<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test:seed {--demo}', function () {
    if (app()->environment('production')) {
        $this->error('This command is disabled in production.');
        return self::FAILURE;
    }

    if ($this->option('demo')) {
        $this->call('db:seed', ['--class' => \Database\Seeders\DemoDataSeeder::class]);
    } else {
        $this->call('db:seed');
    }

    return self::SUCCESS;
})->purpose('Seed all test/demo data');

Artisan::command('test:reset', function () {
    if (app()->environment('production')) {
        $this->error('This command is disabled in production.');
        return self::FAILURE;
    }

    $this->call('migrate:fresh', ['--seed' => true]);

    return self::SUCCESS;
})->purpose('Drop all tables, re-run migrations, and seed all test/demo data');
