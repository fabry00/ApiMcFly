<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\seeds\UsersTableSeeder;

/**
 * WARNING
 * If you create a new seeder class run in command line:
 *      composer dump-autoload
 * 
 * To seed the db run:
 *      php artisan db:seed
 */
class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (App::environment() === 'production') {
            exit('We are in production mode. Cannot seed database. Can be overriden using --env attribute');
        }
        
        if (App::environment() !== 'testing') {
            $this->truncateTables();
        }

        // Model::unguard() does temporarily disable the mass assignment 
        // protection of the model, so you can seed all model properties.
        // A mass-assignment vulnerability occurs when a user passes an unexpected 
        // HTTP parameter through a request, and that parameter changes a column 
        // in your database you did not expect. For example, a malicious user 
        // might send an is_admin parameter through an HTTP request, which is 
        // then mapped onto your model's create method, allowing the user to 
        // escalate themselves to an administrator.
        Model::unguard();

        
        // Users
        $this->call(UsersTableSeeder::class);

        Model::reguard();
    }
    
    /**
     * Truncates all tables except migrations
     */
    public function truncateTables()
    {
        $dbName = env('DB_DATABASE');
        // Get all tables list, except migrations table
        $tables = DB::select('SHOW TABLES WHERE `Tables_in_' . $dbName . '` != ?', ['migrations']);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($tables as $table) {
            DB::table($table->{'Tables_in_' . $dbName})->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
/*
class UsersTableSeeder extends Seeder {

    public function run() {
        User::unguard();
        DB::table('users')->delete();

        
        //User::create(['email' => 'foo@bar.com']);

        $users = array(
            ['name' => 'User 1', 'email' => 'user@test.com', 'password' => Hash::make('test')],
            ['name' => 'User 2', 'email' => 'user2@test.com', 'password' => Hash::make('test2')],
            ['name' => 'User 3', 'email' => 'user3@test.com', 'password' => Hash::make('test3')],
        );

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user) {
            User::create($user);
        }
        
         User::reguard();
        $this->command->info('User table seeded!');
    }

}*/