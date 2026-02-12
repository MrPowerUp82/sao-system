<?php

/**
 * Created by Claudio Campos.
 * User: callcocam@gmail.com, contato@sigasmart.com.br
 * https://www.sigasmart.com.br
 */

namespace App\Core\Filament\Plugins\Navigation\Builder;

use Symfony\Component\Finder\Finder;

class BuilderNavigation
{

    public $pages = [];

    public $groups = [];

    public static function make()
    {
        return new static();
    }

    //Lemos as páginas e adicionamos ao array
    //todos os itens da pagina ex label, icon, slug, route, params, target, parent, order, active, badge, groupIcon, groupLabel, groupId, groupOrder, viewMenu, groupChunk, link, pageId, isVisble
    public function loadPages()
    {
        $pages = [];
        $loader = Finder::create()->in(app_path('Livewire/Site'))->name('*.php')->files();
        foreach ($loader as $page) {
            $namespace = str($page->getPath())->afterLast('app')->before('.php')->replace('/', '\\')->__toString();
            $namespace = sprintf('App%s', $namespace);
            $name = $page->getFilenameWithoutExtension();
            $class = sprintf('%s\%s', $namespace, $name);
            if (class_exists($class)) {
                $page = str($class)->lower()
                    ->afterLast('site\\')->replace('\\', '-')->__toString();
                $pages[] = $this->build(app($class), $page);
            }
        }
        $this->pages = $pages;

        return $this;
    }

    //Construimos o item de navegação
    public function build($class, $page)
    {
        return NavigationItem::make()->navigationLabel($class->getNavigationLabel())
            ->navigationIcon($class->getNavigationIcon())
            ->slug($class->getSlug())
            ->navigationRoute($class->getRouteIndexName())
            ->navigationParams($class->getNavigationParams())
            ->navigationTarget($class->getNavigationTarget())
            ->navigationParent($class->getNavigationParent())
            ->navigationOrder($class->getNavigationOrder())
            ->navigationActive($class->getNavigationActive())
            ->navigationBadge($class->getNavigationBadge())
            ->navigationGroupIcon($class->getNavigationGroupIcon())
            ->navigationGroupLabel($class->getNavigationGroupLabel())
            ->navigationGroupId($class->getNavigationGroupId())
            ->navigationGroupOrder($class->getNavigationGroupOrder())
            ->viewMenu($class->getViewMenu())
            ->navigationGroupChunk($class->getNavigationGroupChunk())
            ->navigationLink($class->getNavigationLink())
            ->pageId($page)
            ->isVisble($class->getVisible());
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
            'children' => $childrens
        ];
    }

    public function navigationGroups()
    {
        $navigationGroups = [];
        collect($this->pages)
            ->sortBy(fn (NavigationItem $item): int => $item->getSort())
            ->map(function (NavigationItem $item) use (&$navigationGroups) {
                $group = !empty($item->getGroup()) ? $item->getGroup() : $item->getSlug();
                if (!isset($navigationGroups[$group])) { 
                    $navigationGroups[$group] = NavigationGroup::make()
                        ->pageId($item->getPageId())
                        ->navigationGroupId($group)
                        ->navigationGroupLabel($item->getNavigationGroupLabel())
                        ->navigationGroupId($item->getSlug())
                        ->navigationGroupOrder($item->getGroupSort())
                        ->navigationGroupChunk($item->getChunk());
                }
            }) ;

        $this->groups = $navigationGroups;

        return $this;
    }

    public function getNavigations()
    { 
        if ($this->groups) {
            foreach ($this->groups as $name => $group) {
              
                $group
                     ->navigationGroupId($name)
                    ->addNavigationGroupItem(collect($this->pages)
                        ->sortBy(fn (NavigationItem $item): int => $item->getSort())
                        ->filter(fn (NavigationItem $item) => $item->getSlug() ==  $name)
                        ->first())
                    ->navigationGroupItems(collect($this->pages)
                        ->sortBy(fn (NavigationItem $item): int => $item->getSort())
                        ->filter(fn (NavigationItem $item) => $item->getGroup() ==  $name)
                        ->chunk($group->getNavigationGroupChunk())
                        ->toArray());
            }
        }  
        return $this->groups;
    }

    public function getNavigationsByGroup($group)
    {
        return collect($this->pages)
            ->sortBy(fn (NavigationItem $item): int => $item->getSort())
            ->filter(fn (NavigationItem $item) => $item->getGroup() == $group)
            ->toArray();
    }

    public function getNavigationsBySlug($slug)
    {
        return collect($this->pages)
            ->sortBy(fn (NavigationItem $item): int => $item->getSort())
            ->filter(fn (NavigationItem $item) => $item->getSlug() == $slug)
            ->toArray();
    }

    public function getNavigationsByPageId($parent)
    {
        return collect($this->pages)
            ->sortBy(fn (NavigationItem $item): int => $item->getSort())
            ->filter(fn (NavigationItem $item) => $item->getPageId() == $parent)
            ->first();
    }

    public function getDefaultsNavigations()
    {
        return collect($this->pages)
            ->sortBy(fn (NavigationItem $item): int => $item->getSort())
            ->toArray();
    }

}
