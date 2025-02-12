@extends('layouts.app')
@section('header')
    @include('layouts.appHeaderback')
@endsection
@section('content')
<div class="section mt-2"> 
    <div class="section-heading mt-2">
        <h2 class="title"> {{ $sales->first()->customer->name ?? 'tidak ada' }}</h2>
    </div>
    <div class="section-body">
            
            {{-- Filter Input --}}
            <div class="row">
                <div class="col-md-3 form-group">
                    <input type="text" name="search" id="searchInput" class="form-control search" placeholder="Cari invoice atau customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-6 form-group">
                    <input type="text" id="daterange" class="form-control">
                </div>

                <div class="col-md-3 form-group">
                    <button type="button" class="btn btn-primary btn-block btn-print"  >Print</button>
                </div>
            </div>
            <div class="card table-responsive" >

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
                <tbody id="sales-data">
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
                            <td colspan="6" class="text-center">Tidak ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Total Penjualan --}}
            {{-- Total Penjualan --}}
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th style="text-align: right;">Total Penjualan Keseluruhan</th>
                    </tr>
                </thead>
                <tbody id="total-data">
                    <tr> 
                        <td style="text-align: right;">Rp {{ number_format($totaljual) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

@push ('custom-scripts')
{{-- Script untuk filter otomatis --}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- DateRangePicker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- DateRangePicker JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    
    $(document).ready(function () {
        // Ambil customer_id dari Blade ke dalam JavaScript
        let customer_id = "{{ $sales->first()->customer->id ?? '' }}";

        // Inisialisasi Daterangepicker
        $('#daterange').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        function fetchFilteredData() {
            let search = $('#searchInput').val();
            let daterange = $('#daterange').val();
            console.log(search);
            // Pastikan customer_id tersedia sebelum AJAX dipanggil
            if (customer_id) {
                $.ajax({
                    url: '{{ route("reports.reportbycustomer", ":customer_id") }}'.replace(':customer_id', customer_id),
                    type: 'GET',
                    data: { search,  daterange },
                    success: function (response) {
                        let salesData = $(response).find('#sales-data').html();
                        let TotalData = $(response).find('#total-data').html();
                        $('#sales-data').html(salesData);
                        $('#total-data').html(TotalData);

                        // $('#total-penjualan').text(response.totaljual);
                        console.log(TotalData);
                    }
                });
            }
        }

        $('#searchInput, #daterange').on('input change', fetchFilteredData);

        $('.btn-print').click(function () {
            // let customer_id = "{{ $sales->first()->customer->id ?? '' }}";
            let search = $('#searchInput').val(); // Perbaikan di sini
            let daterange = $('#daterange').val();
            console.log(customer_id);
            
            if (!customer_id) {
                Swal.fire('Error', 'Customer ID tidak ditemukan!', 'error');
                return;
            }

            let url = '{{ route("reports.printReport", ":customer_id") }}'
                .replace(':customer_id', customer_id) + 
                "?search=" + encodeURIComponent(search) + 
                "&daterange=" + encodeURIComponent(daterange);
            
            console.log("url: " + url);
            window.open(url, '_blank');
        });


    });


    

</script>
@endpush


@endsection
