<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'add kost']);
        Permission::create(['name' => 'update kost']);
        Permission::create(['name' => 'delete kost']);
        Permission::create(['name' => 'view own kost']);
        Permission::create(['name' => 'view all kost']);
        Permission::create(['name' => 'view detail kost']);
        Permission::create(['name' => 'search kost']);
        Permission::create(['name' => 'ask room']);

        // create roles and assign created permissions

        Role::create(['name' => 'owner'])->givePermissionTo(['add kost', 'update kost', 'delete kost', 'view own kost']);

        Role::create(['name' => 'regular'])->givePermissionTo(['view all kost', 'view detail kost', 'search kost', 'ask room']);

        Role::create(['name' => 'premium'])->givePermissionTo(['view all kost', 'view detail kost', 'search kost', 'ask room']);
    }
}
