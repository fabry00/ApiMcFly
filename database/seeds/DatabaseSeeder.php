<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\seeds\UsersTableSeeder;
use database\seeds\RolesTableSeeder;
use database\seeds\PermissionsTableSeeder;
use database\seeds\NotesTableSeeder;
use App\Models\User;
use App\Models\Role;

use Illuminate\Support\Facades\DB;
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
        /*if (App::environment() === 'production') {
            exit('We are in production mode. Cannot seed database. Can be overriden using --env attribute');
        }*/

        if (App::environment() !== 'testing') {
            //$this->truncateTables();
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

        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(NotesTableSeeder::class);

        Model::reguard();
    }

    /**
     * Truncates all tables except migrations
     */
    public function truncateTables()
    {
       // User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('notes')->truncate();
        /*DB::table('favorite_notes')->delete();
        DB::table('password_resets')->delete();
        DB::table('permission_role')->delete();*/
        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }

}
