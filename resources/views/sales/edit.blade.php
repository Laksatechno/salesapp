@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')

<div class="section mt-2">
    <div class="card">
        <div class="card-body">
            <h2>Edit Penjualan</h2>

            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control" >
                        <option value="">Pilih Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" 
                                {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="product_id">Produk</label>
                    <select id="product_id" class="form-control">
                        <option value="">Pilih Produk</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="number" id="price" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah</label>
                    <input type="number" id="quantity" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="diskon_type">Jenis Diskon</label>
                            <select id="diskon_type" class="form-control">
                                <option value="percent">Persen (%)</option>
                                <option value="amount">IDR (Rupiah)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="diskon_value">Nilai Diskon</label>
                            <input type="number" id="diskon_value" class="form-control" placeholder="Masukkan nilai diskon" value="0" step="0.01">
                        </div>
                    </div>
                </div>

                <button type="button" id="addItem" class="btn btn-primary">Tambah Barang</button>

                <table class="table mt-3" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Diskon/Item</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->details as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->price }}</td>
                                <td>{{ $detail->diskon_barang }}</td>
                                <td>{{ $detail->total }}</td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem({{ $loop->index }})">Hapus</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <input type="hidden" name="items" id="itemsInput" value="{{ json_encode($sale->details->map(function($detail) {
                    return [
                        'product_id' => $detail->product_id,
                        'name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'total' => $detail->total,
                        'diskon_barang' => $detail->diskon_barang
                    ];
                })) }}">

                <div class="form-group">
                    <label for="tax_status">Tax Status</label>
                    <select name="tax_status" id="tax_status" class="form-control">
                        <option value="non-ppn" {{ $sale->tax_status == 'non-ppn' ? 'selected' : '' }}>Non-PPN</option>
                        <option value="ppn" {{ $sale->tax_status == 'ppn' ? 'selected' : '' }}>PPN</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="diskon">Diskon (%) </label>
                    <input type="text" name="diskon" class="form-control" placeholder="Misal: 10" value="0">
                </div>

                <div class="form-group">
                    <label for="tanggal"> Tanggal </label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $sale->tanggal }}">
                </div>

                <button type="submit" class="btn btn-success mt-3">Perbarui Penjualan</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const productDropdown = $('#product_id');
        const priceInput = $('#price');

        // Initialize items array with existing sale details
        let items = JSON.parse('{!! addslashes(json_encode($sale->details->map(function($detail) {
            return [
                'product_id' => $detail->product_id,
                'name' => $detail->product->name,
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'total' => $detail->total,
                'diskon_barang' => $detail->diskon_barang
            ];
        }))) !!}');

        // Fungsi untuk memuat produk berdasarkan customer ID
        function loadProducts(customerId) {
            productDropdown.empty().append('<option value="">Pilih Produk</option>');

            if (customerId) {
                $.ajax({
                    url: `/customers/${customerId}/products`,
                    type: 'GET',
                    success: function (products) {
                        products.forEach(product => {
                            productDropdown.append(`
                                <option value="${product.product_id}" 
                                        data-name="${product.product.name}" 
                                        data-price="${product.price}">
                                    ${product.product.name} - ${product.price.toLocaleString()}
                                </option>
                            `);
                        });

                        // Set harga awal jika produk sudah dipilih
                        setInitialProductPrice();
                    },
                    error: function () {
                        alert('Gagal memuat produk.');
                    }
                });
            }
        }

        // Set harga awal jika ada produk yang dipilih
        function setInitialProductPrice() {
            const selectedOption = productDropdown.find(':selected');
            const price = selectedOption.data('price') || 0;
            priceInput.val(price);
        }

        // Update tabel items
        function updateItemsTable() {
            const tableBody = $('#itemsTable tbody');
            tableBody.empty();

            items.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price}</td>
                        <td>${item.diskon_barang}</td>
                        <td>${item.total}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/>
                                        </svg>
                                        <span class="d-none d-md-inline"> Hapus</span>
                            </button>
                        </td>
                    </tr>`;
                tableBody.append(row);
            });

            $('#itemsInput').val(JSON.stringify(items));
        }

        // Tambahkan item baru
        $('#addItem').click(function () {
            const productId = $('#product_id').val();
            const productName = $('#product_id option:selected').text();
            const quantity = parseInt($('#quantity').val(), 10);
            let price = parseFloat($('#price').val());
            const diskonType = $('#diskon_type').val();
            const diskonValue = parseFloat($('#diskon_value').val()) || 0;
            
            let diskon_barang = 0;
            if (diskonType === 'percent') {
                diskon_barang = (price * diskonValue) / 100;
            } else {
                diskon_barang = diskonValue;
            }
            price -= diskon_barang;
            const total = quantity * price;

            items.push({ product_id: productId, name: productName, quantity, price, diskon_barang, total });
            updateItemsTable();
        });

        // Hapus item dari tabel
        window.removeItem = function (index) {
            items.splice(index, 1);
            updateItemsTable();
        };

        // Event ketika produk diubah
        productDropdown.change(function () {
            const selectedOption = $(this).find(':selected');
            const price = selectedOption.data('price') || 0;
            priceInput.val(price);
        });

        // Muat produk ketika customer diubah
        $('#customer_id').change(function () {
            const customerId = $(this).val();
            loadProducts(customerId);
        });

        // Muat produk dan data awal ketika halaman dimuat
        const initialCustomerId = $('#customer_id').val();
        if (initialCustomerId) {
            loadProducts(initialCustomerId);
        } else {
            updateItemsTable();
        }
    });
</script>

@endsection