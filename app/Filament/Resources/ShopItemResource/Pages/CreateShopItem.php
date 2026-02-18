<?php

namespace App\Filament\Resources\ShopItemResource\Pages;

use App\Filament\Resources\ShopItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShopItem extends CreateRecord
{
    protected static string $resource = ShopItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
