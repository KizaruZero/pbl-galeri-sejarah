<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SetupProject extends Command
{
    protected $signature = 'project:setup';
    protected $description = 'Setup project after cloning (composer install, npm install, env setup, and migrate)';

    public function handle()
    {
        $this->info('Starting project setup...');

        // Run composer install
        $this->info('Running composer install...');
        exec('composer install');
        $this->info('Composer install completed!');

        // Run npm install
        $this->info('Running npm install...');
        exec('npm install');
        $this->info('NPM install completed!');

        // Copy .env.example to .env if .env doesn't exist
        if (!File::exists(base_path('.env'))) {
            $this->info('Creating .env file...');
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('.env file created!');
        } else {
            $this->info('.env file already exists.');
        }

        // Update .env values
        $this->info('Updating .env configuration...');
        $this->updateEnvFile([
            'DB_CONNECTION' => 'mysql',
            'SESSION_DRIVER' => 'file',
            'SESSION_LIFETIME' => '60',
        ]);
        $this->info('.env configuration updated!');

        // Run migration for users table only
        $this->info('Running migration for users table...');
        $this->call('migrate', [
            '--path' => 'database/migrations/0001_01_01_000000_create_users_table.php'
        ]);
        $this->info('Migration completed!');

        // set sessions



        // key generate
        $this->info('Generating key...');
        $this->call('key:generate', ['--force' => true]);
        $this->info('Key generated!');

        // storage link
        $this->info('Creating storage link...');
        $this->call('storage:link');
        $this->info('Storage link created!');

        // CALL CONFIG CACHE
        $this->call('config:clear');
        $this->info('Config clear completed!');
        $this->info('Running config cache...');
        $this->call('config:cache');
        $this->info('Config cache completed!');
        $this->call('view:clear');
        $this->info('View clear completed!');

        $this->info('Project setup completed successfully!');
    }

    protected function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            // If key exists, replace it
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                // If key doesn't exist, add it
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envFile, $envContent);
    }
}