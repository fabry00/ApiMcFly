<?php
namespace database\seeds;

use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

/**
 * User table seeder
 */
class UsersTableSeeder extends Seeder {

    public function run() {
        $this->command->info('UsersTableSeeder starting');
        // Not needed. Db already cleaned in DatabaseSeeder.php
        //DB::table('users')->delete();
        $users = array(
            ['name' => 'Admin',  'email' => 'admin@test.com', 'password' => Hash::make('admin')],
            ['name' => 'User 1', 'email' => 'user@test.com', 'password' => Hash::make('test')],
            ['name' => 'User 2', 'email' => 'user2@test.com', 'password' => Hash::make('test2')],
            ['name' => 'User 3', 'email' => 'user3@test.com', 'password' => Hash::make('test3')],
        );

        $adminRole = Role::where('name', '=', 'admin')->first();
        $moderatorRole = Role::where('name', '=', 'moderator')->first();
        $userRole = Role::where('name', '=', 'user')->first();

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user) {
            $newUser = User::create($user);
            if($newUser->name == 'Admin'){
              $newUser->roles()->attach($adminRole->id);
            }
        }

        $this->command->info('USers added: '.User::count());
        $this->command->info('Users table seeded!');
    }

}
