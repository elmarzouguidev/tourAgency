<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Roles\RolesEnums;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (RolesEnums::cases() as $role) {
            Role::create(['name' => $role->value]);
        }
    }
}
