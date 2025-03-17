<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\UserResource;
use App\Services\Response;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    public function profile(Request $request)
    {
        return $this->response
            ->code(200)
            ->message('Profile fetched successfully')
            ->data(new UserResource($request->user()))
            ->json();
    }
}
