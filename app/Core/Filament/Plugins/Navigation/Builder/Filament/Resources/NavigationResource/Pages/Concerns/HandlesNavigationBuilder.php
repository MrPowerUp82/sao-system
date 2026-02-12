<?php

namespace App\Core\Filament\Plugins\Navigation\Builder\Filament\Resources\NavigationResource\Pages\Concerns;

use App\Core\Filament\Plugins\Navigation\Builder\BuilderNavigation;
use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Core\Filament\Plugins\Navigation\Builder\FilamentNavigation;
use App\Core\Filament\Plugins\Navigation\Builder\NavigationItem;
use App\Models\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Cache;

trait HandlesNavigationBuilder
{
    public $mountedItem;

    public $mountedItemData = [];

    public $mountedChildTarget;

    public function sortNavigation(string $targetStatePath, array $targetItemsStatePaths)
    {
        $items = [];

        foreach ($targetItemsStatePaths as $targetItemStatePath) {
            $item = data_get($this, $targetItemStatePath);
            $uuid = Str::afterLast($targetItemStatePath, '.');

            $items[$uuid] = $item;
        }

        data_set($this, $targetStatePath, $items);
    }

    public function addChild(string $statePath)
    {
        $this->mountedChildTarget = $statePath;

        $this->mountAction('item');
    }

    public function removeItem(string $statePath)
    {
        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($this, $parentPath);

        data_set($this, $parentPath, Arr::except($parent, $uuid));
    }

    public function editItem(string $statePath)
    {
        $this->mountedItem = $statePath;
        $this->mountedItemData = Arr::except(data_get($this, $statePath), 'children');

        $this->mountAction('item');
    }

    public function createItem()
    {
        $this->mountedItem = null;
        $this->mountedItemData = [];
        $this->mountedActionData = [];

        $this->mountAction('item');
    }

    protected function getSelectPageOptions()
    {

        return Cache::remember('pages-id', 60 * 60 * 24, function () {
            $parents = BuilderNavigation::make()->loadPages()->getDefaultsNavigations();
            $data = [];

            collect($parents)->map(function (NavigationItem $parent) use (&$data) {
                $data[$parent->getPageId()] = $parent->getPageId();
            })->toArray();

            collect($parents)->map(function (NavigationItem $parent) use (&$data) {
                $data[$parent->getGroup()] = $parent->getGroup();
            })->toArray();

            Page::query()->get()->map(function (Page $page) use (&$data) {
                $data[$page->slug] = $page->name;
            });

            $data = array_filter($data);

            return $data;
        });
    }

    protected function getSelectPagePropertyOptions($parent)
    {

        //vamos buscar as propriedades do modelo 

        $parents =  Cache::remember(sprintf('pages-id-%s', $parent), 60 * 60 * 24, function () use ($parent) {

            return BuilderNavigation::make()->getNavigationsByPageId($parent);
        });
        if ($parents) {
            $vars = get_object_vars($parents);
            $vars = array_keys($vars);
            $vars = array_combine($vars, $vars);
            return $vars;
        }
        return [];
    }

    protected function getSelectPagePropertyValue($page, $parent)
    {
        //vamos buscar as propriedades do modelo
        $parents =  Cache::remember(sprintf('pages-id-%s', $page), 60 * 60 * 24, function () use ($page) {
            return BuilderNavigation::make()->getNavigationsByPageId($page);
        });
        if ($parents) {
            $vars = get_object_vars($parents);
            return data_get($vars, $parent);
        }
        return null;
    }
    protected function getSelectPagePropertyValues($page)
    {
        //vamos buscar as propriedades do modelo
        // $parents =  Cache::remember(sprintf('pages-id-values-%s', $page), 60 * 60 * 24, function () use ($page) {
        //     return BuilderNavigation::make()->getNavigationsByPageId($page);
        // });
        $parents = BuilderNavigation::make()->loadPages()->getNavigationsByPageId($page);
        if (!$parents) {
            $parents = BuilderNavigation::make()->getNavigationsByGroup($page);
            if (!$parents) {
                $parents = Page::where('slug', $page)->first();
                if ($parents) {
                    $parents = $parents->toArray();
                    $parents['navigationLabel'] = data_get($parents, 'name');
                    $parents['navigationIcon'] = data_get($parents, 'icon');
                    $parents['navigationOrder'] = data_get($parents, 'order', 0);
                    return $parents;
                }
            }
        }

        if ($parents) {
            $vars = get_object_vars($parents);
            return $vars;
        }
        return [];
    }

    protected function getActions(): array
    {
        return [
            Action::make('item')
                ->mountUsing(function (ComponentContainer $form) {
                    if (!$this->mountedItem) {
                        return;
                    }
                    $form->fill($this->mountedItemData);
                })
                ->view('filament-navigation::hidden-action')
                ->slideOver()
                ->form([
                    Select::make('name')
                        ->label(__('filament-navigation::filament-navigation.items-modal.name'))
                        ->options(function () {
                            return $this->getSelectPageOptions();
                        })
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set): void {
                            if (!$state) {
                                return;
                            }
                            $properties = $this->getSelectPagePropertyValues($state);
                            $set('icon', data_get($properties, 'navigationIcon'));
                            $set('slug', data_get($properties, 'slug'));
                            $set('route', data_get($properties, 'navigationRoute'));
                            $set('label', data_get($properties, 'navigationLabel'));
                            $set('type', data_get($properties, 'type'));
                            $set('order', data_get($properties, 'navigationOrder'));
                            $set('params', data_get($properties, 'navigationParams', []));

                            $set('data', [
                                'url' => data_get($properties, 'navigationLink'),
                                'target' => data_get($properties, 'navigationTarget'),
                            ]);
                        }),
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            TextInput::make('subtitle')
                                ->label(__('filament-navigation::filament-navigation.items-modal.subtitle')),
                            TextInput::make('label')
                                ->label(__('filament-navigation::filament-navigation.items-modal.label'))
                                ->required(),
                        ]),
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            TextInput::make('slug')
                                ->label(__('filament-navigation::filament-navigation.items-modal.slug'))
                                ->required(),
                            TextInput::make('route')
                                ->label(__('filament-navigation::filament-navigation.items-modal.route'))
                        ]),
                    Grid::make()
                        ->columns(3)
                        ->schema([
                            TextInput::make('icon')
                                ->label(__('filament-navigation::filament-navigation.items-modal.icon'))
                                ->columnSpan(2),
                            TextInput::make('chunk')
                                ->label(__('filament-navigation::filament-navigation.items-modal.chunk'))
                                ->columnSpan(1),
                        ]),
                    TagsInput::make('position')
                        ->label(__('filament-navigation::filament-navigation.items-modal.position'))
                        ->suggestions(['top', 'bottom', 'left', 'right']),
                    Repeater::make('params')
                        ->label(__('filament-navigation::filament-navigation.items-modal.params'))
                        ->collapsed()
                        ->itemLabel(fn ($state) => sprintf('%s: %s', data_get($state, 'key'), data_get($state, 'value')))
                        ->schema([
                            TextInput::make('key')
                                ->label(__('filament-navigation::filament-navigation.items-modal.param_key'))
                                ->required(),
                            TextInput::make('value')
                                ->label(__('filament-navigation::filament-navigation.items-modal.param_value'))
                                ->required(),
                        ])->columns(2),
                    Select::make('type')
                        ->label(__('filament-navigation::filament-navigation.items-modal.type'))
                        ->options(function () {
                            $types = FilamentNavigation::get()->getItemTypes();

                            return array_combine(array_keys($types), Arr::pluck($types, 'name'));
                        })
                        ->afterStateUpdated(function ($state, Select $component): void {
                            if (!$state) {
                                return;
                            }

                            // NOTE: This chunk of code is a workaround for Livewire not letting
                            //       you entangle to non-existent array keys, which wire:model
                            //       would normally let you do.
                            $component
                                ->getContainer()
                                ->getComponent(fn (Component $component) => $component instanceof Group)
                                ->getChildComponentContainer()
                                ->fill();
                        })
                        ->reactive(),
                    Group::make()
                        ->statePath('data')
                        ->whenTruthy('type')
                        ->schema(function (Get $get) {
                            $type = $get('type');

                            return FilamentNavigation::get()->getItemTypes()[$type]['fields'] ?? [];
                        }),
                ])
                ->modalWidth('lg')
                ->action(function (array $data) {
                    if ($this->mountedItem) {
                        data_set($this, $this->mountedItem, array_merge(data_get($this, $this->mountedItem), $data));

                        $this->mountedItem = null;
                        $this->mountedItemData = [];
                    } elseif ($this->mountedChildTarget) {
                        $children = data_get($this, $this->mountedChildTarget . '.children', []);

                        $children[(string) Str::uuid()] = [
                            ...$data,
                            ...['children' => []],
                        ];

                        data_set($this, $this->mountedChildTarget . '.children', $children);

                        $this->mountedChildTarget = null;
                    } else {
                        $this->data['items'][(string) Str::uuid()] = [
                            ...$data,
                            ...['children' => []],
                        ];
                    }

                    $this->mountedActionData = [];
                })
                ->modalSubmitActionLabel(__('filament-navigation::filament-navigation.items-modal.btn'))
                ->label(__('filament-navigation::filament-navigation.items-modal.title')),
        ];
    }
}
