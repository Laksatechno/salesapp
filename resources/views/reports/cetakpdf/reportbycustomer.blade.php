<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Laporan Penjualan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body onload="window.print();">
    <div class="container mt-3">
        <h2>Laporan Penjualan - Customer {{ $sales->first()->customer->name ?? 'Unknown' }}</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NO. INV</th>
                    <th>Produk</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Marketing</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->details[0]->product->name }}</td>
                        <td>{{ $sale->details[0]->quantity }}</td>
                        <td>Rp {{ number_format($sale->total + $sale->tax) }}</td>
                        <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                        <td>{{ $sale->marketing->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th class="text-right">Total Penjualan Keseluruhan</th>
                </tr>
            </thead>
            <tbody>
                <tr> 
                    <td class="text-right">Rp {{ number_format($totaljual) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
