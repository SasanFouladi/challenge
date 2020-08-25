<?php

namespace Tests\Feature\Panel\Code;

use App\Code;
use App\Http\Middleware\VerifyCsrfToken;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated user can not be show Code info.
     * @test
     * @return void
     */
    public function un_authenticate_user_can_not_show_code_info()
    {
        // create code for get info
        $code = factory(Code::class)->create();

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('get', route('codes.show', ['code' => $code->id]));

        // check correct response
        $response->assertStatus(401);
    }

    /**
     * Only authenticate user can show code info.
     * @test
     * @return void
     */
    public function only_authenticate_user_can_show_code_info()
    {
        // create code for get info
        $code = factory(Code::class)->create();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('get', route('codes.show', ['code' => $code->id]));

        // check valid response
        $response->assertOk();
    }

    /**
     * Code info received successfully.
     * @test
     * @return void
     */
    public function show_code_info_successfully()
    {
        // create code for get info
        $code = factory(Code::class)->create();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('get', route('codes.show', ['code' => $code->id]));

        // check valid response
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
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
            ]);

    }
}
