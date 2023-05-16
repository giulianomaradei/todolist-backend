<?php

namespace Tests\Feature;

use App\Models\Tenant\User\User;
use Tests\BaseTest;


class UserTest extends BaseTest
{

    protected $header;
    protected $baseUrl;

    protected function setUp():void
    {
        parent::setUp();
        $this->baseUrl = env('API_URL') . "/api/user";
        $this->header = $this->getHeaderAutorization();
    }


    /********************************************************
     * Create user tests.
     ********************************************************/
    public function testNewUser()
    {
        $response = $this->withHeaders($this->header)->postJson(
            $this->baseUrl,
            $this->newUser()
        );

        $response->assertOk()->assertJsonStructure($this->userStructure());
    }


    /********************************************************
     * Get user tests.
     ********************************************************/
    public function testGetUser()
    {
        $response = $this->withHeaders($this->header)->postJson(
            $this->baseUrl,
            $this->newUser()
        );

        $id = json_decode($response->content(), true)['data']['id'];

        $response = $this->withHeaders($this->header)->getJson(
            $this->baseUrl.'/'. $id
        );

        $response->assertOk()->assertJsonStructure($this->userStructure());
    }


    /********************************************************
     * Get profile tests.
     ********************************************************/
    public function testProfileUser()
    {
        $response = $this->withHeaders($this->header)->getJson(
            $this->baseUrl
        );

        $response->assertOk()->assertJsonStructure($this->userStructure());
    }


    /********************************************************
     * List user tests.
     ********************************************************/
    public function testGetUsersList()
    {
        $response = $this->withHeaders($this->header)->getJson("{$this->baseUrl}/all/0/10");
        $response->assertOk()->assertJsonStructure([
            'data' => ['total_items', 'pages', 'list']
        ]);
    }


    /********************************************************
     * Update user tests.
     ********************************************************/
    public function testUpdateUser()
    {
        $response = $this->withHeaders($this->header)->postJson(
            $this->baseUrl,
            $this->newUser()
        );

        $user = json_decode($response->content(), true)['data'];

        $response = $this->withHeaders($this->header)->postJson(
            "{$this->baseUrl}/{$user['id']}",
            $user
        );

        $response->assertOk()->assertJsonStructure($this->userStructure());
    }


    /********************************************************
     * Update profile tests.
     ********************************************************/
    public function testUpdateProfile()
    {
        $response = $this->withHeaders($this->header)->getJson(
            $this->baseUrl,
            $this->newUser()
        );

        $user = json_decode($response->content(), true)['data'];

        $response = $this->withHeaders($this->header)->postJson(
            "{$this->baseUrl}/profile",
            $user
        );

        $response->assertOk()->assertJsonStructure($this->userStructure());
    }


    /********************************************************
     * Delete tests.
     ********************************************************/
    public function testDeleteUser()
    {
        $response = $this->withHeaders($this->header)->postJson(
            $this->baseUrl,
            $this->newUser()
        );

        $user = json_decode($response->content(), true)['data'];

        $response = $this->withHeaders($this->header)->deleteJson("{$this->baseUrl}/{$user['id']}");

        $response->assertOk();

    }


    /********************************************************
     * Private methods.
     ********************************************************/
    private function newUser()
    {
        $user = User::factory()->make();

        return [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'settings' => ['type' => 'admin']
        ];
    }

    private function userStructure()
    {
        return [
            'data' => [ 'id', 'name','email','deleted_at',
                'settings' => [
                    'id','user_id','type','lang','role_id','image_id','deleted_at',
                    'image' => [ 'url','name','size','type','uuid','file_name' ],
                    'role' => [ 'id','name','slug' ]
                ]
            ]
        ];
    }
}
