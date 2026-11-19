<?php
namespace App\Services;
use App\Models\{Pesanan, Penerimaan, Penggunaan, Rencana, Setting, Jadwal};
use App\Services\StokService as S;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/** Menjembatani state antarmuka (JavaScript) dengan tabel MySQL sesuai ERD. */
class StateService {
    static function export(): array {
        $p = S::persediaan();
        return [
            'now' => (float) $p->stok_aktual, 'min' => (float) $p->batas_minimum, 'max' => (float) Setting::get('stok_maks', 1000),
            'harian' => (float) Setting::get('harian_standar', 65), 'harianStart' => Setting::get('harian_mulai', today()->toDateString()),
            'rencana' => (object) Rencana::all()->mapWithKeys(fn($r) => [$r->tanggal => (float) $r->jumlah])->all(),
            'orders' => Pesanan::orderByDesc('id')->get()->map(fn($o) => ['id' => 'P' . str_pad($o->id, 3, '0', STR_PAD_LEFT), 'nama' => $o->nama_pelanggan, 'jml' => (int) $o->jumlah_pesanan,
                'tanggal' => $o->tanggal_permintaan, 'mulai' => $o->tanggal_mulai, 'alamat' => $o->alamat_pelanggan, 'status' => $o->status_pesanan])->values(),
            'riwayat' => Penerimaan::orderByDesc('tanggal_penerimaan')->orderByDesc('id')->get()->map(fn($r) => ['tgl' => $r->tanggal_penerimaan, 'sumber' => $r->sumber_penerimaan, 'jml' => (float) $r->jumlah_kedelai, 'harga' => (float) $r->harga_per_kg])->values(),
            'penggunaan' => Penggunaan::orderByDesc('tanggal_penggunaan')->orderByDesc('id')->get()->map(fn($u) => ['tgl' => $u->tanggal_penggunaan, 'jadwal' => $u->keterangan, 'jml' => (float) $u->jumlah_digunakan] + ($u->rutin ? ['rutin' => true] : []))->values(),
        ];
    }

    static function import(array $s): void {
        DB::transaction(function () use ($s) {
            S::persediaan()->update(['stok_aktual' => max(0, (float) ($s['now'] ?? 0)), 'batas_minimum' => max(0, (float) ($s['min'] ?? 100))]);
            Setting::put('stok_maks', (float) ($s['max'] ?? 1000)); Setting::put('harian_standar', (float) ($s['harian'] ?? 65));
            Setting::put('harian_mulai', $s['harianStart'] ?? today()->toDateString());
            foreach ([Penggunaan::class, Penerimaan::class, Pesanan::class, Jadwal::class, Rencana::class] as $m) $m::query()->delete();
            foreach ((array) ($s['rencana'] ?? []) as $tgl => $kg) Rencana::create(['tanggal' => $tgl, 'jumlah' => (float) $kg]);

            $orders = collect($s['orders'] ?? []); $jid = [];
            foreach ($orders->filter(fn($o) => in_array($o['status'], ['Dijadwalkan', 'Diproduksi']) && !empty($o['mulai']))->pluck('mulai')->unique() as $m)
                $jid[$m] = Jadwal::create(['tanggal_mulai' => $m, 'tanggal_selesai' => Carbon::parse($m)->addDays(S::LEAD)->toDateString(),
                    'jumlah_kedelai_direncanakan' => S::harianOn($m) + $orders->where('mulai', $m)->sum(fn($o) => S::butuh((int) $o['jml']))])->id;
            foreach ($orders as $o) Pesanan::create(['id' => (int) substr($o['id'], 1), 'id_jadwal' => $jid[$o['mulai'] ?? ''] ?? null, 'nama_pelanggan' => $o['nama'], 'alamat_pelanggan' => $o['alamat'] ?? '-',
                'jumlah_pesanan' => (int) $o['jml'], 'tanggal_permintaan' => $o['tanggal'], 'tanggal_mulai' => $o['mulai'] ?? Carbon::parse($o['tanggal'])->subDays(S::LEAD)->toDateString(),
                'kebutuhan_kedelai' => S::butuh((int) $o['jml']), 'status_pesanan' => $o['status']]);
            foreach ($s['riwayat'] ?? [] as $r) Penerimaan::create(['id_persediaan' => 1, 'tanggal_penerimaan' => $r['tgl'], 'sumber_penerimaan' => $r['sumber'], 'jumlah_kedelai' => (float) $r['jml'], 'harga_per_kg' => (float) ($r['harga'] ?? 0), 'status_penerimaan' => 'diterima']);
            foreach ($s['penggunaan'] ?? [] as $u) Penggunaan::create(['id_persediaan' => 1, 'tanggal_penggunaan' => $u['tgl'], 'keterangan' => mb_substr((string) $u['jadwal'], 0, 100), 'jumlah_digunakan' => (float) $u['jml'], 'rutin' => !empty($u['rutin'])]);
        });
    }
}
