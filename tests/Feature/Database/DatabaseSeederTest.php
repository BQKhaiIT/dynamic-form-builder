<?php

namespace Tests\Feature\Database;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_demo_admin_and_employee_accounts(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => UserRole::Admin->value,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'employee@example.com',
            'role' => UserRole::Employee->value,
        ]);
    }
}
