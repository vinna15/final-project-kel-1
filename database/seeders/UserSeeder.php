<?php
namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $bandung  = Branch::where('kode', 'CBG-001')->first();
        $jakarta  = Branch::where('kode', 'JKT-001')->first();
        $surabaya = Branch::where('kode', 'SBY-001')->first();
        $yogya    = Branch::where('kode', 'YGY-001')->first();
        $medan    = Branch::where('kode', 'MDN-001')->first();

        $users = [
            // Owner — tidak terikat cabang
            ['name' => 'Pak Jayusman',       'email' => 'owner@minimarket.com',              'password' => Hash::make('password'), 'branch_id' => null,          'role' => 'owner'],
            // Manager
            ['name' => 'Manager Bandung',    'email' => 'manager.bandung@minimarket.com',    'password' => Hash::make('password'), 'branch_id' => $bandung->id,  'role' => 'manager'],
            ['name' => 'Manager Jakarta',    'email' => 'manager.jakarta@minimarket.com',    'password' => Hash::make('password'), 'branch_id' => $jakarta->id,  'role' => 'manager'],
            // Supervisor
            ['name' => 'Supervisor Bandung', 'email' => 'supervisor.bandung@minimarket.com', 'password' => Hash::make('password'), 'branch_id' => $bandung->id,  'role' => 'supervisor'],
            // Kasir
            ['name' => 'Kasir Bandung 1',   'email' => 'kasir1.bandung@minimarket.com',     'password' => Hash::make('password'), 'branch_id' => $bandung->id,  'role' => 'kasir'],
            ['name' => 'Kasir Jakarta 1',   'email' => 'kasir1.jakarta@minimarket.com',     'password' => Hash::make('password'), 'branch_id' => $jakarta->id,  'role' => 'kasir'],
            // Gudang
            ['name' => 'Gudang Bandung',    'email' => 'gudang.bandung@minimarket.com',     'password' => Hash::make('password'), 'branch_id' => $bandung->id,  'role' => 'gudang'],
            ['name' => 'Gudang Surabaya',   'email' => 'gudang.surabaya@minimarket.com',    'password' => Hash::make('password'), 'branch_id' => $surabaya->id, 'role' => 'gudang'],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);
            $user = User::firstOrCreate(['email' => $data['email']], $data);
            $user->assignRole($role);
        }
    }
}