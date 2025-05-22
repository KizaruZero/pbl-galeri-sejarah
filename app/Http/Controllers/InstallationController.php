<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\InstallationRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
class InstallationController extends Controller
{
    //
    public function showInstallForm()
    {
        // Blokir jika sudah terinstall
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        return view('form');
    }

    public function install(InstallationRequest $request)
    {
        // Validasi ekstra
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        // Proses instalasi
        DB::transaction(function () use ($request) {
            // 1. Setup Environment
            $this->setupEnvironment($request->validated());

            // 2. Jalankan Migrasi & Seeding
            $this->runDatabaseSetup();

            // 3. Buat Admin User
            $admin = $this->createAdminUser($request);

            // 4. Setup Company Profile
            $this->setupCompanyProfile($request, $admin);

            // 5. Finalisasi
            $this->finalizeInstallation();
        });

        return redirect('/');
    }

    // Helper Methods
    protected function setupEnvironment($data)
    {
        // Update .env
        $envUpdates = [
            'APP_NAME' => '"' . $data['company_name'] . '"',
            'APP_URL' => $data['site_url'],
            'DB_DATABASE' => $data['db_name'],
            // ... tambahkan config lainnya
        ];

        $this->updateEnvFile($envUpdates);

        // Clear cache config
        Artisan::call('config:clear');
    }

    protected function runDatabaseSetup()
    {
        // Test koneksi database
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            throw new ("Database connection failed: " . $e->getMessage());
        }

        // Jalankan migrasi
        Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true
        ]);
    }

    protected function createAdminUser($request)
    {
        return User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'super_admin'
        ]);
    }

    protected function setupCompanyProfile($request, $admin)
    {
        CompanyProfile::create([
            'name' => $request->company_name,
            'email' => $request->company_email,
            'phone' => $request->company_phone,
            'address' => $request->company_address,
            'website' => $request->site_url,
            'created_by' => $admin->id
        ]);
    }

    protected function finalizeInstallation()
    {
        // Buat file penanda
        File::put(storage_path('installed'), 'v1.0.0');

        // Generate application key
        Artisan::call('key:generate', ['--force' => true]);

        // Optimize aplikasi
        Artisan::call('optimize');
    }

    protected function updateEnvFile($data)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= PHP_EOL . $replacement;
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
