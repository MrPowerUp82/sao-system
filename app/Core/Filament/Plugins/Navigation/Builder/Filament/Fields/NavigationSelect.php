<?php

namespace App\Core\Filament\Plugins\Navigation\Builder\Filament\Fields;

use Filament\Forms\Components\Select;
use App\Core\Filament\Plugins\Navigation\Builder\Models\Navigation;

class NavigationSelect extends Select
{
    protected string $optionValueProperty = 'id';

    protected function setUp(): void
    {
        parent::setUp();

        $this->options(function (NavigationSelect $component) {
            return Navigation::pluck('name', $component->getOptionValueProperty());
        });
    }

    public function getOptionValueProperty(): string
    {
        return $this->optionValueProperty;
    }
}
