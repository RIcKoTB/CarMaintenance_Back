<?php

namespace App\Filament\Resources;

use App\Models\Service;
use App\Filament\Resources\ServiceResource\Pages;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function canViewNavigation(): bool
    {
        return auth()->user()?->hasPermission('services') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Назва послуги')
                    ->required()
                    ->maxLength(255),

                TextInput::make('description')
                    ->label('Опис')
                    ->maxLength(1000),

                TextInput::make('price')
                    ->label('Ціна')
                    ->numeric()
                    ->required(),

                FileUpload::make('image_path')
                    ->label('Зображення')
                    ->image()
                    ->directory('services') // зберігатиметься у storage/app/public/services
                    ->imagePreviewHeight('150')
                    ->downloadable()
                    ->preserveFilenames()
                    ->openable()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Зображення')
                    ->disk('public') // важливо!
                    ->visibility('public')
                    ->circular()
                    ->height(60)
                    ->width(60),

                TextColumn::make('title')
                    ->label('Назва')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Ціна')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Додано')
                    ->date('d.m.Y'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
