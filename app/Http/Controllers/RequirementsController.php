<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class RequirementsController extends Controller
{
    public function checkRequirements()
    {
        $requirements = [
            'php' => [
                'openssl',
                'pdo',
                'mbstring',
                'tokenizer',
                'JSON',
                'cURL',
                'fileinfo',
                'gd',
                'xml',
                'zip',
                'bcmath',
            ],
            'filament' => [
                'minimum_version' => '3.0.0',
            ],
            'livewire' => [
                'minimum_version' => '3.0.0',
            ],
            'inertia' => [
                'minimum_version' => '1.0.0',
            ],
            'vue' => [
                'minimum_version' => '3.0.0',
            ],
            'tailwind' => [
                'minimum_version' => '3.0.0',
            ],
            'vite' => [
                'minimum_version' => '4.0.0',
            ],
            'axios' => [
                'minimum_version' => '1.0.0',
            ],
        ];

        $permissions = [
            'storage/framework/' => '775',
            'storage/logs/' => '775',
            'bootstrap/cache/' => '775',
            'public/storage' => '775',
        ];

        $results = [
            'requirements' => [],
            'permissions' => [],
            'versions' => [
                'php' => [
                    'current' => PHP_VERSION,
                    'minimum' => '8.2.0',
                    'supported' => version_compare(PHP_VERSION, '8.2.0', '>=')
                ],
                'filament' => $this->getFilamentVersion(),
                'livewire' => $this->getLivewireVersion(),
                'inertia' => $this->getPackageVersion('@inertiajs/vue3'),
                'vue' => $this->getPackageVersion('vue'),
                'tailwind' => $this->getPackageVersion('tailwindcss'),
                'vite' => $this->getPackageVersion('vite'),
                'axios' => $this->getPackageVersion('axios'),
            ]
        ];

        // Check PHP Extensions
        foreach ($requirements['php'] as $extension) {
            $results['requirements'][] = [
                'type' => 'PHP',
                'name' => $extension,
                'status' => extension_loaded($extension),
                'required' => true
            ];
        }

        // Check Folder Permissions
        foreach ($permissions as $folder => $permission) {
            $path = base_path($folder);
            if (File::exists($path)) {
                $currentPermission = substr(sprintf('%o', fileperms($path)), -4);
                $results['permissions'][] = [
                    'folder' => $folder,
                    'current' => $currentPermission,
                    'required' => $permission,
                    'status' => $currentPermission >= $permission
                ];
            }
        }

        return response()->json($results);
    }

    private function getFilamentVersion()
    {
        try {
            $composerJson = base_path('composer.json');
            if (file_exists($composerJson)) {
                $json = json_decode(file_get_contents($composerJson), true);
                if (isset($json['require']['filament/filament'])) {
                    $version = trim($json['require']['filament/filament'], '^~');
                    return [
                        'current' => $version,
                        'minimum' => '3.0.0',
                        'supported' => version_compare($version, '3.0.0', '>=')
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Filament version check failed: ' . $e->getMessage());
        }

        return [
            'current' => null,
            'minimum' => '3.0.0',
            'supported' => false
        ];
    }

    private function getLivewireVersion()
    {
        try {
            $composerJson = base_path('composer.json');
            if (file_exists($composerJson)) {
                $json = json_decode(file_get_contents($composerJson), true);
                if (isset($json['require']['livewire/livewire'])) {
                    $version = trim($json['require']['livewire/livewire'], '^~');
                    return [
                        'current' => $version,
                        'minimum' => '3.0.0',
                        'supported' => version_compare($version, '3.0.0', '>=')
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Livewire version check failed: ' . $e->getMessage());
        }

        return [
            'current' => null,
            'minimum' => '3.0.0',
            'supported' => false
        ];
    }

    private function getPackageVersion($packageName)
    {
        try {
            $packageJson = base_path('package.json');
            if (file_exists($packageJson)) {
                $json = json_decode(file_get_contents($packageJson), true);

                // Check dependencies
                if (isset($json['dependencies'][$packageName])) {
                    $version = trim($json['dependencies'][$packageName], '^~');
                    return [
                        'current' => $version,
                        'minimum' => $this->getMinimumVersion($packageName),
                        'supported' => version_compare($version, $this->getMinimumVersion($packageName), '>=')
                    ];
                }

                // Check devDependencies
                if (isset($json['devDependencies'][$packageName])) {
                    $version = trim($json['devDependencies'][$packageName], '^~');
                    return [
                        'current' => $version,
                        'minimum' => $this->getMinimumVersion($packageName),
                        'supported' => version_compare($version, $this->getMinimumVersion($packageName), '>=')
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Package version check failed for {$packageName}: " . $e->getMessage());
        }

        return [
            'current' => null,
            'minimum' => $this->getMinimumVersion($packageName),
            'supported' => false
        ];
    }

    private function getMinimumVersion($packageName)
    {
        $minimumVersions = [
            '@inertiajs/vue3' => '1.0.0',
            'vue' => '3.0.0',
            'tailwindcss' => '3.0.0',
            'vite' => '4.0.0',
            'axios' => '1.0.0',
        ];

        return $minimumVersions[$packageName] ?? '0.0.0';
    }
}