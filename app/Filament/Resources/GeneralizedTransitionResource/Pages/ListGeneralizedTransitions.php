<?php

namespace App\Filament\Resources\GeneralizedTransitionResource\Pages;

use App\Filament\Resources\GeneralizedTransitionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralizedTransitions extends ListRecords
{
    protected static string $resource = GeneralizedTransitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
