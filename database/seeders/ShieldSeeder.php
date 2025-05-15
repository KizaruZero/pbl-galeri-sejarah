<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[
            {
                "name": "super_admin",
                "guard_name": "web",
                "permissions": [
                    "view_article", "view_any_article", "create_article", "update_article", "restore_article", 
                    "restore_any_article", "replicate_article", "reorder_article", "delete_article", "delete_any_article", 
                    "force_delete_article", "force_delete_any_article", "view_category", "view_any_category", 
                    "create_category", "update_category", "restore_category", "restore_any_category", "replicate_category", 
                    "reorder_category", "delete_category", "delete_any_category", "force_delete_category", 
                    "force_delete_any_category", "view_category::content", "view_any_category::content", 
                    "create_category::content", "update_category::content", "restore_category::content", 
                    "restore_any_category::content", "replicate_category::content", "reorder_category::content", 
                    "delete_category::content", "delete_any_category::content", "force_delete_category::content", 
                    "force_delete_any_category::content", "view_content::photo", "view_any_content::photo", 
                    "create_content::photo", "update_content::photo", "restore_content::photo", "restore_any_content::photo", 
                    "replicate_content::photo", "reorder_content::photo", "delete_content::photo", 
                    "delete_any_content::photo", "force_delete_content::photo", "force_delete_any_content::photo", 
                    "view_content::reaction", "view_any_content::reaction", "create_content::reaction", 
                    "update_content::reaction", "restore_content::reaction", "restore_any_content::reaction", 
                    "replicate_content::reaction", "reorder_content::reaction", "delete_content::reaction", 
                    "delete_any_content::reaction", "force_delete_content::reaction", "force_delete_any_content::reaction", 
                    "view_content::video", "view_any_content::video", "create_content::video", "update_content::video", 
                    "restore_content::video", "restore_any_content::video", "replicate_content::video", 
                    "reorder_content::video", "delete_content::video", "delete_any_content::video", 
                    "force_delete_content::video", "force_delete_any_content::video", "view_event", "view_any_event", 
                    "create_event", "update_event", "restore_event", "restore_any_event", "replicate_event", 
                    "reorder_event", "delete_event", "delete_any_event", "force_delete_event", "force_delete_any_event", 
                    "view_metadata::photo", "view_any_metadata::photo", "create_metadata::photo", "update_metadata::photo", 
                    "restore_metadata::photo", "restore_any_metadata::photo", "replicate_metadata::photo", 
                    "reorder_metadata::photo", "delete_metadata::photo", "delete_any_metadata::photo", 
                    "force_delete_metadata::photo", "force_delete_any_metadata::photo", "view_metadata::video", 
                    "view_any_metadata::video", "create_metadata::video", "update_metadata::video", 
                    "restore_metadata::video", "restore_any_metadata::video", "replicate_metadata::video", 
                    "reorder_metadata::video", "delete_metadata::video", "delete_any_metadata::video", 
                    "force_delete_metadata::video", "force_delete_any_metadata::video", "view_reaction", 
                    "view_any_reaction", "create_reaction", "update_reaction", "restore_reaction", 
                    "restore_any_reaction", "replicate_reaction", "reorder_reaction", "delete_reaction", 
                    "delete_any_reaction", "force_delete_reaction", "force_delete_any_reaction", "view_role", 
                    "view_any_role", "create_role", "update_role", "delete_role", "delete_any_role", "view_token", 
                    "view_any_token", "create_token", "update_token", "restore_token", "restore_any_token", 
                    "replicate_token", "reorder_token", "delete_token", "delete_any_token", "force_delete_token", 
                    "force_delete_any_token", "view_user", "view_any_user", "create_user", "update_user", "restore_user", 
                    "restore_any_user", "replicate_user", "reorder_user", "delete_user", "delete_any_user", 
                    "force_delete_user", "force_delete_any_user", "view_user::comment", "view_any_user::comment", 
                    "create_user::comment", "update_user::comment", "restore_user::comment", "restore_any_user::comment", 
                    "replicate_user::comment", "reorder_user::comment", "delete_user::comment", "delete_any_user::comment", 
                    "force_delete_user::comment", "force_delete_any_user::comment", "view_user::favorite", 
                    "view_any_user::favorite", "create_user::favorite", "update_user::favorite", "restore_user::favorite", 
                    "restore_any_user::favorite", "replicate_user::favorite", "reorder_user::favorite", 
                    "delete_user::favorite", "delete_any_user::favorite", "force_delete_user::favorite", 
                    "force_delete_any_user::favorite", "view_user::reaction", "view_any_user::reaction", 
                    "create_user::reaction", "update_user::reaction", "restore_user::reaction", 
                    "restore_any_user::reaction", "replicate_user::reaction", "reorder_user::reaction", 
                    "delete_user::reaction", "delete_any_user::reaction", "force_delete_user::reaction", 
                    "force_delete_any_user::reaction", "page_CompanyProfileSettings", "page_FilamentGoogleAnalyticsDashboard", 
                    "widget_ContentPhotoChart", "widget_ContentVideoChart", "widget_LatestPhoto", "widget_LatestVideo", 
                    "widget_StatsOverview", "widget_VideoContentCategory", "widget_PhotoContentCategory", 
                    "widget_PageViewsWidget", "widget_VisitorsWidget", "widget_ActiveUsersOneDayWidget", 
                    "widget_ActiveUsersSevenDayWidget", "widget_ActiveUsersTwentyEightDayWidget", "widget_SessionsWidget", 
                    "widget_SessionsDurationWidget", "widget_SessionsByCountryWidget", "widget_SessionsByDeviceWidget", 
                    "widget_MostVisitedPagesWidget", "widget_TopReferrersListWidget"
                ]
            },
            {
                "name": "member",
                "guard_name": "web",
                "permissions": [
                    "view_article", "view_category", "view_content::photo", "view_content::reaction", 
                    "view_content::video", "view_event", "view_metadata::photo", "view_metadata::video", 
                    "view_reaction", "view_role", "view_user", "view_user::comment", "view_user::favorite", 
                    "view_user::reaction"
                ]
            },
            {
                "name": "author",
                "guard_name": "web",
                "permissions": [
                    "view_article", "view_any_article", "create_article", "update_article", "restore_article", 
                    "delete_article"
                ]
            },
            {
                "name": "direktur",
                "guard_name": "web",
                "permissions": [
                    "view_category", "view_any_category", "create_category", "update_category", "restore_category", 
                    "restore_any_category", "replicate_category", "reorder_category", "delete_category", 
                    "delete_any_category", "force_delete_category", "force_delete_any_category"
                ]
            }
        ]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (!blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (!blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (!blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }


}