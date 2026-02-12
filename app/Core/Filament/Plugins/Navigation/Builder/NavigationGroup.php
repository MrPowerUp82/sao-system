<?php

/**
 * Created by Claudio Campos.
 * User: callcocam@gmail.com, contato@sigasmart.com.br
 * https://www.sigasmart.com.br
 */

namespace App\Core\Filament\Plugins\Navigation\Builder;

class NavigationGroup
{

    protected string|null $navigationGroupId = null;

    protected string|null $navigationGroupLabel = null;

    protected string|null $navigationGroupIcon = null;

    protected int|null $navigationGroupOrder = 0;

    protected array|null $navigationGroupItems = [];

    protected NavigationItem | null $navigationGroupItem = null;

    protected int $navigationGroupChunk = 5;

    protected ?string $pageId = '';

    public static function make()
    {
        return new static();
    }

    public function navigationGroupId($navigationGroupId)
    {
        $this->navigationGroupId = $navigationGroupId;
        return $this;
    }

    public function navigationGroupLabel($navigationGroupLabel)
    {
        $this->navigationGroupLabel = $navigationGroupLabel;
        return $this;
    }

    public function navigationGroupIcon($navigationGroupIcon)
    {
        $this->navigationGroupIcon = $navigationGroupIcon;
        return $this;
    }

    public function navigationGroupOrder($navigationGroupOrder)
    {
        $this->navigationGroupOrder = $navigationGroupOrder;
        return $this;
    }

    public function navigationGroupItems($navigationGroupItems)
    { 
        $this->navigationGroupItems = $navigationGroupItems;

        return $this;
    }

    public function addNavigationGroupItem(NavigationItem|null $navigationGroupItem)
    {
        $this->navigationGroupItem = $navigationGroupItem;
        return $this;
    }

    public function navigationGroupChunk($navigationGroupChunk)
    {
        $this->navigationGroupChunk = $navigationGroupChunk;
        return $this;
    }

    public function pageId($pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }

    public function getNavigationGroupId()
    {
        return $this->navigationGroupId;
    }

    public function getNavigationGroupLabel()
    {
        return $this->navigationGroupLabel;
    }

    public function getNavigationGroupIcon()
    {
        return $this->navigationGroupIcon;
    }

    public function getNavigationGroupOrder()
    {
        return $this->navigationGroupOrder;
    }

    public function getNavigationGroupItems()
    {
        return $this->navigationGroupItems;
    }

    public function hasNavigationGroupItems(): bool
    {
        return is_array($this->navigationGroupItems);
    }

    public function getNavigationGroupItem()
    {
        return $this->navigationGroupItem;
    }

    public function getNavigationGroupChunk()
    {
        return $this->navigationGroupChunk;
    }

    public function getPageId()
    {
        return $this->pageId;
    }

    public function hasNavigationGroupItem(): bool
    {
        return $this->navigationGroupItem instanceof NavigationItem;
    }

    public function getNavigationGroupItemsCount()
    {
        return count($this->navigationGroupItems);
    }

    public function getNavigationGroupItemSlug()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getSlug();
        }
        return null;
    }

    public function getNavigationGroupItemLabel()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getLabel();
        }
        return null;
    }

    public function getNavigationGroupItemIcon()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getIcon();
        }
        return null;
    }

    public function getNavigationGroupItemRoute()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getRouteIndexName();
        }
        return null;
    }

    public function getNavigationGroupItemParams()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getNavigationParams();
        }
        return null;
    }

    public function getNavigationGroupItemLink()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getNavigationLink();
        }
        return null;
    }

    public function getNavigationGroupItemTarget()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getNavigationTarget();
        }
        return null;
    }

    public function getNavigationGroupItemParent()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getGroup();
        }
        return null;
    }

    public function getNavigationGroupItemSort()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getSort();
        }
        return null;
    }

    public function getNavigationGroupItemType()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getNavigationType();
        }
        return null;
    }

    public function getNavigationGroupItemChildren()
    {
        if ($this->navigationGroupItem) {
            return $this->navigationGroupItem->getChildren();
        }
        return null;
    }

}
