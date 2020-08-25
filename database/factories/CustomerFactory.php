<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Code;
use App\Customer;
use Faker\Generator as Faker;
$phoneNumbers = [
    '09125000000','09125000001','09125000002','09125000003','09125000004','09125000005','09125000006',
    '09125000007','09125000008','09125000009','09125000010', '09125000011','09125000012','09125000013',
    '09125000014','09125000015','09125000016','09125000017','09125000018','09125000019','09125000020',
];
$factory->define(Customer::class, function (Faker $faker) use ($phoneNumbers) {
    $phoneNumber = $phoneNumbers[array_rand($phoneNumbers)];
    return [
        'phone' => $phoneNumber,
    ];
});
