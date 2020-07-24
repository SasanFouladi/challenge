<?php

use App\Code;
use App\Customer;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CodeCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Code::class, 20)->create()->each(function ($code) {
            /** @var Code $code */
            $code->customers()->createMany(factory(Customer::class, 5)->make()->toArray());
        });

        $this->command->info('codes and customers created successfully');
    }
}
