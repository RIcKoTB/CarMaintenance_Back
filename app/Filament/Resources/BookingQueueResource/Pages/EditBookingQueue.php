<?php

namespace App\Filament\Resources\BookingQueueResource\Pages;

use App\Filament\Resources\BookingQueueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookingQueue extends EditRecord
{
    protected static string $resource = BookingQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
