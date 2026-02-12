<?php

/**
 * Created by Claudio Campos.
 * User: callcocam@gmail.com, contato@sigasmart.com.br
 * https://www.sigasmart.com.br
 */

namespace App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages\Concerns;

use App\Core\Filament\Plugins\Navigation\Builder\BuilderNavigation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait HasStyles
{
 
    protected static bool $showTimestamps = true;

    private static ?string $workNavigationLabel = null;

    private static ?string $workPluralLabel = null;

    private static ?string $workLabel = null;

    public static function disableTimestamps(bool $condition = true): void
    {
        static::$showTimestamps = !$condition;
    }

    public static function navigationLabel(?string $string): void
    {
        self::$workNavigationLabel = $string;
    }

    public static function pluralLabel(?string $string): void
    {
        self::$workPluralLabel = $string;
    }

    public static function label(?string $string): void
    {
        self::$workLabel = $string;
    }

    public static function getNavigationLabel(): string
    {
        return self::$workNavigationLabel ?? parent::getNavigationLabel();
    }

    public static function getModelLabel(): string
    {
        return self::$workLabel ?? parent::getModelLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return self::$workPluralLabel ?? parent::getPluralModelLabel();
    }

    public function getItem($item, $name, $childrens = [])
    {
        return [
            'name' => $name,
            'label' => $item->getLabel(),
            'parent' => $item->getParentItem(),
            'slug' => $item->getSlug(),
            'icon' => $item->getIcon(),
            'route' => $item->getRouteIndexName(),
            'params' => $item->getNavigationParams(),
            'data' => [
                'url' => $item->getNavigationLink(),
                'target' => $item->getNavigationTarget(),
            ],
            'type' => $item->getNavigationType(),
            'order' => $item->getSort(),
            'chunk' => $item->getChunk(),
            'children' => $childrens
        ];
    }

    public function getNavigationOptions(): array
    {
        $navigations = [];

        if ($originalNavigations = BuilderNavigation::make()->loadPages()->navigationGroups()->getNavigations()) {
            foreach ($originalNavigations as $navigation) {

                if ($navigation->hasNavigationGroupItem()) {
                    $item =  $navigation->getNavigationGroupItem();
                    $uuid = Str::uuid()->toString();
                    $navigations[$uuid] =  $this->getItem($item, $item->getPageId());
                } else {
                    if ($navigation->hasNavigationGroupItems()) {
                        $childrens = [];
                        foreach ($navigation->getNavigationGroupItems() as $navigationGroupItems) {
                            foreach ($navigationGroupItems as $navigationGroupItem) {
                                $uuid = Str::uuid()->toString();
                                $childrens[$uuid] = $this->getItem($navigationGroupItem, $navigationGroupItem->getPageId());
                            }
                        }
                        $uuid = Str::uuid()->toString();
                        $navigations[$uuid] =  [
                            'name' => $navigation->getNavigationGroupId(),
                            'label' => $navigation->getNavigationGroupLabel(),
                            'parent' => $navigation->getNavigationGroupId(),
                            'slug' => $navigation->getNavigationGroupId(),
                            'icon' => $navigation->getNavigationGroupIcon(),
                            'route' => null,
                            'params' => null,
                            'chunk' => $navigation->getNavigationGroupChunk(),
                            'order' => $navigation->getNavigationGroupOrder(),
                            'children' => $childrens
                        ];
                    }
                }
            }
        }
        return $navigations;
    }
}
