<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Service;
use App\Models\Booking;
use App\Models\User;

Route::get('/services', function () {
    return response()->json(Service::all());
});

Route::post('/bookings', function (Request $request) {
    dd($request->all());
});



Route::post('/bookings', function (Request $request) {
    // Знайти або створити користувача за ім'ям + email
    $user = User::firstOrCreate(
        ['email' => $request->email],
        [
            'name' => $request->name,
            'password' => bcrypt('default_password'), // ← обов’язкове поле
        ]
    );

    // Створити бронювання
    $booking = Booking::create([
        'user_id' => $user->id,
        'service_id' => $request->service_id,
        'booking_date' => $request->booking_date,
        'status' => 'очікує',
        'taken_by_user_id' => null,
    ]);

    return response()->json($booking, 201);
});

Route::get('/calendar-events', function () {
    return Booking::with(['user', 'service'])
        ->whereNotNull('booking_date')
        ->get()
        ->map(function ($booking) {
            return [
                'title' => $booking->service->title . ' - ' . $booking->user->name,
                'start' => $booking->booking_date,
                'end' => \Carbon\Carbon::parse($booking->booking_date)->addHour(), // умовно 1 година
                'color' => match ($booking->status) {
                    'new' => '#ffc107',
                    'confirmed' => '#28a745',
                    'done' => '#6c757d',
                    'canceled' => '#dc3545',
                    default => '#007bff',
                }
            ];
        });
});
