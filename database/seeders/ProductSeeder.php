<?php
namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['kode_barang' => 'PRD-001', 'nama_barang' => 'Indomie Goreng',       'kategori' => 'Makanan',  'harga' => 3500],
            ['kode_barang' => 'PRD-002', 'nama_barang' => 'Aqua 600ml',           'kategori' => 'Minuman',  'harga' => 4000],
            ['kode_barang' => 'PRD-003', 'nama_barang' => 'Teh Botol Sosro',      'kategori' => 'Minuman',  'harga' => 5000],
            ['kode_barang' => 'PRD-004', 'nama_barang' => 'Sabun Mandi Lifebuoy', 'kategori' => 'Toiletri', 'harga' => 8000],
            ['kode_barang' => 'PRD-005', 'nama_barang' => 'Shampoo Pantene',      'kategori' => 'Toiletri', 'harga' => 15000],
            ['kode_barang' => 'PRD-006', 'nama_barang' => 'Beras 5kg',            'kategori' => 'Sembako',  'harga' => 65000],
            ['kode_barang' => 'PRD-007', 'nama_barang' => 'Minyak Goreng 1L',     'kategori' => 'Sembako',  'harga' => 18000],
            ['kode_barang' => 'PRD-008', 'nama_barang' => 'Gula Pasir 1kg',       'kategori' => 'Sembako',  'harga' => 14000],
            ['kode_barang' => 'PRD-009', 'nama_barang' => 'Kecap Manis Bango',    'kategori' => 'Bumbu',    'harga' => 12000],
            ['kode_barang' => 'PRD-010', 'nama_barang' => 'Chitato 68gr',         'kategori' => 'Snack',    'harga' => 10000],
        ];

        $branches = Branch::all();

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['kode_barang' => $productData['kode_barang']],
                $productData
            );

            foreach ($branches as $branch) {
                BranchStock::firstOrCreate(
                    ['branch_id' => $branch->id, 'product_id' => $product->id],
                    ['stok' => rand(50, 200)]
                );
            }
        }
    }
}