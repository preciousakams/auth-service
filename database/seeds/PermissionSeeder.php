<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::updateOrcreate(['name' => 'register administrators', 'guard_name' => 'api_admin']);
        Permission::updateOrcreate(['name' => 'export administrators', 'guard_name' => 'api_admin']);
        Permission::updateOrcreate(['name' => 'export users', 'guard_name' => 'api_admin']);

        $developer = Role::updateOrcreate(['name' => 'Developer', 'guard_name' => 'api_admin']);
        if (!$developer->hasPermissionTo('register administrators')) {
            $developer->givePermissionTo('register administrators');
        }
        if (!$developer->hasPermissionTo('export administrators')) {
            $developer->givePermissionTo('export administrators');
        }
        if (!$developer->hasPermissionTo('export users')) {
            $developer->givePermissionTo('export users');
        }

        $landlord = Role::updateOrcreate(['name' => 'Landlord', 'guard_name' => 'api_user']);
        $agent = Role::updateOrcreate(['name' => 'Agent', 'guard_name' => 'api_user']);
        $tenant = Role::updateOrcreate(['name' => 'Tenant', 'guard_name' => 'api_user']);
        $superAdmin = Role::updateOrCreate(['name' => 'Super Admin', 'guard_name' => 'api_user']);

         // create user permissions
        Permission::updateOrcreate(['name' => 'users make administrator', 'guard_name' => 'api_user']);
        Permission::updateOrcreate(['name' => 'users fetch', 'guard_name' => 'api_user']);
        Permission::updateOrcreate(['name' => 'permission create', 'guard_name' => 'api_user']);
        Permission::updateOrcreate(['name' => 'users details', 'guard_name' => 'api_user']);
        Permission::updateOrcreate(['name' => 'users export', 'guard_name' => 'api_user']);
        Permission::updateOrcreate(['name' => 'users revoke administrator', 'guard_name' => 'api_user']);
        Permission::updateOrcreate(['name' => 'permission fetch', 'guard_name' => 'api_user']);

        $superAdmin->givePermissionTo([
            'users make administrator',
            'users fetch',
            'permission create',
            'users details',
            'users export',
            'users revoke administrator',
            'permission fetch',
        ]);
    }
    
}
