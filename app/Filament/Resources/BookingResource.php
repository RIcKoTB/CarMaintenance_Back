<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use App\Filament\Resources\BookingResource\Pages;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function canViewNavigation(): bool
    {
        return auth()->user()?->hasPermission('bookings') ?? false;
    }
    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->relationship('user', 'name')->label('Клієнт')->required(),
                Select::make('service_id')->relationship('service', 'title')->label('Послуга')->required(),
                DateTimePicker::make('booking_date')->label('Дата')->required(),
                TextInput::make('status')->label('Статус')->default('new')->required()->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Клієнт'),
                TextColumn::make('service.title')->label('Послуга'),
                TextColumn::make('booking_date')->label('Дата')->dateTime('d.m.Y H:i'),
                TextColumn::make('status')->label('Статус')->badge(),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
