<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response as HttpResponse;
//use JWTAuth;
use App\database\ModelFactory;

class ApiTest extends TestCase
{
    // rollback the database after each test and migrate it before the next test
    use DatabaseMigrations;

    private static $adminUser;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');

        self::$adminUser = App\Models\User::where('name', '=', 'Admin')->first();
    }
    private static function getAdminUser(){
      /*$user = factory(App\Models\User::class)->create([])->each(function($u) {
            $u->roles()->attach(1);
            $note = factory(App\Models\Note::class)->create([
              "public" => 1,
              "user_id" => $u->$this->adminRole->id
            ]);
      });*/
    }

    /**
     * Test getPublicNotes
     *
     * @return void
     */
    public function testGetPublicNotes()
    {
      $response =$this->call("GET", "/api/public/notes/public");
      $this->assertResponseOk();
      $data = $this->parseJson($response);
      $this->assertNotEmpty($data);
      $this->checkPublicNotes($data);
    }

    public function testGetNotesCount()
    {
      $response =$this->call("GET", "/api/public/notes/count");
      $this->assertResponseOk();
      $data = $this->parseJson($response);
      $this->assertTrue($data>0);
    }

    public function testAuthenticateSuccesfull()
    {
      $credentials = [
            'email' => 'admin@test.com',
            'password'    => 'test'
        ];
      $response =$this->call("POST", "/api/public/authenticate",$credentials);
      $this->assertEquals(HttpResponse::HTTP_ACCEPTED, $response->status());
      $data = $this->parseJson($response);
      $this->assertNotEmpty($data->token);
    }




    protected function checkPublicNotes($data){
      array_walk($data,function($item, $key){
          $this->assertEquals(1,$item->public,"Note: ".$item->id." is not public");
          $this->assertEquals(1,$item->user_id,"The owner of the Note: ".$item->id." is not Admin");
      });
    }

    protected function parseJson(Illuminate\Http\JsonResponse $response)
   {
       return json_decode($response->getContent());
   }
}
