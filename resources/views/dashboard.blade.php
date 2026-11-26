@extends('layouts.app')@section('isi')
@foreach($warn as $w)<div class="al warn">⚠️ {{ $w }}</div>@endforeach
<div class="card"><h2>Stok Kedelai Saat Ini</h2><h1 style="margin:0">{{ number_format($p->stok_aktual,1,',','.') }} kg</h1>
<div class="bar"><i style="width:{{ min(100,max(0,$p->stok_aktual/10)) }}%"></i></div>
<p>Teralokasi: {{ number_format($alokasi,1,',','.') }} kg · Bebas: <b>{{ number_format($bebas,1,',','.') }} kg</b> · Minimum: {{ $p->batas_minimum }} kg</p>
<form method="POST" action="{{ route('stok') }}" class="row">@csrf<label>Stok aktual (kg)<input name="stok_aktual" type="number" step="any" value="{{ $p->stok_aktual }}"></label><label>Batas minimum (kg)<input name="batas_minimum" type="number" step="any" value="{{ $p->batas_minimum }}"></label><button class="btn s">Atur Stok</button></form></div>
<div class="card"><h3>Ringkasan Pesanan</h3>Menunggu: <b>{{ $cnt['Menunggu']??0 }}</b> · Dijadwalkan: <b>{{ $cnt['Dijadwalkan']??0 }}</b> · Diproduksi: <b>{{ $cnt['Diproduksi']??0 }}</b> · Selesai: <b>{{ $cnt['Selesai']??0 }}</b>
<p>Jadwal terdekat: {{ $next ? "{$next->nama_pelanggan} – mulai {$next->tanggal_mulai}" : 'belum ada' }}</p></div>
<div class="card"><h3>Catat Penerimaan Kedelai</h3><form method="POST" action="{{ route('penerimaan') }}" class="row">@csrf
<label>Tanggal<input type="date" name="tanggal_penerimaan" value="{{ today()->toDateString() }}"></label>
<label>Sumber<select name="sumber_penerimaan"><option>Pemasok Utama</option><option>Toko Terdekat</option></select></label>
<label>Jumlah (kg)<input name="jumlah_kedelai" type="number" step="any"></label><label>Harga/kg (Rp)<input name="harga_per_kg" type="number" step="any"></label>
<label>Status<select name="status_penerimaan"><option value="diterima">Diterima</option><option value="terjadwal">Terjadwal</option></select></label><button class="btn">Simpan</button></form>
<div class="tw"><table><tr><th>Tanggal</th><th>Sumber</th><th>Jumlah</th><th>Harga/kg</th><th>Status</th></tr>@foreach($riwayat as $r)<tr><td>{{ $r->tanggal_penerimaan }}</td><td>{{ $r->sumber_penerimaan }}</td><td>{{ $r->jumlah_kedelai }} kg</td><td>Rp {{ number_format($r->harga_per_kg,0,',','.') }}</td><td>{{ $r->status_penerimaan }}</td></tr>@endforeach</table></div></div>
<form method="POST" action="{{ route('reset') }}" onsubmit="return confirm('Hapus semua data dan mulai dari 0? Akun tetap ada.')">@csrf<button class="btn r">Reset Data (mulai dari 0)</button></form>
@endsection
