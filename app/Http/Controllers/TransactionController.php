<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\BranchStock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Transaction::with(['branch', 'kasir'])->latest();

        if (! $user->hasRole('owner')) {
            $query->where('branch_id', $user->branch_id);
        }

        $transactions = $query->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $user     = Auth::user();
        $products = Product::all();
        $stocks   = BranchStock::where('branch_id', $user->branch_id)->pluck('stok', 'product_id');

        return view('transactions.create', compact('products', 'stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            $total       = 0;
            $detailsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $stock   = BranchStock::where('branch_id', $user->branch_id)
                                       ->where('product_id', $product->id)
                                       ->lockForUpdate()
                                       ->first();

                if (! $stock || $stock->stok < $item['qty']) {
                    throw new \Exception("Stok {$product->nama_barang} tidak mencukupi.");
                }

                $subtotal      = $product->harga * $item['qty'];
                $total        += $subtotal;
                $detailsData[] = [
                    'product_id'   => $product->id,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $product->harga,
                    'subtotal'     => $subtotal,
                ];

                $stock->decrement('stok', $item['qty']);
            }

            $transaction = Transaction::create([
                'kode_transaksi' => Transaction::generateKode(),
                'branch_id'      => $user->branch_id,
                'kasir_id'       => $user->id,
                'tanggal'        => today(),
                'total'          => $total,
            ]);

            foreach ($detailsData as &$detail) {
                $detail['transaction_id'] = $transaction->id;
                $detail['created_at']     = now();
                $detail['updated_at']     = now();
            }

            TransactionDetail::insert($detailsData);

            foreach ($detailsData as $detail) {
                StockHistory::create([
                    'branch_id'      => $user->branch_id,
                    'product_id'     => $detail['product_id'],
                    'transaction_id' => $transaction->id,
                    'jenis'          => 'keluar',
                    'jumlah'         => $detail['qty'],
                    'keterangan'     => 'Penjualan ' . $transaction->kode_transaksi,
                    'created_by'     => $user->id,
                ]);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['branch', 'kasir', 'details.product']);
        return view('transactions.show', compact('transaction'));
    }
}