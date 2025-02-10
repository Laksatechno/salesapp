@extends('layouts.app')
@section('header')
    @include('layouts.appHeaderback')
@endsection
@section('content')
    <div class="section mt-2">
        <div class="section-heading">
            <b class="card-title">Tambah Penawaran</b>
        </div>
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <div class="wide-block pt-2 pb-2">
                        <form action="{{ url('/penawaran') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Customer</label>
                                <input type="text" name="customer" class="form-control {{ $errors->has('customer') ? 'is-invalid':'' }}" placeholder="Contoh : PMI Yogyakarta">
                                <p class="text-danger">{{ $errors->first('customer') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <input type="text" name="address" col="3" rows="3" class="form-control  {{ $errors->has('address') ? 'is-invalid':'' }}"placeholder="Alamat Outlet">
                                <p class="text-danger">{{ $errors->first('address') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="">Perihal</label>
                                <input type="text" name="perihal" class="form-control {{ $errors->has('perihal') ? 'is-invalid':''  }}" placeholder="Contoh : Penawaran Blood Bag">
                                <p class="text-danger">{{ $errors->first('perihal') }}</p>
                            </div>
                            <button class="btn btn-primary btn-sm btn-block">Tambah</button>
                        </form>
        </div>
    </div>
@endsection
