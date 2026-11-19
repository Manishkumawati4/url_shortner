<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'superadmin@example.com';

        // Check if SuperAdmin already exists
        $existingUser = DB::selectOne(
            'SELECT id FROM users WHERE email = ?',
            [$email]
        );

        if (!$existingUser) {

            $password = Hash::make('SuperAdmin@123');

            // Insert using raw SQL
            DB::statement(
                'INSERT INTO users
                (company_id, name, email, password, role, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
                [
                    null,
                    'Super Admin',
                    $email,
                    $password,
                    'SuperAdmin'
                ]
            );
        }
    }
}
