<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class ProductionAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = filter_var(env('ADMIN_EMAIL'), FILTER_VALIDATE_EMAIL);
        $adminPassword = env('ADMIN_PASSWORD');
        $adminName = env('ADMIN_NAME', 'Administrator');

        if ($adminEmail === false || ! is_string($adminPassword) || strlen($adminPassword) < 12) {
            throw new InvalidArgumentException('Set a valid ADMIN_EMAIL and an ADMIN_PASSWORD of at least 12 characters.');
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            ['name' => $adminName, 'password' => $adminPassword],
        );

        User::query()
            ->where('email', 'test@example.com')
            ->where('email', '!=', $adminEmail)
            ->delete();
    }
}
