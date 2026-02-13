<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage clients']);
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'manage customers']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage sales']);
        Permission::create(['name' => 'view reports']);

        $admin = Role::create(['name' => 'Admin da Loja']);
        $admin->givePermissionTo(Permission::all());

        $vendedor = Role::create(['name' => 'Vendedor']);
        $vendedor->givePermissionTo(['manage clients', 'manage sales']);
    }
}
