<?php

namespace Tests\Feature\Panel\Code;

use App\Code;
use App\Http\Middleware\VerifyCsrfToken;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated user can not be update Code.
     * @test
     * @return void
     */
    public function un_authenticate_user_can_not_update_code()
    {
        // save code for update
        $oldCode = factory(Code::class)->create();

        // make request data
        $newCode = factory(Code::class)->make()->toArray();


        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('patch', route('codes.update', ['code' => $oldCode->id]), $newCode);

        // check correct response
        $response->assertStatus(401);
    }

    /**
     * Only authenticate user can update code.
     * @test
     * @return void
     */
    public function only_authenticate_user_can_update_code()
    {
        // save code for update
        $oldCode = factory(Code::class)->create();

        // make request data
        $newCode = factory(Code::class)->make()->toArray();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('patch', route('codes.update', ['code' => $oldCode->id]), $newCode);

        // check valid response
        $response->assertOk();
    }

    /**
     * code updated successfully
     * @test
     * @return void
     */
    public function update_code_successfully()
    {
        // save code for update
        $oldCode = factory(Code::class)->create();

        // make request data
        $newCode = factory(Code::class)->make()->toArray();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('patch', route('codes.update', ['code' => $oldCode->id]), $newCode);

        // check valid response
        $response->assertOk();

        // new values exist in database
        $this->assertDatabaseHas('codes', [
            'code' => $newCode['code'],
            'name' => $newCode['name'],
        ]);

        // old values not exist in database
        $this->assertDatabaseMissing('codes',[
           'code' => $oldCode->code,
           'name' => $oldCode->name
        ]);
    }
}
