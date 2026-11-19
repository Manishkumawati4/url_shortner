<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestShortUrlsSeeder extends Seeder
{
    public function run(): void
    {
        $emails = [
            'admin@example.com',
            'member@example.com',
            'sales@example.com',
            'manager@example.com',
            'memberb@example.com',
        ];

        foreach ($emails as $email) {
            $user = DB::table('users')
                ->where('email', $email)
                ->first();

            if (!$user) {
                throw new \Exception("User not found: " . $email);
            }

            $code = match ($email) {
                'admin@example.com' => 'admin01',
                'member@example.com' => 'member1',
                'sales@example.com' => 'sales01',
                'manager@example.com' => 'manager1',
                'memberb@example.com' => 'memberB1',
            };

            DB::table('short_urls')->updateOrInsert(
                ['short_code' => $code],
                [
                    'company_id' => $user->company_id,
                    'user_id' => $user->id,
                    'original_url' => 'https://www.google.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
