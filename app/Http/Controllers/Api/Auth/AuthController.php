<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\VerifyRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Models\User;
use App\Services\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    public function register(RegisterRequest $request)
    {
        $imagePath = $request->file('image_path')->store('images', 'public');

        $user = User::create([
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'location' => $request->location,
            'image_path' => $imagePath,
            'verification_code' => rand(1000, 9999),
            'is_verified' => false,
            'user_type' => $request->user_type ?? 'user',
            'fcm_token' => $request->fcm_token,


        ]);

        if (env('TWILIO_SID') && env('TWILIO_AUTH_TOKEN') && env('TWILIO_NUMBER')) {
            try {
                $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
                $twilio->messages->create(
                    $user->mobile_number,
                    ['from' => env('TWILIO_NUMBER'), 'body' => "Your verification code is: {$user->verification_code}"]
                );
            } catch (\Exception $e) {
                return $this->response
                    ->code(500)
                    ->message('Error sending verification code.')
                    ->json();
            }
        } else {
            $user->update(['is_verified' => true]);
        }

        return $this->response
            ->code(201)
            ->message('User registered successfully. Verification code sent or auto-verified.')
            ->json();
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('mobile_number', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->response
                ->code(401)
                ->message('Invalid credentials')
                ->json();
        }

        $user = JWTAuth::user();
        if (!$user->is_verified) {
            return $this->response
                ->code(403)
                ->message('Account not verified. Please verify your mobile number.')
                ->json();
        }

        if (!$user->name || !$user->location || !$user->image_path) {
            return $this->response
                ->code(403)
                ->message('Profile incomplete. Please complete your profile.')
                ->json();
        }

        return $this->response
            ->code(200)
            ->message('Login successful')
            ->data(['token' => $token])
            ->json();
    }

    public function verify(VerifyRequest $request)
    {
        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return $this->response
                ->code(404)
                ->message('User not found')
                ->json();
        }

        if ($user->verification_code !== $request->verification_code) {
            return $this->response
                ->code(400)
                ->message('Invalid verification code')
                ->json();
        }

        $user->update(['is_verified' => true]);

        return $this->response
            ->code(200)
            ->message('Mobile number verified successfully')
            ->json();
    }
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->response
                ->code(200)
                ->message('Logout successful.')
                ->json();
        } catch (\Exception $e) {
            return $this->response
                ->code(500)
                ->message('Failed to logout, please try again.')
                ->json();
        }
    }


}
