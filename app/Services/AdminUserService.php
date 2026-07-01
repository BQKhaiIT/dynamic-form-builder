<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserService
{
    public function createOrUpdateAdmin(string $name, string $email, string $password): User
    {
        $user = User::query()->firstOrNew([
            'email' => $email,
        ]);

        $user->fill([
            'name' => $name,
            'password' => Hash::make($password),
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $user->save();

        return $user;
    }

    public function createOrUpdateEmployee(string $name, string $email, string $password): User
    {
        $user = User::query()->firstOrNew([
            'email' => $email,
        ]);

        $user->fill([
            'name' => $name,
            'password' => Hash::make($password),
            'role' => UserRole::Employee,
            'email_verified_at' => now(),
        ]);

        $user->save();

        return $user;
    }
}
