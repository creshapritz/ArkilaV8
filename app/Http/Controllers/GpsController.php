<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpsData;
use App\Models\Car;

class GpsController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'car_id' => 'required|integer',
            'client_id' => 'required|integer',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $gpsData = new GpsData();
        $gpsData->car_id = $validatedData['car_id'];
        $gpsData->client_id = $validatedData['client_id'];
        $gpsData->latitude = $validatedData['latitude'];
        $gpsData->longitude = $validatedData['longitude'];
        $gpsData->save();

        return response()->json(['message' => 'GPS data updated successfully'], 200);
    }


   
    public function index()
    {
        $gpsData = GpsData::select('car_id', 'client_id', 'latitude', 'longitude')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('car_id') 
            ->values(); // Reset keys

        return response()->json($gpsData);
    }

    public function show($car_id, $client_id)
{
    $gpsData = GpsData::where('car_id', $car_id)
        ->where('client_id', $client_id)
        ->latest()
        ->first();

    if ($gpsData) {
        return response()->json([
            'latitude' => $gpsData->latitude,
            'longitude' => $gpsData->longitude
        ]);
    }

    return response()->json([
        'latitude' => null,
        'longitude' => null
    ]);
}

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'car_id' => 'required|integer',
            'client_id' => 'required|integer'
        ]);

        GpsData::updateOrCreate(
            ['client_id' => $validated['client_id'], 'car_id' => $validated['car_id']],
            [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'updated_at' => now()
            ]
        );

        return response()->json(['message' => 'Location updated successfully']);
    }

    public function update(Request $request)
    {
       
        \DB::table('client_gps_logs')->insert([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_at' => now(),
        ]);

        return response()->json(['status' => 'success']);
    }
    public function getCarLocation($carId)
{
    $car = Car::find($carId);
    
    if ($car && $car->latitude && $car->longitude) {
        return response()->json([
            'latitude' => $car->latitude,
            'longitude' => $car->longitude
        ]);
    }

    return response()->json(['error' => 'Location not found'], 404);
}




}
