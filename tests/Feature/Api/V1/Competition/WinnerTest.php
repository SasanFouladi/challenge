<?php

namespace Tests\Feature\Api\V1\Competition;

use App\Code;
use App\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WinnerTest extends TestCase
{
    use  RefreshDatabase;
    /**
     * code input field is required test.
     *
     * @test
     * @return void
     */
    public function code_input_is_required()
    {
        //send request
        $response = $this->json('get', route('competition.winner'));

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["code"])
            ->assertJson([
                "errors" => [
                    "code" => ["The code field is required."]
                ]
            ]);
    }

    /**
     * code input field is string test.
     *
     * @test
     * @return void
     */
    public function code_input_is_string()
    {
        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'code' => 123456 // test
            ]
        );

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["code"])
            ->assertJson([
                "errors" => [
                    "code" => ["The code must be a string."]
                ]
            ]);
    }

    /**
     * The code length must be at least 4 characters.
     *
     * @test
     * @return void
     */
    public function code_input_must_be_at_least_4_characters()
    {
        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'code' => Str::random(3) // test
            ]
        );

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["code"])
            ->assertJson([
                "errors" => [
                    "code" => ["The code must be at least 4 characters."]
                ]
            ]);
    }

    /**
     * The code length must be at least 4 characters.
     *
     * @test
     * @return void
     */
    public function code_input_not_be_greater_than_20_characters()
    {
        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'code' => Str::random(21)
            ]
        );

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["code"])
            ->assertJson([
                "errors" => [
                    "code" => ["The code may not be greater than 20 characters."]
                ]
            ]);
    }

    /**
     * phone input field is required test.
     *
     * @test
     * @return void
     */
    public function phone_input_is_required()
    {
        //send request
        $response = $this->json('get', route('competition.winner'));

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["phone"])
            ->assertJson([
                "errors" => [
                    "phone" => ["The phone field is required."]
                ]
            ]);
    }

    /**
     * phone input field is string test.
     *
     * @test
     * @return void
     */
    public function phone_input_is_string()
    {
        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'phone' => 123456 // test
            ]
        );

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["phone"])
            ->assertJson([
                "errors" => [
                    "phone" => ["The phone must be a string."]
                ]
            ]);
    }

    /**
     * The phone length must be at least 4 characters.
     *
     * @test
     * @return void
     */
    public function phone_input_not_be_greater_than_15_characters()
    {
        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'phone' => '09120000000000000' //test phone number
            ]
        );

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["phone"])
            ->assertJson([
                "errors" => [
                    "phone" => ["The phone may not be greater than 15 characters."]
                ]
            ]);
    }

    /**
     * The phone format must be iranian phone number format.
     *
     * @test
     * @return void
     */
    public function phone_input_must_be_iranian_mobile_number_format()
    {
        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'phone' => '+136458711536' //test phone number
            ]
        );

        // check tests
        $response->assertStatus(422)
            ->assertJsonValidationErrors(["phone"])
            ->assertJson([
                "errors" => [
                    "phone" => ["The phone format is invalid."]
                ]
            ]);
    }


    /**
     * The input code not in database.
     *
     * @test
     * @return void
     */
    public function input_code_not_in_database()
    {
        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'code' => Str::random(12),
                'phone' => '09123621000' //test phone number
            ]
        );

        // check tests
        $response->assertOk()
            ->assertJson([
                "message" => "You have not won"
            ]);
    }


    /**
     * The customer not won.
     *
     * @test
     * @return void
     */
    public function customer_not_won()
    {
        // create code
        $code = factory(Code::class)->create();

        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'code' => $code->code,
                'phone' => '09123621000' //test phone number
            ]
        );

        // check tests
        $response->assertOk()
            ->assertJson([
                "message" => "You have not won"
            ]);
    }

    /**
     * The customer have won.
     *
     * @test
     * @return void
     */
    public function customer_have_won()
    {
        // create code
        $code = factory(Code::class)->create();
        $customer = factory(Customer::class)->make();
        $code->customers()->create($customer->toArray());

        //send request
        $response = $this->json(
            'get',
            route('competition.winner'),
            [
                'code' => $code->code,
                'phone' => $customer->phone
            ]
        );

        // check tests
        $response->assertOk()
            ->assertJson([
                "message" => 'Congratulations, you have won'
            ]);
    }

}
