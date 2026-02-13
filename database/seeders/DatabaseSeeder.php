<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(1)->create();

        $this->call(SaoSeeder::class);

        //php artisan shield:super-admin --user=1 --panel=admin
        Artisan::call('shield:super-admin', [
            '--user' => 1,
            '--panel' => 'admin'
        ]);

        //php artisan shield:generate --all --ignore-existing-policies --panel=admin
        Artisan::call('shield:generate', [
            '--all' => true,
            '--ignore-existing-policies' => true,
            '--panel' => 'admin'
        ]);
    }
}
