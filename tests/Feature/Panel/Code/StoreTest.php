<?php

namespace Tests\Feature\Panel\Code;

use App\Code;
use App\Http\Middleware\VerifyCsrfToken;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated user can not be store code.
     * @test
     * @return void
     */
    public function un_authenticate_user_can_not_store_code()
    {
        // make request data
        $code = factory(Code::class)->make()->toArray();

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('post', route('codes.store'), $code);

        // check correct response
        $response->assertStatus(401);
    }

    /**
     * Only authenticate user can store code.
     * @test
     * @return void
     */
    public function only_authenticate_user_can_store_code()
    {
        // make request data
        $code = factory(Code::class)->make()->toArray();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        //send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('post', route('codes.store'), $code);

        // check valid response
        $response->assertCreated();
    }

    /**
     * code created successfully.
     * @test
     * @return void
     */
    public function store_code_successfully()
    {
        // make request data
        $code = factory(Code::class)->make()->toArray();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        //send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('post', route('codes.store'), $code);

        // response status code == 201 and valid data
        $response->assertStatus(201);
        $this->assertDatabaseHas('codes', [
            'code' => $code['code'],
        ]);
    }
}
