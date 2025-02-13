<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
            margin: 2px;
            padding: 0;
            color: #000000;
        }


        h1 {
            text-align: center;
            font-size: 26px;
            text-transform: uppercase;
            /* margin-bottom: 10px;
            border-bottom: 2px solid #000000; */
            padding-bottom: 10px;
        }
        p {
            text-align: center;
            font-size: 12px;
            margin-bottom: 10px;
            border-bottom: 2px solid #000000;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #a5a5a5;
            text-align: left;
        }

        table thead {
            background: #ffffff;
            color: rgb(0, 0, 0);
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .total-row {
            font-weight: bold;
            background: #ffffff;
            color: rgb(0, 0, 0);
        }

        .total-row td {
            padding: 12px;
        }

        .text-right {
            text-align: right;
        }

        .label {
            display: inline-block;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            border-radius: 4px;
        }

        .label-success { background-color: #28a745; }
        .label-warning { background-color: #ffc107; }
        .label-info { background-color: #17a2b8; }
        .label-danger { background-color: #dc3545; }

    </style>
</head>
<body>
    <section class="container_box">
        <h1>PT Laksa Medika Internusa</h1>
        <p> Jl. Amarta No. 12A RT01, RW.01, Pelem, Baturetno, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198</p>
        <div class="content_box">
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th class="text-right">Total</th>
                        <th>Marketing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->customer->name ?? $sale->users->name }}</td>
                        <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                        <td class="text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td>{{ $sale->marketing->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total Penjualan Keseluruhan</td>
                        <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    </section>
</body>
</html>
