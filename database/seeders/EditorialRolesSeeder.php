<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EditorialRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'submit for review',
            'review posts',
            'approve posts',
            'reject posts',
            'assign reviewers',
            'view all posts',
            'manage templates',
            'view analytics',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Writer: Can create and submit for review
        $writer = Role::firstOrCreate(['name' => 'Writer']);
        $writer->givePermissionTo([
            'create posts',
            'edit posts',
            'submit for review',
        ]);

        // Reviewer: Can review and request changes
        $reviewer = Role::firstOrCreate(['name' => 'Reviewer']);
        $reviewer->givePermissionTo([
            'create posts',
            'edit posts',
            'submit for review',
            'review posts',
            'view all posts',
        ]);

        // Editor: Can approve, reject, and publish
        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $editor->givePermissionTo([
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'submit for review',
            'review posts',
            'approve posts',
            'reject posts',
            'assign reviewers',
            'view all posts',
            'manage templates',
            'view analytics',
        ]);

        // Admin: All permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $this->command->info('Editorial roles and permissions created successfully!');
        $this->command->info('Roles: Writer, Reviewer, Editor, Admin');
    }
}
