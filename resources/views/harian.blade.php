@extends('layouts.app')@section('isi')
<div class="card"><h3>Produksi Harian</h3><p>Standar {{ $standar }} kg/hari. Tanggal yang tidak diubah memakai standar. Tercatat otomatis tiap hari (✔), ada pesanan atau tidak.</p>
<form method="POST" action="{{ route('harian.simpan') }}" class="row">@csrf<label>Tanggal<input type="date" name="tanggal" value="{{ today()->toDateString() }}"></label><label>Kedelai (kg)<input name="jumlah" type="number" step="any"></label><button class="btn">Simpan (hanya tanggal ini)</button></form>
<form method="POST" action="{{ route('harian.standar') }}" class="row" style="margin-top:10px">@csrf<label>Ubah standar (kg/hari)<input name="jumlah" type="number" step="any" value="{{ $standar }}"></label><button class="btn s">Ubah Standar</button></form></div>
<div class="card tw"><table><tr><th>Tanggal</th><th>Rutin (kg)</th><th>Pesanan (kg)</th><th>Total (kg)</th><th></th></tr>
@foreach($rows as $r)<tr><td>{{ $r['tgl'] }}</td><td>{{ $r['rutin'] }}{{ $r['ubah']?' ✎':'' }}{{ $r['dicatat']?' ✔':'' }}</td><td>{{ $r['pesanan'] }}</td><td>{{ $r['rutin']+$r['pesanan'] }}</td>
<td>@if($r['ubah'])<form method="POST" action="{{ route('harian.reset',$r['tgl']) }}">@csrf @method('DELETE')<button class="btn r">Reset</button></form>@endif</td></tr>@endforeach</table></div>
@endsection
