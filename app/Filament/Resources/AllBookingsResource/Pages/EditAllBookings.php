<?php

namespace App\Filament\Resources\AllBookingsResource\Pages;

use App\Filament\Resources\AllBookingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllBookings extends EditRecord
{
    protected static string $resource = AllBookingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
