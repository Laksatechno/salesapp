@extends('layouts.app')
@section('header')
    @include('layouts.appHeaderback')
@endsection
@section('content')
<div class="section inset mt-2">
    <div class="section-title">Ubah Data Customer</div>
    <div class="wide-block pt-2 pb-2">
    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}" >
        </div>
        <div class="form-group">
            <label for="address">Alamat</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}" required>
        </div>
        <div class="form-group">
            <label for="tipe_pelanggan">Type</label>
            <select class="form-control" id="tipe_pelanggan" name="tipe_pelanggan" required>
                <option value="Reguler" {{ $customer->tipe_pelanggan == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                <option value="Subdis" {{ $customer->tipe_pelanggan == 'Subdis' ? 'selected' : '' }}>Subdis</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success btn-block">Update</button>
    </form>
    </div>
</div>
@endsection
