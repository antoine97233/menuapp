<?php

namespace App\Entities;

class Menu
{
    private $menuId;
    private $menuTitle;
    private $menuPath;


    public function getMenuId()
    {
        return $this->menuId;
    }

    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;
        return $this;
    }

    public function getMenuTitle()
    {
        return $this->menuTitle;
    }

    public function setMenutitle($menuTitle)
    {
        $this->menuTitle = $menuTitle;
        return $this;
    }

    public function getMenuPath()
    {
        return $this->menuPath;
    }

    public function setMenuPath($menuPath)
    {
        $this->menuPath = $menuPath;
        return $this;
    }
}
