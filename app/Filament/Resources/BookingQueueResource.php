<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Resources\BookingQueueResource\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class BookingQueueResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Нові замовлення';

    public static function canViewNavigation(): bool
    {
        return auth()->check();
    }
    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()->whereNull('taken_by_user_id') // тільки вільні замовлення
            )
            ->columns([
                TextColumn::make('user.name')->label('Клієнт'),
                TextColumn::make('service.title')->label('Послуга'),
                TextColumn::make('booking_date')->label('Дата')->dateTime('d.m.Y H:i'),
            ])
            ->actions([
                Action::make('take')
                    ->label('Взяти замовлення')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Booking $record, $livewire) {
                        $record->taken_by_user_id = auth()->id();
                        $record->save();

                        Notification::make()
                            ->title('Замовлення взято')
                            ->success()
                            ->send();

                        $livewire->dispatch('refresh');
                    })
                    ->visible(fn (Booking $record) => $record->taken_by_user_id === null),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingQueues::route('/'),
        ];
    }
}
