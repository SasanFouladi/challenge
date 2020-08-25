<?php

namespace App\Http\Controllers\Api\V1;

use App\Code;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TakePartRequest;
use App\Http\Requests\Api\V1\WinnerRequest;
use App\Jobs\AddWinnerToDatabase;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class CompetitionController extends Controller
{
    /**
     * take part in competition
     * @param TakePartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function takePart(TakePartRequest $request)
    {
        $code = $request->get('code');
        $phone = $request->get('phone');

        $codeCapacity = Redis::get($code);

        if (is_null($codeCapacity)) {
            throw new HttpResponseException(
                Response::error('Invalid Code')
            );
        }

        if ($codeCapacity <= 0) {
            throw new HttpResponseException(
                Response::error('Code capacity is exhausted')
            );
        }

        // dispatch Job to queue with request data
        AddWinnerToDatabase::dispatch($code, $phone);
        return Response::withoutData('Your request has been saved successfully in Queue');
    }


    /**
     * phone has won
     * @param WinnerRequest $request
     * @return mixed
     */
    public function winner(WinnerRequest $request)
    {
        $code = $request->get('code');
        $phone = $request->get('phone');

        /** @var Code $code */
        $code = Code::where('code', $code)->first();
        if (!$code)
            return Response::withoutData('You have not won');

        /** @var Customer $customer */
        $customer = $code->customers->where('phone', $phone)->first();
        if (!$customer)
            return Response::withoutData('You have not won');

        return Response::withoutData('Congratulations, you have won');
    }
}
