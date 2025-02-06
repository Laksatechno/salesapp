<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .text-center {
            text-align: center;
        }
        .mt-3 {
            margin-top: 1rem;
        }
        .total-sales {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            font-size: 16px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan Produk</h1>
        <p>Periode: {{ now()->format('d-m-Y') }}</p>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice Number</th>
                <th>Customer</th>
                <th>Produk</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Marketing</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                @foreach ($sale->details as $detail)
                    @if ($detail->product_id == $product->id)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->customer->name ?? $sale->users->name }}</td>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->total) }}</td>
                            <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                            <td>{{ $sale->marketing->name }}</td>
                        </tr>
                    @endif
                @endforeach
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data penjualan untuk produk ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="table mt-3">
        <thead>
            <tr>
                <th style="text-align: right;">Total Penjualan Keseluruhan Untuk Produk {{ $product->name }} :</th>
                @php 
                    $total = 0;
                    foreach ($sales as $sale) {
                        foreach ($sale->details as $detail) {
                            if ($detail->product_id == $product->id) {
                                $total += $detail->total;
                            }
                        }
                    }
                @endphp
                <th style="text-align: right;" class="total-sales">Rp {{ number_format($total) }}</th>
            </tr>
        </thead>
    </table>
</body>
</html>