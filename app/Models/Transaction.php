<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'branch_id',
        'kasir_id',
        'tanggal',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'total'   => 'decimal:2',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public static function generateKode(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        $last   = self::where('kode_transaksi', 'like', $prefix . '%')
                      ->latest('id')
                      ->value('kode_transaksi');
        $number = $last ? ((int) substr($last, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}