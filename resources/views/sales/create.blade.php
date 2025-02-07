@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')

<div class="section mt-2">
    <div class="card">
        <div class="card-body">
            <h2>Tambah Penjualan</h2>

            <form action="{{ route('sales.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control select2">
                        <option value="">Pilih Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="product_id">Produk</label>
                    <select id="product_id" class="form-control select2">
                        <option value="">Pilih Produk</option>
                    </select>
                    @error('product_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" id="stock" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" id="price" class="form-control" readonly>
                        </div>
                    </div>
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

                <card class="mt-3 table-responsive">
                <table class="table mt-3" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Diskon/Item</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </card>

                <input type="hidden" name="items" id="itemsInput">
                <div class="form-group">
                    <label for="due_date">Jenis Tempo</label>
                    <select name="due_date" id="due_date" class="form-control">
                        <option value="1">1 Bulan</option>
                        <option value="2">2 Bulan</option>
                        <option value="3">Cash</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tax_status">Tax Status</label>
                    <select name="tax_status" id="tax_status" class="form-control">
                        <option value="non-ppn">Non-PPN</option>
                        <option value="ppn">PPN</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-3">Buat Penjualan</button>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan jQuery dan Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            // placeholder: "Pilih Customer atau Produk",
            // allowClear: true
        });

        $('#customer_id').change(function () {
            const customerId = $(this).val();
            const productDropdown = $('#product_id');

            productDropdown.empty().append('<option value="">Pilih Produk</option>').trigger('change');

            if (!customerId) {
                return;
            }

            $.ajax({
                url: `/customers/${customerId}/products`,
                type: 'GET',
                success: function (products) {
                    products.forEach(product => {
                        productDropdown.append(`
                            <option value="${product.product_id}" 
                                data-name="${product.product.name}" 
                                data-price="${product.price}" 
                                data-stock="${product.product.stock}">
                                ${product.product.name} - Stok: ${product.product.stock} - Rp. ${product.price.toLocaleString()}
                            </option>
                        `);
                    });
                    productDropdown.trigger('change'); // Refresh Select2
                },
                error: function () {
                    alert('Gagal memuat produk.');
                }
            });
        });

        $('#product_id').change(function () {
            const selectedOption = $(this).find(':selected');
            const price = selectedOption.data('price') || 0;
            const stock = selectedOption.data('stock') || 0;

            $('#price').val(price);
            $('#stock').val(stock);
            $('#addItem').prop('disabled', stock === 0);
            $('#quantity').attr('max', stock); // Batasi jumlah sesuai stok
            $('#quantity').val('');
        });

        let items = [];

        function updateItemsTable() {
            const tableBody = $('#itemsTable tbody');
            tableBody.empty();

            items.forEach((item, index) => {
                const row = `<tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${item.price}</td>
                    <td>${item.total}</td>
                    <td>${item.diskon_barang}</td>
                    <td><button type="button" class="btn btn-danger btn-sm removeItem" data-index="${index}">Hapus</button></td>
                </tr>`;
                tableBody.append(row);
            });

            $('#itemsInput').val(JSON.stringify(items));
        }

        // Fungsi untuk menghapus item dari tabel dan mengembalikan stok
        $(document).on('click', '.removeItem', function () {
            const index = $(this).data('index');
            const removedItem = items[index];

            // Kembalikan stok produk yang dihapus
            const productOption = $(`#product_id option[value="${removedItem.product_id}"]`);
            let currentStock = parseInt(productOption.data('stock'), 10);
            let newStock = currentStock + removedItem.quantity;

            productOption.data('stock', newStock);
            productOption.text(`${removedItem.name} - Stok: ${newStock} - Rp. ${removedItem.price.toLocaleString()}`);

            // Hapus item dari array
            items.splice(index, 1);
            updateItemsTable();
        });

        $('#addItem').click(function () {
        const productId = $('#product_id').val();
        const productName = $('#product_id option:selected').data('name');
        const quantity = parseInt($('#quantity').val(), 10);
        const price = parseFloat($('#price').val());
        const diskonType = $('#diskon_type').val();
        let diskonValue = parseFloat($('#diskon_value').val()) || 0;
        let stock = parseInt($('#product_id option:selected').data('stock'), 10);

        if (!productId || !quantity || !price || quantity > stock) {
            alert('Lengkapi data barang dengan benar atau jumlah melebihi stok.');
            return;
        }

        let total = quantity * price;
        let diskonBarang = 0;

        // Perhitungan diskon
        if (diskonType === "percent") {
            diskonBarang = (total * diskonValue) / 100;
        } else if (diskonType === "amount") {
            diskonBarang = diskonValue;
        }

        // Pastikan diskon tidak lebih besar dari total harga
        if (diskonBarang > total) {
            diskonBarang = total;
        }

        let totalSetelahDiskon = total - diskonBarang;
        stock -= quantity;

        $(`#product_id option[value="${productId}"]`).data('stock', stock);
        $(`#product_id option[value="${productId}"]`).text(`${productName} - Stok: ${stock} - Rp. ${price.toLocaleString()}`);

        items.push({
            product_id: productId,
            name: productName,
            quantity,
            price,
            total: totalSetelahDiskon,
            diskon_barang: diskonBarang
        });

        updateItemsTable();

        $('#product_id').val('').trigger('change');
        $('#quantity').val('');
        $('#price').val('');
        $('#stock').val('');
    });
    });
</script>
@endsection
