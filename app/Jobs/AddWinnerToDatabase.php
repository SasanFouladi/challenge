<?php

namespace App\Jobs;

use App\Code;
use App\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class AddWinnerToDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code, $phone;

    /**
     * Create a new job instance.
     *
     * @param $code
     * @param $phone
     */
    public function __construct($code, $phone)
    {
        $this->code = $code;
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Redis::get($this->code) <= 0)
            return;

        /** @var Code $code */
        $code = Code::where('code', $this->code)->first();
        if (!$code)
            return;

        if (!$code->enable)
            return;

        /** @var Customer $customer */
        $customer = $code->customers->where('phone', $this->phone)->first();
        if ($customer)
            return;


        $code->customers()->create([
            'phone' => $this->phone
        ]);
        Redis::decrby($this->code, 1);
    }
}
