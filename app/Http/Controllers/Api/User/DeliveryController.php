<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\UserResource;
use App\Models\User;
use App\Services\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    public function getNearestDeliveryRepresentatives(Request $request)
    {
        try {
            $userLocation = json_decode($request->user()->location, true);

            if (!$userLocation || !isset($userLocation['lat']) || !isset($userLocation['lng'])) {
                return $this->response
                    ->code(400)
                    ->message('Invalid user location')
                    ->json();
            }

            $nearestDeliveries = User::where('user_type', 'delivery')
                ->whereNotNull('location')
                ->get()
                ->map(function ($delivery) use ($userLocation) {
                    $deliveryLocation = json_decode($delivery->location, true);
                    if (!$deliveryLocation || !isset($deliveryLocation['lat']) || !isset($deliveryLocation['lng'])) {
                        return null;
                    }
                    $distance = $this->calculateDistance(
                        $userLocation['lat'], $userLocation['lng'],
                        $deliveryLocation['lat'], $deliveryLocation['lng']
                    );
                    $delivery->distance = $distance;
                    return $delivery;
                })
                ->filter()
                ->sortBy('distance')
                ->values();

            return $this->response
                ->code(200)
                ->message('Nearest delivery representatives retrieved successfully')
                ->data(UserResource::collection($nearestDeliveries))
                ->json();
        } catch (\Exception $e) {
            return $this->response
                ->code(500)
                ->message('An error occurred while fetching delivery representatives')
                ->json();
        }
    }
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
