<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'name' => 'Admin',
            'email' => 'admin@yahoo.com',
            'email_verified_at' => Carbon::now()->timestamp,
            'password' => bcrypt('123456789')
        ];

        User::query()->create($user);

        $this->command->info('User created successfully');
        $this->command->info("email:admin@yahoo.com, password:123456789");
    }
}
