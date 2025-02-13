<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: Arial, sans-serif; }
        .kop-surat { text-align: center; margin-bottom: 20px; }
        .kop-surat h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .kop-surat p { margin: 0; font-size: 14px; }
        .line { border-top: 2px solid black; margin: 10px 0 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 40px; text-align: right; font-style: italic; }
    </style>
</head>
<body >
    <div class="container mt-3">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src=" {{ public_path('assets/img/logo_ptlmi.webp') }}" alt="Logo Perusahaan" width="80" style="position: absolute; left: 30px;">
            <h1>PT Laksa Medika Internusa</h1>
            <p> Jl. Amarta No. 12A RT01, RW.01, Pelem, Baturetno, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198</p>
        </div>
        <div class="line"></div>

        <h4 class="text-center">Laporan Penjualan</h4>
        <p><strong>Customer:</strong> {{ $sales->first()->customer->name ??  $sales->first()->users->name ?? 'Unknown' }}</p>
        
        <!-- Tabel Laporan Penjualan -->
        <table class="table table-bordered ">
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
                @forelse ($sales as $sale)
                    @foreach ($sale->details as $detail)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($sale->total) }}</td>
                            <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                            <td>{{ $sale->marketing->name }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Tidak ada data penjualan</td>
                    </tr>
                @endforelse
            </tbody>
            <tbody>
                <tr>
                    <th colspan="12" class="text-right">Total Penjualan Keseluruhan Rp {{ number_format($totaljual) }}</th>
                </tr>
            </tbody>
        </table>


        <!-- Footer -->
        <div class="footer">
            <p>Yogyakarta, {{ date('Y-m-d')  }}</p>
            {{-- <p><strong>Manager Keuangan</strong></p>
            <br><br>
            <p>(___________________)</p> --}}
        </div>
    </div>
</body>
</html>
