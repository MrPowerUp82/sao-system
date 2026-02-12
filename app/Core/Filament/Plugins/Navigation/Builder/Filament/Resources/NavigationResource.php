<?php

namespace App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources;

use App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages\Concerns\HasStyles; 
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table; 
use App\Core\Filament\Plugins\Navigation\Builder\FilamentNavigation;
use Illuminate\Support\Facades\Cache;

class NavigationResource extends Resource
{
    use HasStyles;
    
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
 
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-navigation::filament-navigation.attributes.name'))
                    ->searchable(),
                TextColumn::make('handle')
                    ->label(__('filament-navigation::filament-navigation.attributes.handle'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('filament-navigation::filament-navigation.attributes.created_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('filament-navigation::filament-navigation.attributes.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->icon(null),
                DeleteAction::make()
                    ->icon(null),
            ])
            ->filters([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => NavigationResource\Pages\ListNavigations::route('/'),
            'create' => NavigationResource\Pages\CreateNavigation::route('/create'),
            'edit' => NavigationResource\Pages\EditNavigation::route('/{record}'),
        ];
    }

    public static function getModel(): string
    {
        return FilamentNavigation::get()->getModel();
    }
}
