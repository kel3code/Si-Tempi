<?php
namespace App\Http\Controllers;
use App\Models\{Persediaan, Pesanan, Penerimaan, Penggunaan, Rencana, Setting, Jadwal};
use App\Services\StokService as S;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SiTempiController extends Controller {
    private function aman(callable $f, string $ok) {
        try { $f(); return back()->with('ok', $ok); } catch (RuntimeException $e) { return back()->with('err', $e->getMessage()); }
    }
    function dashboard() {
        S::postHarian(); // cadangan jika cron belum aktif
        return view('dashboard', ['p' => S::persediaan(), 'alokasi' => S::alokasi(), 'bebas' => S::bebas(), 'warn' => S::peringatan(),
            'cnt' => Pesanan::select('status_pesanan', DB::raw('count(*) n'))->groupBy('status_pesanan')->pluck('n', 'status_pesanan'),
            'next' => Pesanan::where('status_pesanan', 'Dijadwalkan')->orderBy('tanggal_mulai')->first(),
            'riwayat' => Penerimaan::orderByDesc('tanggal_penerimaan')->limit(8)->get()]);
    }
    // ---- Pesanan
    function pesanan(Request $r) {
        $q = Pesanan::query()->when($r->status, fn($x) => $x->where('status_pesanan', $r->status))->when($r->cari, fn($x) => $x->where('nama_pelanggan', 'like', "%{$r->cari}%"));
        return view('pesanan', ['list' => $q->latest('id')->get(), 'bebas' => S::bebas()]);
    }
    function simpanPesanan(Request $r) {
        $d = $r->validate(['nama_pelanggan' => 'required|max:100', 'alamat_pelanggan' => 'required', 'jumlah_pesanan' => 'required|integer|min:1', 'tanggal_permintaan' => 'required|date']);
        $mulai = S::mulaiOf($d['tanggal_permintaan']);
        if ($mulai < today()->toDateString() && !$r->boolean('tetap_simpan'))
            return back()->withInput()->with('warn', "Mulai produksi seharusnya {$mulai}, sudah lewat. Centang \"tetap simpan\" jika tetap ingin menyimpan.");
        Pesanan::create($d + ['tanggal_mulai' => $mulai, 'kebutuhan_kedelai' => S::butuh($d['jumlah_pesanan'])]);
        return back()->with('ok', 'Pesanan tersimpan (Menunggu).');
    }
    function setujui(Pesanan $pesanan) { return $this->aman(fn() => S::setujui($pesanan), 'Pesanan disetujui dan dijadwalkan.'); }
    function hapus(Pesanan $pesanan) { // tolak / batalkan = hapus data
        if (!in_array($pesanan->status_pesanan, ['Menunggu', 'Dijadwalkan'])) return back()->with('err', 'Pesanan yang sudah diproduksi tidak bisa dihapus.');
        $pesanan->delete(); return back()->with('ok', 'Pesanan dihapus.');
    }
    function mulai(Request $r, Pesanan $pesanan) { return $this->aman(fn() => S::mulai($pesanan, (float) $r->input('pakai', $pesanan->kebutuhan_kedelai)), 'Produksi dimulai, stok berkurang.'); }
    function selesai(Pesanan $pesanan) { if ($pesanan->status_pesanan === 'Diproduksi') $pesanan->update(['status_pesanan' => 'Selesai']); return back()->with('ok', 'Pesanan selesai.'); }
    // ---- Persediaan
    function terima(Request $r) {
        $d = $r->validate(['tanggal_penerimaan' => 'required|date', 'sumber_penerimaan' => 'required|in:Pemasok Utama,Toko Terdekat', 'jumlah_kedelai' => 'required|numeric|min:0.1', 'harga_per_kg' => 'nullable|numeric|min:0', 'status_penerimaan' => 'required|in:diterima,terjadwal']);
        DB::transaction(function () use ($d) {
            $p = Persediaan::lockForUpdate()->find(S::persediaan()->id);
            Penerimaan::create($d + ['id_persediaan' => $p->id, 'harga_per_kg' => $d['harga_per_kg'] ?? 0]);
            if ($d['status_penerimaan'] === 'diterima') $p->increment('stok_aktual', $d['jumlah_kedelai']); // terjadwal tidak menambah stok aktual
        });
        return back()->with('ok', 'Penerimaan tercatat.');
    }
    function aturStok(Request $r) {
        $d = $r->validate(['stok_aktual' => 'required|numeric|min:0', 'batas_minimum' => 'required|numeric|min:0']);
        S::persediaan()->update($d); return back()->with('ok', 'Stok diperbarui.');
    }
    // ---- Produksi harian (per tanggal, terpisah dari pesanan)
    function harian() {
        $rows = collect(range(-2, 11))->map(fn($i) => today()->addDays($i)->toDateString())->map(fn($d) => [
            'tgl' => $d, 'rutin' => ($e = Penggunaan::where('rutin', 1)->where('tanggal_penggunaan', $d)->first()) ? (float) $e->jumlah_digunakan : S::harianOn($d),
            'dicatat' => (bool) $e, 'ubah' => Rencana::whereKey($d)->exists(), 'pesanan' => (float) Pesanan::whereIn('status_pesanan', ['Dijadwalkan', 'Diproduksi'])->where('tanggal_mulai', $d)->sum('kebutuhan_kedelai')]);
        return view('harian', ['rows' => $rows, 'standar' => S::standar()]);
    }
    function simpanHarian(Request $r) {
        $d = $r->validate(['tanggal' => 'required|date', 'jumlah' => 'required|numeric|min:0']);
        $d['jumlah'] == S::standar() ? Rencana::whereKey($d['tanggal'])->delete() : Rencana::updateOrCreate(['tanggal' => $d['tanggal']], ['jumlah' => $d['jumlah']]);
        S::syncRutin($d['tanggal']); return back()->with('ok', "Produksi {$d['tanggal']}: {$d['jumlah']} kg (hanya tanggal ini).");
    }
    function resetHarian(string $tanggal) { Rencana::whereKey($tanggal)->delete(); S::syncRutin($tanggal); return back()->with('ok', 'Kembali ke standar.'); }
    function standar(Request $r) { Setting::put('harian_standar', $r->validate(['jumlah' => 'required|numeric|min:0'])['jumlah']); S::syncRutin(today()->toDateString()); return back()->with('ok', 'Standar diperbarui.'); }
    // ---- Laporan mingguan
    private function dataLaporan(Request $r): array {
        $a = Carbon::parse($r->minggu ?? today()->startOfWeek())->startOfWeek(); $z = $a->copy()->addDays(6); $rg = [$a->toDateString(), $z->toDateString()];
        return ['a' => $a, 'z' => $z,
            'pesanan' => Pesanan::whereBetween('tanggal_permintaan', $rg)->orderBy('tanggal_permintaan')->get(),
            'penerimaan' => Penerimaan::whereBetween('tanggal_penerimaan', $rg)->orderBy('tanggal_penerimaan')->get(),
            'penggunaan' => Penggunaan::whereBetween('tanggal_penggunaan', $rg)->orderBy('tanggal_penggunaan')->get()];
    }
    function laporan(Request $r) { return view('laporan', $this->dataLaporan($r)); }
    function laporanPdf(Request $r, string $jenis) {
        abort_unless(in_array($jenis, ['pesanan', 'penerimaan', 'penggunaan']), 404); $d = $this->dataLaporan($r);
        return Pdf::loadView('laporan_pdf', $d + ['jenis' => $jenis])->setPaper('a4', 'landscape')->download("laporan-{$jenis}-{$d['a']->toDateString()}.pdf");
    }
    // ---- Reset dari 0 (akun tetap)
    function reset() {
        DB::transaction(function () {
            foreach ([Penggunaan::class, Penerimaan::class, Pesanan::class, Jadwal::class, Rencana::class] as $m) $m::query()->delete();
            S::persediaan()->update(['stok_aktual' => 0, 'batas_minimum' => 100]); Setting::put('harian_standar', 65); Setting::put('harian_mulai', today()->toDateString());
        });
        return redirect()->route('dashboard')->with('ok', 'Data direset. Isi stok awal untuk mulai.');
    }
}
