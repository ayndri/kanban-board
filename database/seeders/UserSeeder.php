<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $divisions = Division::all();
        $manajemenDiv = $divisions->where('name', 'Manajemen')->first();

        User::factory()
            ->has(Employee::factory()->state([
                'job_title' => 'System Administrator',
                'division_id' => $manajemenDiv->id
            ]))
            ->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]);

        User::factory()
            ->has(Employee::factory()->state([
                'job_title' => 'Project Manager',
                'division_id' => $manajemenDiv->id
            ]))
            ->create([
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role_id' => $managerRole->id,
            ]);

        User::factory(20)
            ->has(Employee::factory()->state(function (array $attributes) use ($divisions) {
                return ['division_id' => $divisions->random()->id];
            }))
            ->create([
                'role_id' => $employeeRole->id
            ]);
    }
}
