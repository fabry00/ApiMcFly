<?php

namespace database\seeds;

use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use App\Models\Permission;

/**
 * User table seeder
 */
class PermissionsTableSeeder extends Seeder {

    public function run() {
        // Not needed. Db already cleaned in DatabaseSeeder.php
        //DB::table('users')->delete(); 


        $permissions = array(
            ['name' => 'create-users']
        );

        // Loop through each user above and create the record for them in the database
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('Role table seeded!');
    }

}
