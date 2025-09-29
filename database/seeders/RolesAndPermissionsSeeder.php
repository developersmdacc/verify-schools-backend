<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'view schools',
            'view all learners',
            'view all teachers',
            'view all parents',
            'view single school',
            'view school learners', // View learners for a specific school (registered in this app)
            'view school teachers', // View teachers for a specific school (registered in this app)
            'view school parents',
            'view drivers',
            'view vendors',
            'view reports',
            'view stats',
            'view shop',
            'search schools',
            'search drivers',
            'search vendors',
            'submit verification request', // If verification is done on this solution (project)
            'verify schools', // If verification is done on this solution (project)
            'manage shop', // For Vendors to manage their shops
            'manage transportation listings',
            'manage users',
            'manage schools', // Admin, Gov Officials
            'manage single school',
            'manage vendors',
            'manage products',
            'manage data', // Imports of source data
            'manage wordpress', // takes you to -wp-admin on a website
            'access dashboard',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $verifier = Role::firstOrCreate(['name' => 'verifier']);
        $verifier->givePermissionTo(['verify schools', 'view schools', 'view reports']);

        $parent = Role::firstOrCreate(['name' => 'parent']);
        $parent->givePermissionTo(['view schools', 'search schools', 'view school teachers', 'submit verification request', 'access dashboard']);

        $learner = Role::firstOrCreate(['name' => 'learner']);
        $learner->givePermissionTo(['view schools', 'view school leaners', 'search schools', 'submit verification request', 'access dashboard']);

        $learner = Role::firstOrCreate(['name' => 'teacher']);
        $learner->givePermissionTo(['view schools', 'view school learners', 'access dashboard']);

        $learner = Role::firstOrCreate(['name' => 'school_admin']);
        $learner->givePermissionTo(['view single school', 'view school learners', 'view school teachers', 'view school parents', 'manage single school', 'access dashboard']);

        $vendor = Role::firstOrCreate(['name' => 'vendor']);
        $vendor->givePermissionTo(['manage products', 'view schools', 'search schools', 'access dashboard']);

        $driver = Role::firstOrCreate(['name' => 'driver']);
        $driver->givePermissionTo(['manage transportation listings', 'view schools','search schools', 'access dashboard']);

        $visitor = Role::firstOrCreate(['name' => 'visitor']);
        $visitor->givePermissionTo(['view schools']);


    }
}
