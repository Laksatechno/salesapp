<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data sales dengan filter
        $sales = Sale::with('details.product', 'customer', 'marketing')->orderBy('created_at', 'desc');
    
        // Filter berdasarkan pencarian (invoice number atau nama customer)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $sales->where(function ($query) use ($search) {
                $query->where('invoice_number', 'like', '%' . $search . '%')
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('marketing', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
            });
        }
    
        // Filter berdasarkan rentang waktu
        if ($request->has('date_range')) {
            $dateRange = $request->date_range;
            if ($dateRange == 'today') {
                $sales->whereDate('created_at', today());
            } elseif ($dateRange == 'last_7_days') {
                $sales->where('created_at', '>=', now()->subDays(7));
            } elseif ($dateRange == 'this_month') {
                $sales->whereMonth('created_at', now()->month);
            } elseif ($dateRange == 'custom_range' && $request->has('start_date') && $request->has('end_date')) {
                $sales->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
        }
    
        // Filter berdasarkan jenis transaksi
        if ($request->has('transaction_type')) {
            $transactionType = $request->transaction_type;
            if ($transactionType == 'customer') {
                $sales->whereNotNull('customer_id');
            } elseif ($transactionType == 'user') {
                $sales->whereNotNull('user_id');
            }
        }
    
        
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin' || Auth::user()->role === 'keuangan') {
            if ($request->has('marketing_id')) {
                $sales->where('user_id', $request->marketing_id);
            }
        } elseif (Auth::user()->role === 'marketing') {
            $sales->where('user_id', Auth::user()->id);
        }
        // if ($request->has('user_id')) {
        //     $sales->where('user_id', $request->marketing_id);
        // }
    

    
        // Ambil data sales yang sudah difilter
        $sales = $sales->get();
    
        // Jika permintaan AJAX, kembalikan partial view
        if ($request->ajax()) {
            return view('reports.sales_list', compact('sales'))->render();
        }
    
        // Ambil data tambahan untuk dropdown filter
        $products = Product::all();
        $customers = Customer::all();
        $marketings = User::where('role', '!=', 'customer')
             ->whereIn('role', ['marketing', 'superadmin', 'admin', 'keuangan'])
             ->get();
    
        return view('reports.index', compact('sales', 'products', 'customers', 'marketings'));
    }

    public function print(Request $request)
{
    
    // Ambil data sales dengan filter yang sama seperti di index
    $sales = Sale::with('details.product', 'customer', 'marketing');

    // Filter berdasarkan pencarian
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $sales->where(function ($query) use ($search) {
            $query->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('marketing', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        });
    }

    // Filter berdasarkan rentang waktu
    if ($request->has('date_range')) {
        $dateRange = $request->date_range;
        if ($dateRange == 'today') {
            $sales->whereDate('created_at', today());
        } elseif ($dateRange == 'last_7_days') {
            $sales->where('created_at', '>=', now()->subDays(7));
        } elseif ($dateRange == 'this_month') {
            $sales->whereMonth('created_at', now()->month);
        } elseif ($dateRange == 'custom_range' && $request->has('start_date') && $request->has('end_date')) {
            $sales->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
    }

    // Filter berdasarkan jenis transaksi
    if ($request->has('transaction_type')) {
        $transactionType = $request->transaction_type;
        if ($transactionType == 'customer') {
            $sales->whereNotNull('customer_id');
        } elseif ($transactionType == 'user') {
            $sales->whereNotNull('user_id');
        }
    }

    // Filter berdasarkan marketing
    if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin' || Auth::user()->role === 'keuangan') {
        if ($request->has('marketing_id') && $request->marketing_id != '') {
            $sales->where('user_id', $request->marketing_id);
        }
        // Jika marketing_id kosong, tidak perlu menambahkan where clause, sehingga menampilkan semua data
    } elseif (Auth::user()->role === 'marketing') {
        $sales->where('user_id', Auth::user()->id);
    }

    // Ambil data sales yang sudah difilter
    $sales = $sales->get();

    // Load view untuk PDF
    $pdf = Pdf::loadView('reports.print', compact('sales'));

    // Download atau tampilkan PDF
    // return $pdf->download('laporan_penjualan.pdf');
    return $pdf->stream('laporan_penjualan.pdf');
}

    public function show($product_id, Request $request)
    {
        // Ambil data produk
        $product = Product::findOrFail($product_id);

        // Query dasar untuk penjualan
        $salesQuery = Sale::whereHas('details', function ($query) use ($product_id) {
            $query->where('product_id', $product_id);
        })->with(['details.product', 'marketing', 'customer', 'users']);

        // Filter berdasarkan marketing (bisa lebih dari satu) dan jika kosong menampilkan semua marketing_id
        if ($request->has('marketing_id')) {
            $salesQuery->whereIn('marketing_id', $request->marketing_id);
        } elseif ($request-> has('marketing_id') && $request->marketing_id == '') {
            $salesQuery->whereNotNull('marketing_id');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $salesQuery->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // Filter untuk bulan ini
        if ($request->has('this_month') && $request->this_month == '1') {
            $salesQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $salesQuery->where(function ($query) use ($search) {
                $query->where('invoice_number', 'like', "%$search%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('users', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        // Ambil data penjualan
        $sales = $salesQuery->get();

        // Jika request via AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json([
                'sales' => view('reports.partials.salesbyproduct', compact('sales', 'product'))->render()
            ]);
        }

        // Ambil daftar marketing untuk checkbox
        $marketings = User::where('role', '!=', 'customer')
        ->whereIn('role', ['marketing', 'superadmin', 'admin', 'keuangan'])
        ->get();

        return view('reports.show', compact('sales', 'product', 'marketings'));
    }

public function pdfreportbyproduct(Request $request)
{
    // Ambil ID produk dari request
    $product_id = $request->input('product_id');

    // Cek apakah produk ditemukan
    $product = Product::findOrFail($product_id);

    // Query penjualan berdasarkan produk
    $salesQuery = Sale::whereHas('details', function ($query) use ($product_id) {
        $query->where('product_id', $product_id);
    })->with(['details.product', 'marketing', 'customer', 'users']);

    // Filter berdasarkan marketing (jika ada)
    if ($request->has('marketing_id')) {
        $salesQuery->whereIn('marketing_id', (array) $request->marketing_id);
    }

    // Filter berdasarkan rentang tanggal
    if ($request->has(['start_date', 'end_date'])) {
        $salesQuery->whereBetween('created_at', [
            $request->start_date,
            $request->end_date
        ]);
    }

    // Filter untuk bulan ini
    if ($request->has('this_month') && $request->this_month == '1') {
        $salesQuery->whereMonth('created_at', now()->month)
                   ->whereYear('created_at', now()->year);
    }

    // Filter berdasarkan pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $salesQuery->where(function ($query) use ($search) {
            $query->where('invoice_number', 'like', "%$search%")
                  ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhereHas('users', fn ($q) => $q->where('name', 'like', "%$search%"));
        });
    }

    // Ambil data
    $sales = $salesQuery->get();

    // Load view untuk PDF
    $pdf = PDF::loadView('reports.cetakpdf.reportbyproduct', compact('sales', 'product'));

    return $pdf->stream('laporan_penjualan_product.pdf');
}




public function reportbycustomer(Request $request, $customer_id)
{
    $query = Sale::where('customer_id', $customer_id)->with('details.product', 'customer', 'user');

    // Filter berdasarkan pencarian produk atau nomor invoice
    if ($request->has('search') && !empty($request->search)) {
        $query->where(function ($q) use ($request) {
            $q->where('invoice_number', 'like', '%' . $request->search . '%')
              ->orWhereHas('details.product', function ($q) use ($request) {
                  $q->where('name', 'like', '%' . $request->search . '%');
              });
        });
    }


    // Filter berdasarkan rentang tanggal dari daterangepicker
    if ($request->has('daterange') && !empty($request->daterange)) {
        $dates = explode(' - ', $request->daterange);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));

        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $sales = $query->get();
    $totaljual = $query->sum(DB::raw('total'));
    
    return view('reports.customer', compact('sales', 'totaljual'));
}

public function printReport($customer_id, Request $request)
{
    $query = Sale::where('customer_id', $customer_id)->with('details.product', 'customer', 'user');

    // Filter berdasarkan pencarian produk atau nomor invoice
    if ($request->has('search') && !empty($request->search)) {
        $query->where(function ($q) use ($request) {
            $q->where('invoice_number', 'like', '%' . $request->search . '%')
              ->orWhereHas('details.product', function ($q) use ($request) {
                  $q->where('name', 'like', '%' . $request->search . '%');
              });
        });
    }


    // Filter berdasarkan rentang tanggal dari daterangepicker
    if ($request->has('daterange') && !empty($request->daterange)) {
        $dates = explode(' - ', $request->daterange);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));

        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $sales = $query->get();

    // Hitung total penjualan
    $totaljual = $sales->sum(function ($sale) {
        return $sale->total + $sale->tax;
    });

    return view('reports.cetakpdf.reportbycustomer', compact('sales', 'totaljual'));
}

    

    public function generate(Request $request)
    {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Ambil data berdasarkan rentang tanggal
        $sales = Sale::whereBetween('created_at', [$request->start_date, $request->end_date])
            ->with('details.product') // Pastikan relasi sudah diatur di model Sale
            ->get();

        // Tampilkan data ke view
        return view('reports.result', [
            'sales' => $sales,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    }
}
