<?php

namespace App\Http\Controllers\Panel;

use App\Code;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\CodeListRequest;
use App\Http\Requests\Panel\CodeRequest;
use App\Http\Requests\Panel\CustomerListRequest;
use App\Http\Resources\Panel\CodeResource;
use App\Http\Resources\Panel\CustomerResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class CodeController extends Controller
{
    /**
     * Display a listing of the Code.
     *
     * @param CodeListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CodeListRequest $request)
    {
        $searchKey = $request->get('search_key');

        try {
            $codes = Code::query()
                ->when($request->has('search_key'), function ($query) use ($searchKey) {
                    return  $query->searchKey(['name', 'description', 'code'], $searchKey);
                })
                ->orderBy('updated_at', 'DESC')
                ->paginate();

            $data = CodeResource::collection($codes);
        } catch (\Exception $exception) {
            throw new HttpResponseException(
                Response::error('Code list could not be received')
            );
        }

        return $data->response();
    }

    /**
     * Store a newly created code in Database.
     *
     * @param CodeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CodeRequest $request)
    {
        try {
            $code = Code::create(
                $request->merge(['created_by' => Auth::id()])->all()
            );

            Redis::set($code->code, $code->capacity);
            $data = new CodeResource($code);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                Response::error('Code could not be saved')
            );
        }

        return Response::created($data, 'Code created successfully');
    }

    /**
     * Display the specified code.
     *
     * @param Code $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        $data = new CodeResource($code);

        return Response::success($data, 'Code received successfully');

    }

    /**
     * Update the specified code in database.
     *
     * @param CodeRequest $request
     * @param Code $code
     * @return \Illuminate\Http\Response
     */
    public function update(CodeRequest $request, Code $code)
    {
        try {
            Redis::del($code->code);
            $code->update(
                $request->merge(['updated_by' => Auth::id()])->all()
            );
            Redis::set($code->code, $code->capacity);
            $data = new CodeResource($code);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                Response::error('Code could not be updated')
            );
        }

        return Response::success($data, 'Code updated successfully');
    }

    /**
     * Remove the specified code from database.
     *
     * @param Code $code
     * @return void
     */
    public function destroy(Code $code)
    {
        try {
            $code->delete();
            Redis::del($code->code);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                Response::error('Code could not be deleted')
            );
        }

        return Response::withoutData('Code deleted successfully');
    }


    /**
     * Get customers for the specified code from database.
     * @param Code $code
     * @param CustomerListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customers(Code $code, CustomerListRequest $request)
    {
        $searchKey = $request->get('search_key');
        try {
            $customers = $code->customers()
                ->when($request->has('search_key'), function ($query) use ($searchKey) {
                    return  $query->searchKey(['phone'], $searchKey);
                })
                ->orderBy('created_at', 'DESC')
                ->paginate();

            $data = CustomerResource::collection($customers);
        } catch (\Exception $exception) {
            throw new HttpResponseException(
                Response::error('Customer list could not be received')
            );
        }

        return $data->response();
    }
}
