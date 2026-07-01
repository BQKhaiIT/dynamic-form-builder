<?php

use App\Services\AdminUserService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-admin {email?} {--name=} {--password=}', function (AdminUserService $adminUserService) {
    $name = $this->option('name') ?: $this->ask('Admin name');
    $email = $this->argument('email') ?: $this->ask('Admin email');
    $password = $this->option('password') ?: $this->secret('Admin password');

    $validator = Validator::make([
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ], [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return self::FAILURE;
    }

    $user = $adminUserService->createOrUpdateAdmin(
        name: $name,
        email: $email,
        password: $password
    );

    $this->info('Admin account is ready.');
    $this->line('Name: '.$user->name);
    $this->line('Email: '.$user->email);
    $this->line('Role: '.$user->role->value);

    return self::SUCCESS;
})->purpose('Create or promote an admin account');
