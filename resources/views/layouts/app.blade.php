<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Si Tempi</title>
<style>
:root{--g:#4E7A2B;--gd:#2F4F1E;--bg:#FFFDEF;--l:#E8EDD0;--r:#B3401F}*{box-sizing:border-box}body{margin:0;font-family:system-ui,sans-serif;background:var(--bg);color:var(--gd)}
nav{background:var(--g);display:flex;gap:4px;flex-wrap:wrap;padding:8px 12px;align-items:center}nav a,nav button{color:#fff;text-decoration:none;padding:8px 12px;border-radius:10px;background:none;border:0;font:inherit;cursor:pointer}nav a.on{background:rgba(255,255,255,.2)}nav .sp{flex:1}
main{max-width:980px;margin:16px auto;padding:0 12px}.card{background:#fff;border:1px solid #DDE3C4;border-radius:16px;padding:14px;margin-bottom:14px}h2,h3{margin:0 0 10px}
table{width:100%;border-collapse:collapse;font-size:14px}th{background:var(--l);text-align:left}th,td{padding:8px;border-bottom:1px solid #E7EBD6}.tw{overflow-x:auto}
input,select{padding:9px;border:1px solid #CFD6B8;border-radius:10px;font:inherit;width:100%}label{font-size:13px;display:block}.row{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}.row>*{flex:1;min-width:130px}
.btn{background:var(--g);color:#fff;border:0;border-radius:10px;padding:9px 14px;font:inherit;cursor:pointer;text-decoration:none;display:inline-block}.btn.s{background:var(--l);color:var(--gd)}.btn.r{background:#F8E3D9;color:var(--r)}
.al{padding:10px 14px;border-radius:12px;margin-bottom:10px}.ok{background:#D5EAD8}.err,.warn{background:#F8E3D9;color:var(--r)}.bar{height:12px;background:var(--l);border-radius:8px;overflow:hidden}.bar i{display:block;height:100%;background:var(--g)}
</style></head><body>
<nav><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard')?'on':'' }}">Beranda</a><a href="{{ route('pesanan') }}" class="{{ request()->routeIs('pesanan')?'on':'' }}">Pesanan</a>
<a href="{{ route('harian') }}" class="{{ request()->routeIs('harian')?'on':'' }}">Produksi Harian</a><a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan')?'on':'' }}">Laporan</a><span class="sp"></span>
<form method="POST" action="{{ route('logout') }}">@csrf<button>🚪 Keluar</button></form></nav>
<main>@foreach(['ok','err','warn'] as $k)@if(session($k))<div class="al {{ $k }}">{{ session($k) }}</div>@endif @endforeach
@if($errors->any())<div class="al err">{{ $errors->first() }}</div>@endif
@yield('isi')</main></body></html>
