<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Response;
class AdminController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        $users = User::all();
        return $this->response
            ->code(200)
            ->message('Users retrieved successfully')
            ->data($users)
            ->json();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|unique:users,mobile_number',
            'password' => 'required|string|min:6',
            'user_type' => 'required|in:user,delivery',
            'location' => 'required|json',
            'image_path' => 'required|image',

        ]);

        if ($validator->fails()) {
            return $this->response
                ->code(400)
                ->message($validator->errors()->first())
                ->json();
        }
        $imagePath = $request->file('image_path')->store('images', 'public');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => bcrypt($request->password),
            'user_type' => $request->user_type,
            'location' => $request->location,
            'is_verified' => true,
            'image_path' => $imagePath,
            'fcm_token' => $request->fcm_token,

        ]);

        return $this->response
            ->code(201)
            ->message('User created successfully')
            ->data($user)
            ->json();
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->response
                ->code(404)
                ->message('User not found')
                ->json();
        }

        return $this->response
            ->code(200)
            ->message('User retrieved successfully')
            ->data($user)
            ->json();
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->response
                ->code(404)
                ->message('User not found')
                ->json();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'mobile_number' => 'sometimes|unique:users,mobile_number,' . $id,
            'password' => 'sometimes|string|min:6',
            'user_type' => 'sometimes|in:user,delivery',
            'location' => 'sometimes|json',
        ]);

        if ($validator->fails()) {
            return $this->response
                ->code(400)
                ->message($validator->errors()->first())
                ->json();
        }

        $user->update($request->all());

        return $this->response
            ->code(200)
            ->message('User updated successfully')
            ->data($user)
            ->json();
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->response
                ->code(404)
                ->message('User not found')
                ->json();
        }

        $user->delete();

        return $this->response
            ->code(200)
            ->message('User deleted successfully')
            ->json();
    }

    public function sendPushNotification()
{
    $serverKey = config('firebase.server_key');

    $users = User::pluck('fcm_token')->filter()->toArray();

    if (empty($users)) {
        return $this->response
            ->code(400)
            ->message('No users with FCM tokens found')
            ->json();
    }

    $notificationData = [
        "registration_ids" => $users,
        "notification" => [
            "title" => "رسالة إدارية",
            "body" => "هذه رسالة إشعار تجريبية من المسؤول",
            "sound" => "default"
        ]
    ];

    $headers = [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData));

    $response = curl_exec($ch);
    curl_close($ch);

    return $this->response
        ->code(200)
        ->message('Push notification sent successfully')
        ->data(json_decode($response))
        ->json();
}
}
