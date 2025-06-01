<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Role;
use Illuminate\Http\Request;

Route::get('/users', fn() =>
response()->json(User::with('roles')->get())
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
);

Route::get('/services', function () {
    $services = \App\Models\Service::all()->map(function ($service) {
        $service->image_path = $service->image_path
            ? asset('storage/' . $service->image_path)
            : null;
        return $service;
    });

    return response()->json($services)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});


Route::get('/bookings', fn() =>
response()->json(Booking::with(['user', 'service', 'takenBy'])->get())
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
);


Route::post('/bookings', function (Request $request) {
    return Booking::create([
        'service_id' => $request->service_id,
        'booking_date' => $request->date,
        'status' => 'очікує',
        'user_id' => null,
    ]);
});


Route::get('/roles', fn() =>
response()->json(Role::all())
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
);
