<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'branch.view', 'branch.create', 'branch.edit', 'branch.delete',
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'product.view', 'product.create', 'product.edit', 'product.delete',
            'stock.view', 'stock.update',
            'transaction.view', 'transaction.create', 'transaction.view-all',
            'stock-in.create', 'stock-out.create', 'stock-history.view',
            'report.view', 'report.print', 'report.export',
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // OWNER
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->syncPermissions($permissions);

        // MANAGER
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'dashboard.view', 'product.view', 'stock.view',
            'transaction.view', 'stock-history.view',
            'report.view', 'report.print', 'report.export',
        ]);

        // SUPERVISOR
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $supervisor->syncPermissions([
            'dashboard.view', 'transaction.view', 'stock.view',
            'stock-in.create', 'stock-out.create', 'stock-history.view', 'report.print',
        ]);

        // KASIR
        $kasir = Role::firstOrCreate(['name' => 'kasir']);
        $kasir->syncPermissions([
            'dashboard.view', 'transaction.create', 'transaction.view',
        ]);

        // GUDANG
        $gudang = Role::firstOrCreate(['name' => 'gudang']);
        $gudang->syncPermissions([
            'dashboard.view', 'stock.view', 'stock.update',
            'stock-in.create', 'stock-out.create', 'stock-history.view',
        ]);
    }
}