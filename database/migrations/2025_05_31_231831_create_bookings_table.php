<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');    // Користувач
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Послуга
            $table->dateTime('booking_date');                                     // Дата бронювання
            $table->string('status')->default('new');                            // Статус (new, confirmed, done, canceled)
            $table->foreignId('taken_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
