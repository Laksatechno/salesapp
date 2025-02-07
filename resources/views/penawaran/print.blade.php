<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Penawaran</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            text-align: left;
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
            background-color: #ffffff;
        }

        .container {
            margin: 0 auto;
            padding: 20px;
            width: 700px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        header {
            text-align: left;
            margin-bottom: 20px;
            margin: 0 auto
        }

        header img {
            width: 150px;
            height: auto;
        }

        h1, h2, h3, h4 {
            color: #974578;
            margin: 0;
        }

        h1 {
            font-size: 24px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        h4 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        p {
            margin: 0 0 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ffffff;
            text-align: left;
        }

        th {
            background-color: #ffffff;
            font-weight: bold;
        }

        .page_break {
            page-break-before: always;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px 0;
            color: #974578;
            font-size: 12px;
            border-top: 0.5px solid #000000;
            background-color: #fff; /* Tambahkan background agar konten di belakang footer tidak terlihat */
            z-index: 1000; /* Pastikan footer selalu di atas elemen lain */
        }

        .signature {
            text-align: right;
            margin-top: 40px;
        }

        .signature img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .signature b {
            display: block;
            margin-top: 5px;
        }

        .contact-info {
            text-align: center;
            margin: 20px 0;
            color: #974578;
        }

        .contact-info h4 {
            margin-bottom: 5px;
        }

        .contact-info p {
            margin: 0;
        }

        .conditions {
            margin: 20px 0;
        }

        .conditions p {
            margin: 5px 0;
        }

        .product-table {
            margin: 20px 0;
        }

        .product-table th, .product-table td {
            text-align: center;
        }

        .product-table td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="{{ public_path('assets/img/logo_ptlmi.webp') }}" alt="Logo">
        </header>

        <div align="center" style="margin: 100px">
            <img src="{{ public_path('assets/img/icon/512x512.png') }}" width="100%" height="auto">
        </div>

        <div>
            <h1 align="center">{{$penawarans->perihal}}</h1>
            {{-- <h3 align="center">PT Laksa Medika Internusa</h3> --}}
        </div>

        <footer>
            <h4>PT Laksa Medika Internusa</h4>
            <p>Jl. Amarta No.50 RT01, RW.01, Pelem, Baturetno, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198</p>
            <p>Telepon : 0274 443 6047 | Email : laksamedikainternusa@gmail.com | Website : www.laksamedical.com</p>
        </footer>
    </div>

    <div class="page_break">
        <header>
            <img src="{{ public_path('assets/img/logo_ptlmi.webp') }}" alt="Logo">
        </header>

        <p style="font-size: 14px; margin: 10px;">
            No. 0{{$penawarans->id}}/LMI-SLS/<?php
                $array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
                $bulan = $array_bulan[date('n')];
                echo  "$bulan";
            ?>/{{$penawarans->created_at->format('Y') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Yogyakarta, {{ \Carbon\Carbon::parse( $penawarans->created_at)->locale('id_ID')->isoFormat('dddd, D MMM YYYY') }}
        </p>

        <p style="font-size: 12px; margin: 10px;">
            Kepada Yth.
        </p>
        <b style="font-size: 14px; line-height: 20px; margin: 10px;">{{$penawarans->customer}}</b>
        <p style="font-size: 12px; margin: 10px;">{{$penawarans->address}}</p>
        <b style="font-size: 12px; margin: 10px;">Hal : {{$penawarans->perihal}}</b>

        <p style="font-size: 12px; line-height: 20px; margin: 10px;">Dengan Hormat,</p>
        <p style="font-size: 12px; margin: 10px;">
            Bersama ini kami <b>PT. Laksa Medika Internusa</b>, bermaksud mengajukan {{$penawarans->perihal}} di <b>{{$penawarans->customer}}</b>.<br>
            Daftar penawaran terlampir. <br>
            Dengan kondisi penawaran sebagai berikut :
        </p>

        <div class="conditions">
            @forelse($kondisis as $e=>$row)
                <p style="font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $e+1 }}. {{ $row->kondisi }}</p>
            @empty
                <p>Data Tidak Ditemukan</p>
            @endforelse
        </div>

        <h3 style="margin: 5px; font-size: 14px; text-align: center;">List Harga Produk</h3>
        <table class="product-table">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Qty</th>
                <th>Harga</th>
            </tr>
            @foreach ($hargapenawarans as $e=>$hargapenawaran)
            <tr>
                <td>{{ $e+1 }}</td>
                <td>{{ $hargapenawaran->product->name }}</td>
                <td>{{ $hargapenawaran->qty }}</td>
                <td style="text-align: center;">Rp {{ number_format($hargapenawaran->price) }}</td>
            </tr>
            @endforeach
        </table>

        <p style="font-size: 12px; margin: 10px;">
            Demikian penawaran harga kami, Apabila terdapat hal yang kurang jelas, Anda dapat menghubungi kami melalui nomor HP : {{$penawarans->no_hp}} atau ke No. Telephone (0274) 443 6047. Atas perhatian dan kerja samanya kami ucapkan terima kasih.
        </p>

        <div class="signature">
            {{-- <img src="assets/img/ttd.webp" alt="Tanda Tangan"> --}}
            <p style="font-size: 12px; margin: 10px;">Hormat Kami,</p>
            <br>
            <br>
            <br>
            <b>Yandi Okta Wirawan</b>
            <b>PT. Laksa Medika Internusa</b>
        </div>

        <footer>
            <b>Jl. Amarta No.50 RT01, RW.01, Pelem, Baturetno, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198</b><br>
            <b>Telepon : 0274 443 6047 | Email : laksamedikainternusa@gmail.com | Website : www.laksamedical.com</b>
        </footer>
    </div>
</body>
</html>