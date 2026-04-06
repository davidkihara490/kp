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

        $this->createCRUDPermissions('partner');
        $this->createCRUDPermissions('user');
        $this->createCRUDPermissions('payment');
        $this->createCRUDPermissions('blog-posts');
        $this->createCRUDPermissions('blog-categories');
        $this->createCRUDPermissions('blog-tags');
        $this->createCRUDPermissions('items');
        $this->createCRUDPermissions('weight-ranges');
        $this->createCRUDPermissions('pricing');
        $this->createCRUDPermissions('payment-structure');
        $this->createCRUDPermissions('towns');
        $this->createCRUDPermissions('zones');
        $this->createCRUDPermissions('faqs');

        // =========================================================
        // Finally give the super admin all the above permissions
        // =========================================================

        $superAdmin = $this->createRole('super-admin');
        Permission::all()->each(fn(Permission $permission): Permission => $permission->assignRole($superAdmin));

        $partnerAdmin = $this->createRole('partner-admin');
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
