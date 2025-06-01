<?php

namespace App\Filament\Resources\BookingQueueResource\Pages;

use App\Filament\Resources\BookingQueueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingQueues extends ListRecords
{
    protected static string $resource = BookingQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
