<?php

namespace Database\Seeders;

use App\Models\Gift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1'),
                'remember_token' => null,
                'first_name' => 'Nguyễn Tất',
                'last_name' => 'Tiến',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);

        // Reset cached roles and permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'Manage All']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo('Manage All');

        $user = User::find(1);
        $user->assignRole('super-admin');
    }
}
