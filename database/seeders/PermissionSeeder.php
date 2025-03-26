<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions =[
            "role-list",
            "role-create",
            "role-edit",
            "role-delete",

            "incident-list",
            "incident-create",
            "incident-edit",
            "incident-delete"
        ];

        foreach ($permissions as $key => $permission) {
            Permission::create(['name' => $permission]);
        }

    }
}
