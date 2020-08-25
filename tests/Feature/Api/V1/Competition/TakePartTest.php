<?php

namespace Tests\Feature\Api\V1\Competition;

use App\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Tests\TestCase;

class TakePartTest extends TestCase
{
    use RefreshDatabase;
    /**
     * code input field is required test.
     *
     * @test
     * @return void
     */
    public function code_input_is_required()
    {
        //send request
        $response = $this->json('post', route('competition.take.part'));

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
            'post',
            route('competition.take.part'),
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
            'post',
            route('competition.take.part'),
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
            'post',
            route('competition.take.part'),
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
        $response = $this->json('post', route('competition.take.part'));

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
            'post',
            route('competition.take.part'),
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
            'post',
            route('competition.take.part'),
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
            'post',
            route('competition.take.part'),
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
     * check invalid code.
     *
     * @test
     * @return void
     */
    public function invalid_code_check_with_redis()
    {
        // create code
        $code = factory(Code::class)->create();

        // send request
        $response = $this->json(
            'post',
            route('competition.take.part'),
            [
                'code' => $code->code,
                'phone' => '09197940000'
            ]
        );

        // check tests
        $response->assertStatus(500)
            ->assertJson([
                "errors" => "Invalid Code"
            ]);
    }

    /**
     * Code capacity is exhausted.
     *
     * @test
     * @return void
     */
    public function code_capacity_is_exhausted_check_with_redis()
    {
        // create code
        $code = factory(Code::class)->create();

        // set capacity = 0 in redis
        Redis::set($code->code, 0);

        // send request
        $response = $this->json(
            'post',
            route('competition.take.part'),
            [
                'code' => $code->code,
                'phone' => '09197940000'
            ]
        );

        // check tests
        $response->assertStatus(500)
            ->assertJson([
                "errors" => "Code capacity is exhausted"
            ]);
    }

    /**
     * Code capacity is exhausted.
     *
     * @test
     * @return void
     */
    public function dispatch_request_to_queue()
    {
        Queue::fake();

        // create code
        $code = factory(Code::class)->create();

        // set capacity = 0 in redis
        Redis::set($code->code, 200);

        // send request
        $response = $this->json(
            'post',
            route('competition.take.part'),
            [
                'code' => $code->code,
                'phone' => '09197940000'
            ]
        );

        // check tests
        $response->assertOk()
            ->assertJson([
                "message" => "Your request has been saved successfully in Queue"
            ]);
    }
}
