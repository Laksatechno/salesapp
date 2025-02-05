{{-- create view report by customer --}}
@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2"> 
    <div class="card">
        <div class="card-body table-responsive">
            <h2>Report By Customer</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NO. INV</th>
                        <th>Produk</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Marketing</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->details[0]->product->name }}</td>
                            <td>{{ $sale->details[0]->quantity }}</td>
                            <td>Rp {{ number_format($sale->total + $sale->tax) }}</td>
                            <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                            <td>{{ $sale->marketing->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- create table total sales    --}}
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th style="text-align: right;">Total Penjualan Keseluruhan</th>
                        @php 
                            $total = 0;
                            foreach ($sales as $sale) {
                                $total += $sale->total + $sale->tax;
                            }
                        @endphp
                        <th style="text-align: right;">Rp {{ number_format($total) }}</th>
                    </tr>
                </thead>
        </div>
    </div>
</div>
@endsection