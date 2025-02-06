
@foreach ($sales as $sale)
<div class="item">
    <div class="detail">
        <div>
            <strong>{{ $sale->invoice_number }}</strong>
            <p>{{ $sale->customer->name ?? $sale->users->name }}</p>
            <p>{{ $sale->created_at->format('d-m-Y') }}</p>
            <p>Rp. {{ number_format($sale->total + $sale->tax) }}</p>
            <p>{{ $sale->marketing->name }}</p>
        </div>
    </div>
</div>
@endforeach
{{-- @foreach ($sales as $sale)
    
        <tr>
            <td>{{ $sale->invoice_number }}</td>
            <td>{{ $sale->customer->name ?? $sale->users->name }}</td>
            <td>@foreach ($sale->details as $detail)
                {{ $detail->product->name }}
                @endforeach
            </td>
            <td>@foreach ($sale->details as $detail)
                {{ $detail->quantity }}
                @endforeach
            </td>
            <td>@foreach ($sale->details as $detail)
                Rp {{ number_format($detail->total) }}
                @endforeach
            </td>
            <td>{{ $sale->created_at->format('d-m-Y') }}</td>
            <td>{{ $sale->marketing->name }}</td>
        </tr>

@endforeach

@if ($sales->isEmpty())
    <tr>
        <td colspan="7" class="text-center">Tidak ada data penjualan yang ditemukan.</td>
    </tr>
@endif --}}
