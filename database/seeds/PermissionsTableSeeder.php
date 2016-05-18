<?php

namespace database\seeds;

use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;
/**
 * User table seeder
 */
class PermissionsTableSeeder extends Seeder {

    public function run() {
        $this->command->info('PermissionsTableSeeder starting');
        // Not needed. Db already cleaned in DatabaseSeeder.php
        //DB::table('users')->delete();
        $adminRole = Role::where('name', '=', 'admin')->first();
        $moderatorRole = Role::where('name', '=', 'moderator')->first();
        $userRole = Role::where('name', '=', 'user')->first();

        if(empty($adminRole))
        {
            $this->command->error(get_class($this). ":: Admin role not found");
            exit(-1);
        }
        if(empty($userRole))
        {
            $this->command->error(get_class($this). ":: User role not found");
            exit(-1);
        }
        if(empty($moderatorRole))
        {
            $this->command->error(get_class($this). ":: Moderator role not found");
            exit(-1);
        }

        $permissionUser = array(
            ['name' => 'create-notes'],
            ['name' => 'set-fav'],
        );

        $permissionModerator = array_merge ($permissionUser,array(
            ['name' => 'delete-notes'],
            ['name' => 'publish-notes'],
        ) );


        $permissionsAdmin = array_merge ($permissionModerator,array(
            ['name' => 'create-users'],
            ['name' => 'create-roles'],
        ));

        // Loop through each user above and create the record for them in the database
        foreach ($permissionsAdmin as $permission){
            $permissionModel = Permission::where('name', '=', $permission["name"])->first();
            if($permissionModel == null){
              $this->command->info('Creating permission: '.$permission["name"]);
              $permissionModel = Permission::create($permission);
            }
            $this->command->info('Attaching permission: '.$permission["name"]." to admin role");
            $adminRole->attachPermission($permissionModel);
        }

        foreach ($permissionModerator as $permission) {
            $permissionModel = Permission::where('name', '=', $permission["name"])->first();
            if($permissionModel == null){
              $this->command->info('Creating permission: '.$permission["name"]);
              $permission = Permission::create($permission);
            }
            $this->command->info('Attaching permission: '.$permission["name"]." to moderator role");
            $moderatorRole->attachPermission($permissionModel);
        }
        foreach ($permissionUser as $permission) {
            $permissionModel = Permission::where('name', '=', $permission["name"])->first();
            if($permissionModel == null){
              $this->command->info('Creating permission: '.$permission["name"]);
              $permission = Permission::create($permission);
            }
            $this->command->info('Attaching permission: '.$permission["name"]." to user role");
            $userRole->attachPermission($permissionModel);
        }

        $this->command->info('Permissions added: '.Permission::count());
        $this->command->info('Permissions table seeded!');
    }

}
