<?php

namespace App\Filament\Resources\MyBookingsResource\Pages;

use App\Filament\Resources\MyBookingsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyBookings extends CreateRecord
{
    protected static string $resource = MyBookingsResource::class;
}
