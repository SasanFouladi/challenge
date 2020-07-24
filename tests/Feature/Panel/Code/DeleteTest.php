<?php

namespace Tests\Feature\Panel\Code;

use App\Code;
use App\Http\Middleware\VerifyCsrfToken;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated user can not be delete Code.
     * @test
     * @return void
     */
    public function un_authenticate_user_can_not_delete_code()
    {
        // create code for delete
        $code = factory(Code::class)->create();

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('delete', route('codes.destroy', ['code' => $code->id]));

        // check correct response
        $response->assertStatus(401);
    }

    /**
     * Only authenticate user can delete code.
     * @test
     * @return void
     */
    public function only_authenticate_user_can_delete_code()
    {
        // create code for delete
        $code = factory(Code::class)->create();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('delete', route('codes.destroy', ['code' => $code->id]));

        // check valid response
        $response->assertOk();
    }

    /**
     * Code deleted successfully.
     * @test
     * @return void
     */
    public function delete_code_successfully()
    {
        // create code for delete
        $code = factory(Code::class)->create();

        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->json('delete', route('codes.destroy', ['code' => $code->id]));

        // check valid response
        $response->assertOk();

        // Check that the code has been soft deleted
        $this->assertSoftDeleted('codes', [
            'name' => $code->name,
            'code' => $code->code,
        ]);
    }
}
