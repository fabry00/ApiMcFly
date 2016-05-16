<?php
namespace database\seeds;

use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


/**
 * User table seeder
 */
class UsersTableSeeder extends Seeder {

    public function run() {
        // Not needed. Db already cleaned in DatabaseSeeder.php
        //DB::table('users')->delete(); 

        
        $users = array(
            ['name' => 'User 1', 'email' => 'user@test.com', 'password' => Hash::make('test')],
            ['name' => 'User 2', 'email' => 'user2@test.com', 'password' => Hash::make('test2')],
            ['name' => 'User 3', 'email' => 'user3@test.com', 'password' => Hash::make('test3')],
        );

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user) {
            User::create($user);
        }
        
        $this->command->info('Users table seeded!');
    }

}
