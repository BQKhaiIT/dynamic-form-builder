<?php

namespace Database\Seeders;

use App\Services\AdminUserService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminUserService = app(AdminUserService::class);

        $adminUserService->createOrUpdateAdmin(
            name: 'System Admin',
            email: 'admin@example.com',
            password: 'password'
        );

        $adminUserService->createOrUpdateEmployee(
            name: 'Demo Employee',
            email: 'employee@example.com',
            password: 'password'
        );
    }
}
