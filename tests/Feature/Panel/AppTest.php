<?php

namespace Tests\Feature\Panel;

use App\User;
use Tests\TestCase;

class AppTest extends TestCase
{

    /**
     * Unauthenticated user can not be seen panel admin.
     * @test
     * @return void
     */
    public function un_authenticate_user_can_not_see_panel()
    {
        // send request
        $response = $this->json('get', route('panel'));

        // check correct response
        $response->assertStatus(401);
    }

    /**
     * Only authenticate user can see panel admin.
     * @return void
     */
    public function only_authenticate_user_can_see_panel()
    {
        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $response = $this->json('get', route('panel'));

        // check valid response
        $response->assertOk();
    }

    /**
     * see panel admin successfully.
     * @return void
     */
    public function see_panel_successfully()
    {
        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $response = $this->json('get', route('panel'));

        // check valid response
        $response->assertOk()
            ->assertSeeText($user->name);
    }


}
