<?php

namespace App\Filament\Resources;

use App\Models\Statistic;
use App\Filament\Resources\StatisticResource\Pages;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class StatisticResource extends Resource
{
    protected static ?string $model = Statistic::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function canViewNavigation(): bool
    {
        return auth()->user()?->hasPermission('statistics') ?? false;
    }
    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key')->label('Ключ')->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('value')->label('Значення')->required()->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->label('Ключ')->sortable()->searchable(),
                TextColumn::make('value')->label('Значення'),
                TextColumn::make('updated_at')->label('Оновлено')->dateTime('d.m.Y H:i'),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatistics::route('/'),
            'create' => Pages\CreateStatistic::route('/create'),
            'edit' => Pages\EditStatistic::route('/{record}/edit'),
        ];
    }
}
