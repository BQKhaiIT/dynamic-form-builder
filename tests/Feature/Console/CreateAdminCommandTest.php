<?php

namespace Tests\Feature\Console;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_admin_command_creates_an_admin_account(): void
    {
        $this->artisan('app:create-admin', [
            'email' => 'owner@example.com',
            '--name' => 'Owner',
            '--password' => 'secret123',
        ])
            ->expectsOutput('Admin account is ready.')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => 'owner@example.com',
            'role' => UserRole::Admin->value,
        ]);

        $user = User::query()->where('email', 'owner@example.com')->firstOrFail();

        $this->assertTrue($user->isAdmin());
    }
}
