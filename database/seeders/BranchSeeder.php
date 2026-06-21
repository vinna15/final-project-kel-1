<?php
namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['kode' => 'CBG-001', 'nama_cabang' => 'Minimarket Cabang Bandung',   'alamat' => 'Jl. Sudirman No. 10',      'kota' => 'Bandung'],
            ['kode' => 'JKT-001', 'nama_cabang' => 'Minimarket Cabang Jakarta',   'alamat' => 'Jl. Thamrin No. 5',        'kota' => 'Jakarta'],
            ['kode' => 'SBY-001', 'nama_cabang' => 'Minimarket Cabang Surabaya',  'alamat' => 'Jl. Basuki Rahmat No. 20', 'kota' => 'Surabaya'],
            ['kode' => 'YGY-001', 'nama_cabang' => 'Minimarket Cabang Yogyakarta','alamat' => 'Jl. Malioboro No. 1',      'kota' => 'Yogyakarta'],
            ['kode' => 'MDN-001', 'nama_cabang' => 'Minimarket Cabang Medan',     'alamat' => 'Jl. Gatot Subroto No. 8',  'kota' => 'Medan'],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(['kode' => $branch['kode']], $branch);
        }
    }
}