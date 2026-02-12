<?php

namespace App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;
use App\Core\Filament\Plugins\Navigation\Builder\FilamentNavigation;
use App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages\Concerns\HasStyles;
use App\Core\Filament\Plugins\Navigation\Builder\Models\Navigation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class EditNavigation extends EditRecord
{
    use HandlesNavigationBuilder;
    use HasStyles;

    public static function getResource(): string
    {
        return FilamentNavigation::get()->getResource();
    }

    public function mount(int|string $record): void
    {

        parent::mount($record); 
    }
    
    public function afterSave(): void
    { 
        Cache::clear();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')->schema([
                    Forms\Components\Placeholder::make('name')
                        ->label(__('filament-navigation::filament-navigation.attributes.name'))
                        ->content(fn (?Navigation $record) =>  $record->name),
                    Forms\Components\ViewField::make('items')
                        ->label(__('filament-navigation::filament-navigation.attributes.items'))
                        ->default([])
                        ->view('filament-navigation::navigation-builder'),
                ])
                    ->columnSpan([
                        12,
                        'lg' => 8,
                    ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('')->schema([
                        Forms\Components\Placeholder::make('handle')
                            ->label(__('filament-navigation::filament-navigation.attributes.handle'))
                            ->content(fn (?Navigation $record) => $record->handle),
                        Forms\Components\View::make('filament-navigation::card-divider')
                            ->visible(static::$showTimestamps),
                        Forms\Components\Placeholder::make('created_at')
                            ->label(__('filament-navigation::filament-navigation.attributes.created_at'))
                            ->visible(static::$showTimestamps)
                            ->content(fn (?Navigation $record) => $record ? $record->created_at->translatedFormat(Table::$defaultDateTimeDisplayFormat) : new HtmlString('&mdash;')),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label(__('filament-navigation::filament-navigation.attributes.updated_at'))
                            ->visible(static::$showTimestamps)
                            ->content(fn (?Navigation $record) => $record ? $record->updated_at->translatedFormat(Table::$defaultDateTimeDisplayFormat) : new HtmlString('&mdash;')),
                    ]),
                ])
                    ->columnSpan([
                        12,
                        'lg' => 4,
                    ]),
            ])
            ->columns(12);
    }
}
