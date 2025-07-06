<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очистим кеш
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Создаём разрешения
        Permission::firstOrCreate(['name' => 'manage_posts']);
        Permission::firstOrCreate(['name' => 'publish_posts']);
        Permission::firstOrCreate(['name' => 'edit_posts']);
        Permission::firstOrCreate(['name' => 'delete_posts']);
        Permission::firstOrCreate(['name' => 'manage_categories']);
        Permission::firstOrCreate(['name' => 'manage_users']);

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $editor->syncPermissions(['publish_posts', 'edit_posts']);

        $author = Role::firstOrCreate(['name' => 'Author']);
        $author->syncPermissions(['edit_posts']);

        $reader = Role::firstOrCreate(['name' => 'Reader']);
    }
}
