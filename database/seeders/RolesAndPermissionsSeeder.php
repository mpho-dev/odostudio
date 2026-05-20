<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = ['admin', 'manager', 'crew'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        if (isset($this->command)) {
            $this->command->info('Roles ['.implode(', ', $roles).'] seeded successfully.');
        }
    }
}
