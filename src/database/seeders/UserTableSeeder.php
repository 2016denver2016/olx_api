<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $rolesList = [];
        $permissionsList = [];
        foreach (User::getRoles() as $role) {
            $rolesList[$role] = Role::create(['name' => $role]);
        }

        foreach (User::getPermissions() as $permission) {
            $permissionsList[$permission] = Permission::firstOrCreate(['name' => $permission]);
        }

        /** @var \App\Models\Role[] $rolesList */
        foreach ($rolesList as $role) {
            $rolePermissions = User::getRolesPermissions($role->name);
            foreach ($rolePermissions as $rolePermission) {
                $role->givePermissionTo($permissionsList[$rolePermission]);
            }
        }

        $roles = User::getRoles();

        /** @var User $user */
        $user = User::factory()->create([
            'email'      => 'admin@gmail.com',
            'password'   => Hash::make("admin12345"),
            'status'     => User::STATUS_ACTIVE,
        ]);
        $user->assignRole(User::ROLE_ADMIN);
        $user->givePermissionTo(User::getRolesPermissions(User::ROLE_ADMIN));

        User::factory()->count(50)->create()->each(function ($user) use ($roles) {
            /** @var User $user */
            $role = array_rand($roles);
            $user->assignRole($roles[$role]);
            $user->givePermissionTo(User::getRolesPermissions($roles[$role]));
        });
    }
}
