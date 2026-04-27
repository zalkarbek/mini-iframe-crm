<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view customers']);
        Permission::create(['name' => 'create customers']);
        Permission::create(['name' => 'edit customers']);
        Permission::create(['name' => 'delete customers']);

        Permission::create(['name' => 'view tickets']);
        Permission::create(['name' => 'create tickets']);
        Permission::create(['name' => 'edit tickets']);
        Permission::create(['name' => 'delete tickets']);
        Permission::create(['name' => 'edit tickets status']);
        Permission::create(['name' => 'modify-view tickets statistic']);

        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);

        $admin->givePermissionTo(Permission::all());

        $manager->givePermissionTo([
            'view customers',
            'view tickets',
            'edit tickets status',
            'modify-view tickets statistic',
        ]);
    }
}
