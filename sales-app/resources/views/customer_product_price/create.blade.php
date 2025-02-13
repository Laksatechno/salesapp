@extends('layouts.app')

@section('header')
    @include('layouts.appHeaderback')
@endsection

@section('content')
    <div class="section mt-2">
        <div class="section-title">Tambah Harga Produk</div>
        <div class="wide-block pt-2 pb-2">

            {{-- Form untuk Customer --}}
            <form action="{{ route('customer-product-price.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control select2">
                        <option value="">-- Pilih Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="product_id">Produk</label>
                    <select name="product_id" id="product_id" class="form-control select2">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="text" name="price" id="price" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2 mb-2 d-block d-sm-inline-block w-100 w-sm-auto">Simpan</button>
            </form>

            {{-- Form untuk Customer Langsung --}}
            <form action="{{ route('customer-product-price.storeusercustomer') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user_id">Customer Langsung</label>
                    <select name="user_id" id="user_id" class="form-control select2">
                        <option value="">-- Pilih Customer Langsung --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="product_id">Produk</label>
                    <select name="product_id" id="product_id" class="form-control select2">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="text" name="price" id="price" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2 mb-2 d-block d-sm-inline-block w-100 w-sm-auto">Simpan</button>
            </form>

        </div>
    </div>
@endsection

    {{-- Load jQuery jika belum tersedia --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Load Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih ",
                allowClear: true
            });
        });
    </script>