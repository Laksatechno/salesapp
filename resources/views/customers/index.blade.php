@extends('layouts.app')
@section('header')
    @include('layouts.appHeaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Customer</h2>
        <div class="section-description">

        </div>
    </div>
    <div class="card">
        <div class="card-header">
        <a href="{{ route('customers.create') }}" class="btn btn-primary btn block mb-1">Tambah Customer</a>
        <a href="{{ route('customer-product-price.index') }}" class="btn btn-danger btn block mb-1">Harga Produk Customer</a>
        </div>
        <div class="card-body table-responsive">
            {{-- <h2>Data Customer</h2> --}}
            {{-- <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Tambah Customer</a>
            <a href="{{ route('customer-product-price.index') }}" class="btn btn-primary mb-3">Harga Produk untuk Customer</a> --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table " id="customerTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No. Hp</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Tipe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email ?? '-' }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->tipe_pelanggan }}</td>
                            <td>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M20.71 7.04c.39-.39.39-1.04 0-1.41l-2.34-2.34c-.37-.39-1.02-.39-1.41 0l-1.84 1.83l3.75 3.75M3 17.25V21h3.75L17.81 9.93l-3.75-3.75z"/>
                                    </svg>
                                    <span class="d-none d-md-inline"> Edit</span>
                                </a>
                                
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/>
                                        </svg>
                                        <span class="d-none d-md-inline"> Hapus</span>
                                    </button>
                                </form>                                
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
@push ('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#customerTable').DataTable();
        });
    </script>
@endpush
@endsection
