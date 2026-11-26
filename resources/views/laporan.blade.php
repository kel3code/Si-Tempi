@extends('layouts.app')@section('isi')
<div class="card"><h3>Laporan Mingguan {{ $a->toDateString() }} s/d {{ $z->toDateString() }}</h3>
<form class="row" method="GET"><label>Minggu<input type="date" name="minggu" value="{{ $a->toDateString() }}"></label><button class="btn s">Tampilkan</button></form>
@php $ru=$penggunaan->where('rutin',1)->sum('jumlah_digunakan'); $tot=$penggunaan->sum('jumlah_digunakan'); $kg=$pesanan->sum('kebutuhan_kedelai'); $ok=$pesanan->where('status_pesanan','Selesai')->sum('kebutuhan_kedelai'); @endphp
<p>Total Produksi: <b>{{ $tot }} kg</b> (rutin {{ $ru }} + pesanan {{ $tot-$ru }}) · Total Pesanan: <b>{{ $kg }} kg</b> · Capaian: <b>{{ $kg?round($ok/$kg*100):0 }}%</b> · Masuk: {{ $penerimaan->where('status_penerimaan','diterima')->sum('jumlah_kedelai') }} kg</p></div>
@foreach(['pesanan'=>'Riwayat Pesanan','penerimaan'=>'Penerimaan Kedelai','penggunaan'=>'Penggunaan Kedelai'] as $j=>$jd)
<div class="card tw"><h3>{{ $jd }} <a class="btn s" href="{{ route('laporan.pdf',[$j,'minggu'=>$a->toDateString()]) }}">Unduh PDF</a></h3>@include('laporan_tabel',['jenis'=>$j])</div>@endforeach
@endsection
