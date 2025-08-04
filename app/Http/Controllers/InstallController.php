<?php

namespace App\Http\Controllers;

use Google\Rpc\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class InstallController extends Controller
{
    public function index()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/')->with('info', 'Application already installed');
        }
        return Inertia::render('Views/ConfigurationForm');
    }

    public function changeDatabase(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_pass' => 'nullable|string',
            'db_host' => 'required|string',
        ]);

        try {
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            $this->testDatabaseConnection(
                $validated['db_name'],
                $validated['db_user'],
                $validated['db_pass'] ?? ''
            );

            // Update .env
            $this->updateEnv([
                'APP_NAME' => $validated['app_name'],
                'APP_URL' => $validated['app_url'],
                'DB_DATABASE' => $validated['db_name'],
                'DB_USERNAME' => $validated['db_user'],
                'DB_PASSWORD' => $validated['db_pass'] ?? '',
                'DB_HOST' => $validated['db_host'],
            ]);

            Artisan::call('config:cache');

            return response()->json([
                'message' => 'Database configuration updated successfully.',
                'status' => 200,
            ]);

        } catch (\Exception $e) {
            Log::error('Database change failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Database change failed: ' . $e->getMessage()
            ], 500);
        }
    }



    public function install(Request $request)
    {
        if (file_exists(storage_path('installed'))) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Application already installed'], 400);
            }
            return redirect('/')->with('info', 'Application already installed');
        }

        if (!$request->hasSession()) {
            $request->setLaravelSession(app('session')->driver());
        }
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '8048M');
        set_time_limit(300);

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_email' => 'required|email',
            'app_phone' => 'nullable|string',
            'app_address' => 'nullable|string',
            'admin.name' => 'required|string',
            'admin.email' => 'required|email',
            'admin.password' => 'required|string',
        ]);

        try {
            Artisan::call('migrate:fresh', ['--force' => true]);

            try {
                if (!file_exists(public_path('storage'))) {
                    Artisan::call('storage:link');
                }
            } catch (\Exception $e) {
                \Log::warning('Storage link failed: ' . $e->getMessage());
            }

            Artisan::call('db:seed', ['--force' => true]);
            try {
                CompanyProfile::create([
                    'logo' => null,
                    'cms_name' => $validated['app_name'],
                    'cms_email' => $validated['app_email'],
                    'cms_phone' => $validated['app_phone'],
                    'cms_address' => $validated['app_address'],
                ]);

                $user = User::create([
                    'name' => $validated['admin']['name'],
                    'email' => $validated['admin']['email'],
                    'password' => Hash::make($validated['admin']['password']),
                ]);

                $user->assignRole('super_admin');
            } catch (\Exception $e) {
                \Log::warning('Company profile creation failed: ' . $e->getMessage());
            }

            File::put(storage_path('installed'), now()->toDateTimeString());
            $request->session()->regenerate();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Installation completed successfully!',
                    'reload' => true
                ]);
            }

            return redirect('/login')->with('success', 'Installation completed!');

        } catch (\Exception $e) {
            \Log::error('Installation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Installation failed: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Installation failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function migrateFresh()
    {
        // update env
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('storage:link');
        // db seed
        Artisan::call('db:seed');
        return response()->json(['message' => 'Database migrated successfully.']);
    }

    private function testDatabaseConnection($database, $username, $password)
    {
        try {
            $host = config('database.connections.mysql.host', '127.0.0.1');
            $port = config('database.connections.mysql.port', 3306);

            $pdo = new \PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_TIMEOUT => 5
                ]
            );

            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
            $databaseExists = $stmt->fetch();

            if (!$databaseExists) {
                $pdo->exec("CREATE DATABASE `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            $pdo = new \PDO(
                "mysql:host={$host};port={$port};dbname={$database}",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_TIMEOUT => 5
                ]
            );

            $pdo = null;

        } catch (\Exception $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    private function updateEnv(array $data)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return false;
        }

        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^$key=.*/m";
            $replacement = "$key=\"$value\"";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= PHP_EOL . "$replacement";
            }
        }

        File::put($envPath, $envContent);
        return true;
    }

    private function getTableNameFromMigration($migrationName)
    {
        if (preg_match('/create_(\w+)_table/', $migrationName, $matches)) {
            return $matches[1];
        }
        return null;
    }
}