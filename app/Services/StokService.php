<?php
namespace App\Services;
use App\Models\{Persediaan, Pesanan, Penggunaan, Rencana, Setting, Jadwal};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StokService {
    const PER_PAPAN = 0.6;   // kg kedelai per papan (asumsi, konfirmasi ke pengelola)
    const LEAD = 3;          // hari sebelum tanggal permintaan

    static function persediaan(): Persediaan { return Persediaan::firstOrCreate(['id' => 1], ['stok_aktual' => 0, 'batas_minimum' => 100]); }
    static function butuh(int $papan): float { return round($papan * self::PER_PAPAN, 2); }
    static function alokasi(): float { return (float) Pesanan::where('status_pesanan', 'Dijadwalkan')->sum('kebutuhan_kedelai'); }
    static function bebas(): float { return (float) self::persediaan()->stok_aktual - self::alokasi(); }
    static function standar(): float { return (float) Setting::get('harian_standar', 65); }
    static function harianOn(string $d): float { $r = Rencana::find($d); return $r ? (float) $r->jumlah : self::standar(); }
    static function mulaiOf(string $tanggalPermintaan): string { return Carbon::parse($tanggalPermintaan)->subDays(self::LEAD)->toDateString(); }

    /** Catat / koreksi produksi rutin satu tanggal, stok ikut menyesuaikan. */
    static function setRutin(string $d, float $v): void {
        DB::transaction(function () use ($d, $v) {
            $p = Persediaan::lockForUpdate()->find(self::persediaan()->id);
            $e = Penggunaan::where('rutin', true)->where('tanggal_penggunaan', $d)->first();
            $diff = $e ? $v - $e->jumlah_digunakan : $v;
            $e ? $e->update(['jumlah_digunakan' => $v])
               : Penggunaan::create(['id_persediaan' => $p->id, 'tanggal_penggunaan' => $d, 'keterangan' => 'Produksi rutin harian', 'jumlah_digunakan' => $v, 'rutin' => true]);
            $p->update(['stok_aktual' => max(0, $p->stok_aktual - $diff)]);
        });
    }
    static function syncRutin(string $d): void {
        $ada = Penggunaan::where('rutin', true)->where('tanggal_penggunaan', $d)->exists();
        if ($d <= today()->toDateString() && ($ada || $d >= Setting::get('harian_mulai', today()->toDateString()))) self::setRutin($d, self::harianOn($d));
    }
    /** Dipanggil scheduler tiap hari: isi semua tanggal yang belum tercatat sampai hari ini. */
    static function postHarian(): int {
        $mulai = Setting::get('harian_mulai') ?? tap(today()->toDateString(), fn($t) => Setting::put('harian_mulai', $t)); $n = 0;
        for ($d = Carbon::parse($mulai); $d->lte(today()); $d->addDay()) {
            if (!Penggunaan::where('rutin', true)->where('tanggal_penggunaan', $d->toDateString())->exists()) { self::setRutin($d->toDateString(), self::harianOn($d->toDateString())); $n++; }
        }
        return $n;
    }
    static function peringatan(): array {
        $w = []; $p = self::persediaan();
        if ($p->stok_aktual <= $p->batas_minimum) $w[] = "Stok mencapai batas minimum ({$p->stok_aktual} kg, minimum {$p->batas_minimum} kg).";
        if (self::alokasi() > $p->stok_aktual) $w[] = 'Stok tidak cukup untuk pesanan terjadwal, kurang ' . round(self::alokasi() - $p->stok_aktual, 2) . ' kg.';
        foreach (Pesanan::where('status_pesanan', 'Menunggu')->get() as $o)
            if ($o->kebutuhan_kedelai > self::bebas()) $w[] = "Pesanan #{$o->id} ({$o->nama_pelanggan}) butuh {$o->kebutuhan_kedelai} kg, stok bebas " . round(self::bebas(), 2) . ' kg.';
        return $w;
    }
    static function setujui(Pesanan $o): void {
        if ($o->status_pesanan !== 'Menunggu') throw new RuntimeException('Pesanan sudah diproses.');
        if (self::bebas() < $o->kebutuhan_kedelai) throw new RuntimeException('Stok bebas tidak cukup. Tambah stok atau jadwalkan ulang.');
        DB::transaction(function () use ($o) {
            $j = Jadwal::firstOrCreate(['tanggal_mulai' => $o->tanggal_mulai], ['tanggal_selesai' => Carbon::parse($o->tanggal_mulai)->addDays(self::LEAD)->toDateString()]);
            $o->update(['status_pesanan' => 'Dijadwalkan', 'id_jadwal' => $j->id]);
            $j->update(['jumlah_kedelai_direncanakan' => self::harianOn($j->tanggal_mulai) + Pesanan::where('id_jadwal', $j->id)->sum('kebutuhan_kedelai')]);
        });
    }
    static function mulai(Pesanan $o, float $pakai): void {
        if ($o->status_pesanan !== 'Dijadwalkan') throw new RuntimeException('Pesanan belum dijadwalkan.');
        if (today()->toDateString() < $o->tanggal_mulai) throw new RuntimeException("Produksi baru bisa dimulai pada {$o->tanggal_mulai}.");
        DB::transaction(function () use ($o, $pakai) {
            $p = Persediaan::lockForUpdate()->find(self::persediaan()->id);
            if ($pakai <= 0 || $pakai > $p->stok_aktual) throw new RuntimeException('Pemakaian tidak valid atau stok tidak cukup.');
            Penggunaan::create(['id_persediaan' => $p->id, 'id_pesanan' => $o->id, 'tanggal_penggunaan' => today(), 'keterangan' => "#{$o->id} - {$o->nama_pelanggan}", 'jumlah_digunakan' => $pakai, 'rutin' => 0]);
            $p->decrement('stok_aktual', $pakai); $o->update(['status_pesanan' => 'Diproduksi']);
        });
    }
}
