<?php

namespace Tests\Feature\Panel\Code;

use App\Code;
use App\Customer;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated user can not be get customers list.
     * @test
     * @return void
     */
    public function un_authenticate_user_can_not_get_customers()
    {

        // create code for get customers
        $code = factory(Code::class)->create();

        // send request
        $response = $this->json('get', route('codes.customers', ['code' => $code->id]));

        // check correct response
        $response->assertStatus(401);
    }

    /**
     * Only authenticate user can get codes.
     * @test
     * @return void
     */
    public function only_authenticate_user_can_get_customers()
    {
        // create code for get customers
        $code = factory(Code::class)->create();
        $code->customers()->createMany(factory(Customer::class, 20)->make()->toArray());


        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $response = $this->json('get', route('codes.customers', ['code' => $code->id]));

        // check valid response
        $response->assertOk();
    }

    /**
     * customers list received successfully.
     * @test
     * @return void
     */
    public function get_customers_successfully()
    {
        // create code for get customers
        $code = factory(Code::class)->create();
        $code->customers()->createMany(factory(Customer::class, 20)->make()->toArray());


        // create and login user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send request
        $response = $this->json('get', route('codes.customers', ['code' => $code->id]));

        // check valid response
        $response->assertOk();

        // check response data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'phone',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }
}
