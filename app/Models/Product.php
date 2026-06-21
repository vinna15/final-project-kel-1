<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'harga',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
        ];
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function branchStocks()
    {
        return $this->hasMany(BranchStock::class);
    }

    public function stockAtBranch(int $branchId): int
    {
        return $this->branchStocks()
                    ->where('branch_id', $branchId)
                    ->value('stok') ?? 0;
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}