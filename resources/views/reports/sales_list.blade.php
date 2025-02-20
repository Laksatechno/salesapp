@forelse ($sales as $sale)
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
@empty 
<div class="item">
    <div class="detail">
        <div>
            <p style="color: rgb(255, 0, 0)">Tidak ada data penjualan</p>
        </div>
    </div>
</div>
@endforelse