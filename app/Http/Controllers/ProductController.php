<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Branch;
use App\Models\BranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:20|unique:products,kode_barang',
            'nama_barang' => 'required|string|max:100',
            'kategori'    => 'required|string|max:50',
            'harga'       => 'required|numeric|min:0',
        ]);

        $product  = Product::create($validated);
        $branches = Branch::all();

        foreach ($branches as $branch) {
            BranchStock::create([
                'branch_id'  => $branch->id,
                'product_id' => $product->id,
                'stok'       => 0,
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $user = Auth::user();

        if ($user->hasRole('owner')) {
            $stocks = BranchStock::where('product_id', $product->id)->with('branch')->get();
        } else {
            $stocks = BranchStock::where('product_id', $product->id)->where('branch_id', $user->branch_id)->with('branch')->get();
        }

        return view('products.show', compact('product', 'stocks'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:20|unique:products,kode_barang,' . $product->id,
            'nama_barang' => 'required|string|max:100',
            'kategori'    => 'required|string|max:50',
            'harga'       => 'required|numeric|min:0',
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus.');
    }
}