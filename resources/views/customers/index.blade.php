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
        @if (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin')
        <a href="{{ route('customers.create') }}" class="btn btn-primary btn block mb-1">Tambah Customer</a>
        <a href="{{ route('customer-product-price.index') }}" class="btn btn-danger btn block mb-1">Harga Produk Customer</a>
        @elseif(auth()->user()->role == 'keuangan')
        <button type="button" class="btn btn-xs btn-primary access-failed btn-sm" title="Hapus">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7 12h4V8h1v4h4v1h-4v4h-1v-4H7zm4.5-9a9.5 9.5 0 0 1 9.5 9.5a9.5 9.5 0 0 1-9.5 9.5A9.5 9.5 0 0 1 2 12.5A9.5 9.5 0 0 1 11.5 3m0 1A8.5 8.5 0 0 0 3 12.5a8.5 8.5 0 0 0 8.5 8.5a8.5 8.5 0 0 0 8.5-8.5A8.5 8.5 0 0 0 11.5 4"/></svg>
            <span class="d-none d-md-inline">Tambah Customer</span>
        </button>
        @endif
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
                                @if (auth ()-> user() -> role == 'superadmin' || auth() -> user() -> role == 'admin')
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M20.71 7.04c.39-.39.39-1.04 0-1.41l-2.34-2.34c-.37-.39-1.02-.39-1.41 0l-1.84 1.83l3.75 3.75M3 17.25V21h3.75L17.81 9.93l-3.75-3.75z"/>
                                    </svg>
                                    <span class="d-none d-md-inline"> Edit</span>
                                </a>
                                
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm mb-1" title="Hapus" onclick="confirmDelete('{{ $customer->name }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/>
                                        </svg>
                                        <span class="d-none d-md-inline"> Hapus</span>
                                    </button>
                                </form>   
                                @elseif (auth()->user()->role == 'keuangan')
                                <button type="button" class="btn btn-xs btn-danger access-failed btn-sm" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/>
                                    </svg>
                                    <span class="d-none d-md-inline"> Hapus</span>
                                </button
                                @endif                             
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

        // Fungsi untuk konfirmasi penghapusan dengan swal fire
        function confirmDelete(name) {
            swal.fire({
                title: 'Apakah Anda yakin ingin menghapus customer ini?',
                text: "Data customer " + name + " akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika pengguna mengonfirmasi
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@endpush
@endsection
