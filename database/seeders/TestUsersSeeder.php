<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Company A
        $companyA = DB::table('companies')
            ->where('slug', 'company-a')
            ->first();

        $companyAId = $companyA
            ? $companyA->id
            : DB::table('companies')->insertGetId([
                'name' => 'Company A',
                'slug' => 'company-a',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        // Company B
        $companyB = DB::table('companies')
            ->where('slug', 'company-b')
            ->first();

        $companyBId = $companyB
            ? $companyB->id
            : DB::table('companies')->insertGetId([
                'name' => 'Company B',
                'slug' => 'company-b',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $users = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'role' => 'Admin',
                'company_id' => $companyAId,
            ],
            [
                'name' => 'Test Member',
                'email' => 'member@example.com',
                'role' => 'Member',
                'company_id' => $companyAId,
            ],
            [
                'name' => 'Test Sales',
                'email' => 'sales@example.com',
                'role' => 'Sales',
                'company_id' => $companyAId,
            ],
            [
                'name' => 'Test Manager',
                'email' => 'manager@example.com',
                'role' => 'Manager',
                'company_id' => $companyAId,
            ],
            [
                'name' => 'Test Member B',
                'email' => 'memberb@example.com',
                'role' => 'Member',
                'company_id' => $companyBId,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'company_id' => $user['company_id'],
                    'password' => Hash::make('Password@123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
