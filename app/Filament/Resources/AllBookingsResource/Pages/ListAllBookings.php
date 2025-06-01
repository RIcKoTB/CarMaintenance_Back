<?php

namespace App\Filament\Resources\AllBookingsResource\Pages;

use App\Filament\Resources\AllBookingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllBookings extends ListRecords
{
    protected static string $resource = AllBookingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
