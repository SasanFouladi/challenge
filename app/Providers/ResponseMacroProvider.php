<?php

namespace App\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('notFound', function ($message = 'Your request not found'){
            return response()->json([
                'status' => false,
                'message' => $message,
            ], Response::HTTP_NOT_FOUND);
        });

        Response::macro('badRequest', function ($message = 'something wrong occurred'){
            return response()->json([
                'status' => false,
                'message' => $message,
            ], Response::HTTP_BAD_REQUEST);
        });

        Response::macro('unAuthorized', function ($message = 'This action is unauthorized.'){
            return response()->json([
                'status' => false,
                'message' => $message,
            ], Response::HTTP_UNAUTHORIZED);
        });

        Response::macro('created', function ($data, $message = 'Entity was created successfully'){
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => $message,
            ], Response::HTTP_CREATED);
        });

        Response::macro('success', function ($data = [], $message = null){
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => $message,
            ], Response::HTTP_OK);
        });


        Response::macro('withoutData', function ($message){
            return response()->json([
                'status' => true,
                'message' => $message,
            ], Response::HTTP_OK);
        });

        Response::macro('error', function ($message, $code = 500){
            return response()->json([
                'status' => false,
                'errors' => $message,
            ], $code== 0 ? Response::HTTP_INTERNAL_SERVER_ERROR : $code);
        });
    }

}
