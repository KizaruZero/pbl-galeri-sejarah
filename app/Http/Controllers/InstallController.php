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


class InstallController extends Controller
{
    public function index()
    {
        // Redirect if already installed
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
        ]);

        try {
            // Clear all caches first
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            // Test database connection first
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
            ]);

            // Rebuild config cache
            Artisan::call('config:cache');

            // Return success response with a flag to indicate client should reload
            return response()->json([
                'message' => 'Database configuration updated successfully.',
                'status' => 200,
                'redirect' => '/registration-company',
                'reload_required' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Database change failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Database change failed: ' . $e->getMessage()
            ], 500);
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

    public function install(Request $request)
    {
        // Check if already installed
        if (file_exists(storage_path('installed'))) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Application already installed'], 400);
            }
            return redirect('/')->with('info', 'Application already installed');
        }

        if (!$request->hasSession()) {
            $request->setLaravelSession(app('session')->driver());
        }

        // Set longer execution time and memory limit
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '8048M');
        set_time_limit(300);

        // Validate request
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_email' => 'required|email',
            'app_phone' => 'nullable|string',
            'app_address' => 'nullable|string',
        ]);

        try {
            // Step 1: Test database connection
            // Step 5: Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);

            // Step 6: Create storage link
            try {
                if (!file_exists(public_path('storage'))) {
                    Artisan::call('storage:link');
                }
            } catch (\Exception $e) {
                \Log::warning('Storage link failed: ' . $e->getMessage());
            }

            // create db  seed
            Artisan::call('db:seed');
            // Step 7: Create company profile
            try {
                CompanyProfile::create([
                    'logo' => null,
                    'cms_name' => $validated['app_name'],
                    'cms_email' => $validated['app_email'],
                    'cms_phone' => $validated['app_phone'],
                    'cms_address' => $validated['app_address'],
                ]);
            } catch (\Exception $e) {
                \Log::warning('Company profile creation failed: ' . $e->getMessage());
            }

            // Step 8: Create installation marker
            File::put(storage_path('installed'), now()->toDateTimeString());
            $request->session()->regenerate();
            // Return success response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Installation completed successfully!',
                    'reload' => true // Tell client to reload
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

    private function testDatabaseConnection($database, $username, $password)
    {
        try {
            $host = config('database.connections.mysql.host', '127.0.0.1');
            $port = config('database.connections.mysql.port', 3306);

            // First try to connect without database name to check if we can create it
            $pdo = new \PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_TIMEOUT => 5
                ]
            );

            // Check if database exists
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
            $databaseExists = $stmt->fetch();

            if (!$databaseExists) {
                // Create database if it doesn't exist
                $pdo->exec("CREATE DATABASE `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            // Now connect to the specific database
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

    // Helper function to update .env file
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
        // Extract table name from migration filename
        if (preg_match('/create_(\w+)_table/', $migrationName, $matches)) {
            return $matches[1];
        }
        return null;
    }
}