<?php

namespace App\Entities;

class Item
{
    private $itemId;
    private $itemTitle;
    private $itemDescription;
    private $itemPrice;
    private $itemStock;
    private $categoryId;
    private $itemImagePath;
    private $itemSlug;

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        return $this;
    }

    public function getItemTitle()
    {
        return $this->itemTitle;
    }

    public function setItemTitle($itemTitle)
    {
        $this->itemTitle = $itemTitle;
        return $this;
    }

    public function getItemDescription()
    {
        return $this->itemDescription;
    }

    public function setItemDescription($itemDescription)
    {
        $this->itemDescription = $itemDescription;
        return $this;
    }

    public function getItemPrice()
    {
        return $this->itemPrice;
    }

    public function setItemPrice($itemPrice)
    {
        $this->itemPrice = $itemPrice;
        return $this;
    }

    public function getItemStock()
    {
        return $this->itemStock;
    }

    public function setItemStock($itemStock)
    {
        $this->itemStock = $itemStock;
        return $this;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getItemImagePath()
    {
        return $this->itemImagePath;
    }

    public function setItemImagePath($itemImagePath)
    {
        $this->itemImagePath = $itemImagePath;
        return $this;
    }


    public function getItemSlug()
    {
        return $this->itemSlug;
    }

    public function setItemSlug($itemSlug)
    {
        $this->itemSlug = $itemSlug;
        return $this;
    }
}
