<?php

namespace App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages;

use App\Core\Filament\Plugins\Navigation\Builder\BuilderNavigation;
use Filament\Resources\Pages\CreateRecord;
use App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;
use App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages\Concerns\HasStyles;
use App\Core\Filament\Plugins\Navigation\Builder\FilamentNavigation;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class CreateNavigation extends CreateRecord
{
    use HandlesNavigationBuilder;
    use HasStyles;

    public static function getResource(): string
    {
        return FilamentNavigation::get()->getResource();
    }


    // public function mount(): void
    // {
    //     //  dd($this->getNavigationOptions());
    // }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('items')
                    ->default($this->getNavigationOptions()),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('handle'),
                Forms\Components\Select::make('tenant_id')
                    ->label(__('filament-navigation::filament-navigation.attributes.tenant_id'))
                    ->options(function () {
                        return \App\Models\Tenant::all()->pluck('name', 'id');
                    })->reactive()
                    ->debounce()
                    ->unique()
                    ->afterStateUpdated(function (?string $state, Set $set) {
                        if (!$state) {
                            return;
                        }
                        $name = Tenant::find($state)->name;
                        $set('name', $name);
                        $set('handle', Str::slug($name));
                    })
                    ->required(),
            ])
            ->columns(1);
    }
}
