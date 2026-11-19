<?php
namespace App\Console\Commands;
use App\Services\StokService;
use Illuminate\Console\Command;
class CatatProduksiHarian extends Command {
    protected $signature = 'tempi:catat-harian'; protected $description = 'Catat produksi rutin harian otomatis';
    public function handle(): int { $this->info(StokService::postHarian() . ' hari dicatat.'); return 0; }
}
