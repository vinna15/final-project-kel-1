<?php
namespace App\Http\Controllers;

use App\Models\BranchStock;
use App\Models\StockHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = BranchStock::with(['product', 'branch']);

        if ($user->hasRole('owner')) {
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }
        } else {
            $query->where('branch_id', $user->branch_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        $stocks   = $query->paginate(10)->withQueryString();
        $branches = $user->hasRole('owner') ? \App\Models\Branch::all() : collect();

        return view('stocks.index', compact('stocks', 'branches'));
    }

    public function createIn()
    {
        $products = Product::all();
        return view('stocks.in', compact('products'));
    }

    public function storeIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            $stock = BranchStock::firstOrCreate(
                ['branch_id' => $user->branch_id, 'product_id' => $request->product_id],
                ['stok' => 0]
            );
            $stock->increment('stok', $request->jumlah);

            StockHistory::create([
                'branch_id'  => $user->branch_id,
                'product_id' => $request->product_id,
                'jenis'      => 'masuk',
                'jumlah'     => $request->jumlah,
                'keterangan' => $request->keterangan,
                'created_by' => $user->id,
            ]);
        });

        return redirect()->route('stocks.index')->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function createOut()
    {
        $products = Product::all();
        return view('stocks.out', compact('products'));
    }

    public function storeOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user  = Auth::user();
        $stock = BranchStock::where('branch_id', $user->branch_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (! $stock || $stock->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        DB::transaction(function () use ($request, $user, $stock) {
            $stock->decrement('stok', $request->jumlah);

            StockHistory::create([
                'branch_id'  => $user->branch_id,
                'product_id' => $request->product_id,
                'jenis'      => 'keluar',
                'jumlah'     => $request->jumlah,
                'keterangan' => $request->keterangan,
                'created_by' => $user->id,
            ]);
        });

        return redirect()->route('stocks.index')->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function history(Request $request)
    {
        $user  = Auth::user();
        $query = StockHistory::with(['product', 'branch', 'creator'])
                              ->whereNull('transaction_id')
                              ->latest();

        if ($user->hasRole('owner')) {
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }
        } else {
            $query->where('branch_id', $user->branch_id);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $histories = $query->paginate(15)->withQueryString();
        $branches  = $user->hasRole('owner') ? \App\Models\Branch::all() : collect();

        return view('stocks.history', compact('histories', 'branches'));
    }
}