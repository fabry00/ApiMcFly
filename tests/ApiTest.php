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
    private static $userUser;
    private static $adminToken;
    private static $userToken;
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');

        if(self::$adminUser == null)
        {
          self::$adminUser = App\Models\User::where('name', '=', 'Admin')->first();
          self::$userUser  = App\Models\User::where('name', '=', 'User 1')->first();


          $credentials = ['email' => 'admin@test.com','password' => 'test'];
          $response =$this->call("POST", "/api/public/authenticate",$credentials);
          $data = $this->parseJson($response);
          self::$adminToken =  $data->token;

          $credentials = ['email' => 'user@test.com','password' => 'test'];
          $response =$this->call("POST", "/api/public/authenticate",$credentials);
          $data = $this->parseJson($response);
          self::$userToken =  $data->token;
        }
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

    public function testAuthenticateUnSuccesfull()
    {
      $credentials = [
            'email' => 'admin@test.com',
            'password'    => 'tes111t'
        ];
      $response =$this->call("POST", "/api/public/authenticate",$credentials);
      $this->assertEquals(HttpResponse::HTTP_UNAUTHORIZED, $response->status());
    }


    public function testUnautorizedApi()
    {
      $response =$this->call("GET", "/api/auth/authenticate/user");
      $this->assertEquals(HttpResponse::HTTP_UNAUTHORIZED, $response->status());
    }


    public function testCreateNotUnautorized()
    {
      /*$note = factory(App\Models\Note::class)->make();
      $response = $this->call(
           'GET',
           '/',
           [], //parameters
           [], //cookies
           [], // files
           ['HTTP_Authorization' => 'Bearer ' . self::$userToken], // server
           []
       );
      $response =$this->call("POST", "/api/public/authenticate",$credentials);
      $this->assertEquals(HttpResponse::HTTP_UNAUTHORIZED, $response->status());*/
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
