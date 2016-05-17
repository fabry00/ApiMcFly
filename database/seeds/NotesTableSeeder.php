<?php

namespace database\seeds;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Note;
use App\Models\User;

/**
 * User table seeder
 */
class NotesTableSeeder extends Seeder {

    public function run() {
        $faker = Faker::create();

        $users = User::all();
        $usersID = $users->lists('id')->toArray();

        foreach (range(1, 50) as $index) {
            Note::create([
                'text' => $faker->Text,
                'public' => $faker->randomElement(array(0,1)),
                'user_id' => $faker->randomElement($usersID),
            ]);
        }

        $publicNotes = Note::where('public', '=', 1)->get();

        $this->command->info('Notes added: '.Note::count());
        $this->command->info('Pubblic Notes: '.count($publicNotes));

         //var_dump($users->toArray());
        //var_dump($publicNotes);
        $maxFavoriteNotes = rand ( 1 , count($publicNotes)-1);
        $this->command->info('Favorite notes to assign:: '.$maxFavoriteNotes);
        foreach ($publicNotes as $note)
        {
            $randomUsers = array_rand($users->toArray(), rand ( 1 , count($users->toArray())-1));
            foreach((array)$randomUsers as $key => $value)
            {
                // Assign the curent note as favorite to the random user
                $user = $users[$key];
                $this->command->info('Set favorite note with id: '.$note["id"]." to user: ".$user["name"]);
                $user->favorite_notes()->attach($note["id"]);
                //echo "Random userId: ".$value." assign favorit note: ".$note["id"]."\n";
            }
            if($maxFavoriteNotes-- <= 0){
              break;
            }
        }

        $this->command->info('Notes table seeded!');
    }

}
