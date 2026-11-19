<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('persediaan_kedelai', function (Blueprint $t) {
            $t->id(); $t->decimal('stok_aktual', 10, 2)->default(0); $t->decimal('batas_minimum', 10, 2)->default(100); $t->timestamp('tanggal_pembaruan')->useCurrent()->useCurrentOnUpdate();
        });
        Schema::create('jadwal_produksi', function (Blueprint $t) {
            $t->id(); $t->date('tanggal_mulai')->unique(); $t->date('tanggal_selesai'); $t->decimal('jumlah_kedelai_direncanakan', 10, 2)->default(0);
        });
        Schema::create('pesanan', function (Blueprint $t) {
            $t->id(); $t->foreignId('id_jadwal')->nullable()->constrained('jadwal_produksi')->nullOnDelete();
            $t->string('nama_pelanggan', 100); $t->text('alamat_pelanggan'); $t->unsignedInteger('jumlah_pesanan');
            $t->date('tanggal_permintaan'); $t->date('tanggal_mulai'); $t->decimal('kebutuhan_kedelai', 10, 2);
            $t->enum('status_pesanan', ['Menunggu', 'Dijadwalkan', 'Diproduksi', 'Selesai'])->default('Menunggu'); $t->timestamps();
        });
        Schema::create('penerimaan_kedelai', function (Blueprint $t) {
            $t->id(); $t->foreignId('id_persediaan')->constrained('persediaan_kedelai'); $t->date('tanggal_penerimaan');
            $t->string('sumber_penerimaan', 50); $t->decimal('jumlah_kedelai', 10, 2); $t->decimal('harga_per_kg', 12, 2)->default(0);
            $t->enum('status_penerimaan', ['terjadwal', 'diterima'])->default('diterima');
        });
        Schema::create('penggunaan_kedelai', function (Blueprint $t) {
            $t->id(); $t->foreignId('id_persediaan')->constrained('persediaan_kedelai'); $t->foreignId('id_pesanan')->nullable()->constrained('pesanan')->nullOnDelete();
            $t->date('tanggal_penggunaan'); $t->string('keterangan', 100); $t->decimal('jumlah_digunakan', 10, 2); $t->boolean('rutin')->default(false);
            $t->index(['rutin', 'tanggal_penggunaan']);
        });
        Schema::create('rencana_produksi_harian', function (Blueprint $t) { $t->date('tanggal')->primary(); $t->decimal('jumlah', 10, 2); });
        Schema::create('pengaturan', function (Blueprint $t) { $t->string('kunci', 50)->primary(); $t->string('nilai', 100); });
    }
    public function down(): void { foreach (['pengaturan','rencana_produksi_harian','penggunaan_kedelai','penerimaan_kedelai','pesanan','jadwal_produksi','persediaan_kedelai'] as $x) Schema::dropIfExists($x); }
};
