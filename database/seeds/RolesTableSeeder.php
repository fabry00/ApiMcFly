<?php

namespace database\seeds;

use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use App\Models\Role;

/**
 * User table seeder
 */
class RolesTableSeeder extends Seeder {

    public function run() {
        // Not needed. Db already cleaned in DatabaseSeeder.php
        //DB::table('users')->delete();

        $this->command->info('RolesTableSeeder starting');
        $roles = array(
            ['name' => 'admin'],
            ['name' => 'moderator'],
            ['name' => 'user']
        );

        // Loop through each user above and create the record for them in the database
        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('Roles added: '.Role::count());
        $this->command->info('Role table seeded!');
    }

}
