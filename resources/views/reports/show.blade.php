
@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')

<div class="section mt-2">
    <div class="card">
        <div class="card-body table-responsive">
            <h2>Penjualan untuk Produk: {{ $product->name }}</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Customer</th>
                        <th>Produk</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Marketing</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        @foreach ($sale->details as $detail)
                            @if ($detail->product_id == $product->id)
                                <tr>
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td>{{ $sale->customer->name ?? $sale->users->name }}</td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp {{ number_format($detail->total) }}</td>
                                    <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $sale->marketing->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penjualan untuk produk ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- create table total sales kesuluruhan --}}
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th style="text-align: right;">Total Penjualan Keseluruhan {{ $product->name }}</th>
                        @php 
                            $total = 0;
                            foreach ($sales as $sale) {
                                foreach ($sale->details as $detail) {
                                    if ($detail->product_id == $product->id) {
                                        $total += $detail->total;
                                    }
                                }
                            }
                        @endphp
                        <th style="text-align: right;">Rp {{$total}}</th>
                    </tr>
                </thead>
            </table>

            
        </div>
    </div>
</div>
@endsection