<?php

namespace App\Filament\Resources\MyBookingsResource\Pages;

use App\Filament\Resources\MyBookingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyBookings extends EditRecord
{
    protected static string $resource = MyBookingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
