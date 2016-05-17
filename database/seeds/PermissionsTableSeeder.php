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
        // Not needed. Db already cleaned in DatabaseSeeder.php
        //DB::table('users')->delete();
        $adminRole = Role::where('name', '=', 'admin')->first();

        if(empty($adminRole))
        {
            $this->command->error(get_class($this). ":: Admin role not found");
            exit(-1);
        }
        $permissionsAdmin = array(
            ['name' => 'create-users'],
            ['name' => 'create-roles'],
            ['name' => 'manage-notes'],                        
        );

        // Loop through each user above and create the record for them in the database
        foreach ($permissionsAdmin as $permission) {
            $permission = Permission::create($permission);
            $adminRole->attachPermission($permission);
        }

        $this->command->info('Permissions added: '.Permission::count());
        $this->command->info('Permissions table seeded!');
    }

}
