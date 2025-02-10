<!DOCTYPE html>
<html lang="id">
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
            text-align: right;
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
        <h1>Laporan Penjualan Produk: {{ $product->name }}</h1>
        <p>Periode: {{ now()->format('d-m-Y') }}</p>
    </div>

    <table class="table">
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
            @php $total = 0; @endphp
            @forelse ($sales as $salesData)
                @foreach ($salesData->details as $detail)
                    @if ($detail->product_id == $product->id)
                        @php $total += $detail->total; @endphp
                        <tr>
                            <td>{{ $salesData->invoice_number }}</td>
                            <td>{{ $salesData->customer->name ?? $salesData->users->name }}</td>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                            <td>{{ $salesData->created_at->format('d-m-Y') }}</td>
                            <td>{{ $salesData->marketing->name ?? '-' }}</td>
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
        <tr>
            <th class="total-sales">Total Penjualan: Rp {{ number_format($total, 0, ',', '.') }}</th>
        </tr>
    </table>
</body>
</html>
