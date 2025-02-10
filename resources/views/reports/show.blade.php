@extends('layouts.app')
@section('header')
    @include('layouts.appHeaderback')
@endsection
@section('content')

<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Laporan sales - Product {{ $product->name }}</h2>
    </div>
    <div class="card">
        <div class="card-body table-responsive">
            
            <form id="filter-form" method="GET" action="{{ route('reports.show' , $product->id) }}">
                <div class="row">
                    <!-- Input Search -->
                    <div class="col-md-3 form-group">
                        <input type="text" name="search" class="form-control search" placeholder="Cari invoice atau customer..." value="{{ request('search') }}">
                    </div>
                    
                    <!-- Rentang Waktu -->
                    <div class="form-group col-md-3">
                        <select name="date_range" id="date-range" class="form-control">
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="custom_range" {{ request('date_range') == 'custom_range' ? 'selected' : '' }}>Pilih Rentang Tanggal</option>
                        </select>
                    </div>

                    <!-- Input DateRangePicker -->
                    <div class="form-group col-md-3" id="custom-date-range-container" style="{{ request('date_range') == 'custom_range' ? '' : 'display: none;' }}">
                        <input type="text" name="date_range_picker" id="date-range-picker" class="form-control" placeholder="Tanggal Mulai - Tanggal Akhir">
                        <input type="hidden" name="start_date" id="start-date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="end-date" value="{{ request('end_date') }}">
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="form-group col-md-3">
                        <select name="transaction_type" id="transaction-type" class="form-control">
                            <option value="">Semua Transaksi</option>
                            <option value="customer" {{ request('transaction_type') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="user" {{ request('transaction_type') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>

                    <!-- Marketing -->
                    @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin' || Auth::user()->role == 'keuangan')
                    <div class="form-group col-md-3">
                        <select name="marketing_id" id="marketing-id" class="form-control">
                            <option value="">Pilih Marketing</option>
                            @foreach ($marketings as $marketing)
                            <option value="{{ $marketing->id }}" {{ request('marketing_id') == $marketing->id ? 'selected' : '' }}>{{ $marketing->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </form>
            <button id="print-byproduct-btn" data-product="{{ $product->id }}" class="btn btn-danger btn-sm btn-block mb-2">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <div id="sales-list" class="transactions">
                @include('reports.partials.salesbyproduct', ['sales' => $sales, 'product' => $product])
            </div>
        </div>
    </div>
</div>

@endsection


@push ('custom-scripts')
<!-- Bootstrap CSS -->
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
    
    $(document).ready(function() {
        $('#date-range-picker').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });
            // Event ketika rentang tanggal dipilih
            $('#date-range-picker').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $('#start-date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end-date').val(picker.endDate.format('YYYY-MM-DD'));
            loadSales(); // Muat ulang data penjualan
        });

        // Event ketika rentang tanggal dihapus
        $('#date-range-picker').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $('#start-date').val('');
            $('#end-date').val('');
            loadSales(); // Muat ulang data penjualan
        });



        // Fungsi untuk memuat data penjualan berdasarkan filter
        function loadSales() {
            const formData = $('#filter-form').serialize(); // Ambil data form
            console.log(formData);
            $.ajax({
                url: '{{ route("reports.index") }}',
                method: 'GET',
                data: formData,
                success: function (response) {
                    $('#sales-list').html(response); // Perbarui daftar penjualan
                    console.log(response.sales);
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        }

        // Event listener untuk perubahan pada input filter
        $('.search, #date-range, #transaction-type, #marketing-id').on('input change', function () {
            loadSales(); // Muat ulang data penjualan
        });

        // Toggle input rentang tanggal
        $('#date-range').on('change', function () {
            if ($(this).val() === 'custom_range') {
                $('#custom-date-range-container').show();
            } else {
                $('#custom-date-range-container').hide();
            }
        });

        // Handle tombol cetak PDF
        $(document).on('click', '#print-byproduct-btn', function () {
        const product_id = $(this).data('product'); // Ambil ID produk dari atribut data-product
        const formData = $('#filter-form').serialize(); // Ambil data form

        // Tambahkan product_id ke formData
        const fullData = formData + '&product_id=' + product_id;

        $.ajax({
            url: '{{ route("reports.pdfreportbyproduct") }}',
            method: 'POST',
            data: fullData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                console.log(response.sales);
                const blob = new Blob([response], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);
                window.open(url, '_blank');
                window.URL.revokeObjectURL(url);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mencetak PDF!'
                });
            }
        });
    });

    });

</script>
@endpush