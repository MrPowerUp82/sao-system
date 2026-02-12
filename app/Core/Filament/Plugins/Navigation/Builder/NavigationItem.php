<?php

/**
 * Created by Claudio Campos.
 * User: callcocam@gmail.com, contato@sigasmart.com.br
 * https://www.sigasmart.com.br
 */

namespace App\Core\Filament\Plugins\Navigation\Builder;

class NavigationItem
{

    public  null|string $navigationLabel;

    public  null|string $navigationIcon;

    public  null|string $slug;

    public null|string $navigationRoute;

    public  null|array $navigationParams;

    public  null|string $navigationTarget;

    public  null|string $navigationParent;

    public  null|int|string $navigationOrder;

    protected ?bool $navigationActive;

    protected $navigationBadge;

    public  ?string $navigationGroupIcon;

    public  ?string $navigationGroupLabel;

    protected ?string $navigationGroupId;

    public  ?int $navigationGroupOrder = 0;

    protected  ?string $viewMenu;

    public  ?int $navigationGroupChunk = 5;

    public ?string $navigationLink = null;

    protected ?string $pageId = null;

    protected $isVisble = true;

    public static function make()
    {
        return new static();
    }

    /**
     * @param $navigationLabel
     * @return $this
     */
    public function navigationLabel($navigationLabel)
    {
        $this->navigationLabel = $navigationLabel;
        return $this;
    }

    /**
     * @param $navigationIcon
     * @return $this
     */
    public function navigationIcon($navigationIcon)
    {
        $this->navigationIcon = $navigationIcon;
        return $this;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function slug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param $navigationRoute
     * @return $this
     */
    public function navigationRoute($navigationRoute)
    {
        $this->navigationRoute = $navigationRoute;
        return $this;
    }

    /**
     * @param $navigationLink
     * @return $this
     */
    public function navigationLink($navigationLink)
    {
        $this->navigationLink = $navigationLink;
        return $this;
    }

    /**
     * @param $navigationParams
     * @return $this
     */
    public function navigationParams($navigationParams)
    {
        $this->navigationParams = $navigationParams;
        return $this;
    }

    /**
     * @param $navigationTarget
     * @return $this
     */
    public function navigationTarget($navigationTarget)
    {
        $this->navigationTarget = $navigationTarget;
        return $this;
    }

    /**
     * @param $navigationParent
     * @return $this
     */
    public function navigationParent($navigationParent)
    {
        $this->navigationParent = $navigationParent;
        return $this;
    }

    /**
     * @param $navigationOrder
     * @return $this
     */
    public function navigationOrder($navigationOrder)
    {
        $this->navigationOrder = $navigationOrder;
        return $this;
    }

    /**
     * @param $navigationActive
     * @return $this
     */
    public function navigationActive($navigationActive)
    {
        $this->navigationActive = $navigationActive;
        return $this;
    }

    /**
     * @param $navigationBadge
     * @return $this
     */
    public function navigationBadge($navigationBadge)
    {
        $this->navigationBadge = $navigationBadge;
        return $this;
    }

    /**
     * @param $navigationGroupIcon
     * @return $this
     */
    public function navigationGroupIcon($navigationGroupIcon)
    {
        $this->navigationGroupIcon = $navigationGroupIcon;
        return $this;
    }

    /**
     * @param $navigationGroupLabel
     * @return $this
     */
    public function navigationGroupLabel($navigationGroupLabel)
    {
        $this->navigationGroupLabel = $navigationGroupLabel;
        return $this;
    }

    /**
     * @param $navigationGroupId
     * @return $this
     */
    public function navigationGroupId($navigationGroupId)
    {
        $this->navigationGroupId = $navigationGroupId;
        return $this;
    }

    /**
     * @param $navigationGroupOrder
     * @return $this
     */
    public function navigationGroupOrder($navigationGroupOrder)
    {
        $this->navigationGroupOrder = $navigationGroupOrder;
        return $this;
    }

    /**
     * @param $viewMenu
     * @return $this
     */
    public function viewMenu($viewMenu)
    {
        $this->viewMenu = $viewMenu;
        return $this;
    }

    /**
     * @param $navigationGroupChunk
     * @return $this
     */
    public function navigationGroupChunk($navigationGroupChunk)
    {
        $this->navigationGroupChunk = $navigationGroupChunk;
        return $this;
    }

    /**
     * @param $pageId
     * @return $this
     */
    public function pageId($pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }

    /**
     * @param $isVisble
     * @return $this
     */
    public function isVisble($isVisble)
    {
        $this->isVisble = $isVisble;

        return $this;
    }


    public function getPageId()
    {
        return $this->pageId;
    }

    public function getGroup()
    {
        return $this->navigationParent;
    }

    public function getParentItem()
    {
        return $this->navigationParent;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getLabel()
    {
        return $this->navigationLabel;
    }

    public function getIcon()
    {
        return $this->navigationIcon;
    }

    public function getRouteIndexName()
    {
        return $this->navigationRoute;
    }

    public function getNavigationParams()
    {
        return $this->navigationParams;
    }

    public function getNavigationLink()
    {
        return $this->navigationLink;
    }

    public function getNavigationTarget()
    {
        return $this->navigationTarget;
    }

    public function getNavigationType()
    {
        return 'link';
    } 

    public function getSort()
    {
        return $this->navigationOrder;
    }

    public function getChunk()
    {
        return $this->navigationGroupChunk;
    }

    public function getGroupSort()
    {
        return $this->navigationGroupOrder;
    }

    public function getNavigationGroupLabel()
    {
        return $this->navigationGroupLabel;
    }

    public function toArray()
    {
        return [
            'navigationLabel' => $this->navigationLabel,
            'navigationIcon' => $this->navigationIcon,
            'slug' => $this->slug,
            'navigationRoute' => $this->navigationRoute,
            'navigationParams' => $this->navigationParams,
            'navigationTarget' => $this->navigationTarget,
            'navigationParent' => $this->navigationParent,
            'navigationOrder' => $this->navigationOrder,
            'navigationActive' => $this->navigationActive,
            'navigationBadge' => $this->navigationBadge,
            'navigationGroupIcon' => $this->navigationGroupIcon,
            'navigationGroupLabel' => $this->navigationGroupLabel,
            'navigationGroupId' => $this->navigationGroupId,
            'navigationGroupOrder' => $this->navigationGroupOrder,
            'viewMenu' => $this->viewMenu,
            'navigationGroupChunk' => $this->navigationGroupChunk,
            'navigationLink' => $this->navigationLink,
            'pageId' => $this->pageId,
            'isVisble' => $this->isVisble,
        ];
    }
}
