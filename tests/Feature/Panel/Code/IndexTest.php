<?php

namespace Tests\Feature\Panel\Code;

use App\Code;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated user can not be get codes list.
     * @test
     * @return void
     */
    public function un_authenticate_user_can_not_get_codes()
    {
        // send request
        $response = $this->json('get', route('codes.index'));

        // check correct response
        $response->assertStatus(401);
    }

    /**
     * Only authenticate user can get codes.
     * @test
     * @return void
     */
    public function only_authenticate_user_can_get_codes()
    {
        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $response = $this->json('get', route('codes.index'));

        // response status code == 200
        $response->assertStatus(200);
    }

    /**
     * Only authenticate user can get codes.
     * @test
     * @return void
     */
    public function get_codes_successfully()
    {
        // create Codes
        factory(Code::class, 10)->create();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $response = $this->json('get', route('codes.index'));

        // check response data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'code',
                        'capacity',
                        'enable',
                        'creator',
                        'modifier',
                        'customers_count',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }
}
