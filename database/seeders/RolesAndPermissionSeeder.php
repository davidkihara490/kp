<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createCRUDPermissions('roles');
        $this->createCRUDPermissions('parcel');
        $this->createCRUDPermissions('fleet');
        $this->createCRUDPermissions('driver');
        $this->createCRUDPermissions('parcel-handling-assistant');
        $this->createCRUDPermissions('pickup-and-dropoff-point');

        // =========================================================
        // Finally give the super admin all the above permissions
        // =========================================================

        $superAdmin = $this->createRole('super-admin');
        Permission::all()->each(fn(Permission $permission): Permission => $permission->assignRole($superAdmin));

        $partnerAdmin = $this->createRole('parter-admin');
        Permission::all()->each(fn(Permission $permission): Permission => $permission->assignRole($partnerAdmin));
    }

    public function createRole(string $name): Role
    {
        return Role::query()->updateOrCreate(['name' => $name]);
    }

    public function createPermission(string $name): Permission
    {
        return Permission::query()->updateOrCreate(['name' => $name]);
    }

    public function createCRUDPermissions(string $name): void
    {
        $this->createPermission("$name.create");
        $this->createPermission("$name.view");
        $this->createPermission("$name.update");
        $this->createPermission("$name.delete");
        $this->createPermission("reports");
    }
}
