<?php
namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\BranchStock;
use App\Models\StockHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionDummySeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();

        foreach ($branches as $branch) {

            // Ambil kasir di cabang tertentu
            $kasir = User::where('branch_id', $branch->id)
                         ->whereHas('roles', fn($q) => $q->where('name', 'kasir'))
                         ->first();

            if (! $kasir) continue;

            // Buat 20 transaksi per cabang, tersebar 30 hari terakhir
            for ($i = 0; $i < 20; $i++) {

                $tanggal = now()->subDays(rand(0, 29))->format('Y-m-d');
                $total   = 0;
                $details = [];

                // Ambil 2-5 produk random per transaksi
                $selectedProducts = $products->random(rand(2, 5));

                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 5);

                    // Cek stok
                    $stock = BranchStock::where('branch_id', $branch->id)
                                        ->where('product_id', $product->id)
                                        ->first();

                    if (! $stock || $stock->stok < $qty) continue;

                    $subtotal  = $product->harga * $qty;
                    $total    += $subtotal;

                    $details[] = [
                        'product_id'   => $product->id,
                        'qty'          => $qty,
                        'harga_satuan' => $product->harga,
                        'subtotal'     => $subtotal,
                        'stok_record'  => $stock,
                    ];
                }

                if (empty($details)) continue;

                $transaction = Transaction::create([
                    'kode_transaksi' => Transaction::generateKode(),
                    'branch_id'      => $branch->id,
                    'kasir_id'       => $kasir->id,
                    'tanggal'        => $tanggal,
                    'total'          => $total,
                ]);

                foreach ($details as $detail) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id'     => $detail['product_id'],
                        'qty'            => $detail['qty'],
                        'harga_satuan'   => $detail['harga_satuan'],
                        'subtotal'       => $detail['subtotal'],
                    ]);

                    $detail['stok_record']->decrement('stok', $detail['qty']);

                    StockHistory::create([
                        'branch_id'      => $branch->id,
                        'product_id'     => $detail['product_id'],
                        'transaction_id' => $transaction->id,
                        'jenis'          => 'keluar',
                        'jumlah'         => $detail['qty'],
                        'keterangan'     => 'Penjualan ' . $transaction->kode_transaksi,
                        'created_by'     => $kasir->id,
                    ]);
                }
            }

            // beberapa barang masuk manual dari gudang
            $gudang = User::where('branch_id', $branch->id)
                          ->whereHas('roles', fn($q) => $q->where('name', 'gudang'))
                          ->first();

            if ($gudang) {
                foreach ($products->random(5) as $product) {
                    $jumlah = rand(20, 100);

                    $stock = BranchStock::where('branch_id', $branch->id)
                                        ->where('product_id', $product->id)
                                        ->first();

                    if ($stock) {
                        $stock->increment('stok', $jumlah);

                        StockHistory::create([
                            'branch_id'  => $branch->id,
                            'product_id' => $product->id,
                            'jenis'      => 'masuk',
                            'jumlah'     => $jumlah,
                            'keterangan' => 'Restock dari supplier',
                            'created_by' => $gudang->id,
                        ]);
                    }
                }
            }
        }
    }
}