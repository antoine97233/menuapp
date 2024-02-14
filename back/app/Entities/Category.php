<?php

namespace App\Entities;

class Category
{
    private $categoryId;
    private $categoryTitle;
    private $categoryDescription;
    private $groupId;
    private $categoryRank;
    private $categorySlug;

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getCategoryTitle()
    {
        return $this->categoryTitle;
    }

    public function setCategoryTitle($categoryTitle)
    {
        $this->categoryTitle = $categoryTitle;
        return $this;
    }

    public function getCategoryDescription()
    {
        return $this->categoryDescription;
    }

    public function setCategoryDescription($categoryDescription)
    {
        $this->categoryDescription = $categoryDescription;
        return $this;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function getCategoryRank()
    {
        return $this->categoryRank;
    }

    public function setCategoryRank($categoryRank)
    {
        $this->categoryRank = $categoryRank;
        return $this;
    }

    public function getCategorySlug()
    {
        return $this->categorySlug;
    }

    public function setCategorySlug($categorySlug)
    {
        $this->categorySlug = $categorySlug;
        return $this;
    }
}
