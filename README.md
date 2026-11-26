# Si Tempi – Laravel + MySQL (tampilan identik prototipe)

Tampilan dan perilaku = prototipe HTML (`resources/views/app.blade.php`). Data tersimpan di MySQL (7 tabel sesuai ERD),
login/daftar/logout memakai autentikasi Laravel. Tidak perlu Breeze, npm, atau dompdf (PDF dibuat di browser).

```bash
composer create-project laravel/laravel:^12.0 si-tempi && cd si-tempi
# salin isi folder ini (app, database, resources, routes) ke proyek, pilih Replace
# .env: DB_CONNECTION=mysql, DB_DATABASE=si_tempi, DB_USERNAME=root, DB_PASSWORD=...
php artisan migrate
php artisan serve        # http://localhost:8000
```
Cron produksi harian: `* * * * * php /path/si-tempi/artisan schedule:run`

## Deploy online (Docker, mis. Render)
Isi repo = isi folder ini (Dockerfile, docker/, app/, database/, resources/, routes/). Env wajib:
APP_KEY, APP_ENV=production, APP_DEBUG=false, APP_URL, DB_CONNECTION (mysql atau pgsql), DB_HOST, DB_PORT, DB_DATABASE,
DB_USERNAME, DB_PASSWORD, (pgsql: DB_SSLMODE=require), LOG_CHANNEL=stderr.
Pendaftaran akun otomatis ditutup setelah akun pertama; buka lagi dengan env ALLOW_REGISTER=true.
