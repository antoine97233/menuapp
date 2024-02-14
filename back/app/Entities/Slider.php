<?php

namespace App\Entities;

class Slider
{
    private $sliderId;
    private $sliderName;
    private $sliderTitle;
    private $sliderDescription;
    private $sliderImage;
    private $sliderRank;
    private $sliderSlug;

    public function getSliderId()
    {
        return $this->sliderId;
    }

    public function setSliderId($sliderId)
    {
        $this->sliderId = $sliderId;
        return $this;
    }

    public function getSliderName()
    {
        return $this->sliderName;
    }

    public function setSliderName($sliderName)
    {
        $this->sliderName = $sliderName;
        return $this;
    }

    public function getSliderTitle()
    {
        return $this->sliderTitle;
    }

    public function setSliderTitle($sliderTitle)
    {
        $this->sliderTitle = $sliderTitle;
        return $this;
    }

    public function getSliderDescription()
    {
        return $this->sliderDescription;
    }

    public function setSliderDescription($sliderDescription)
    {
        $this->sliderDescription = $sliderDescription;
        return $this;
    }

    public function getSliderImage()
    {
        return $this->sliderImage;
    }

    public function setSliderImage($sliderImage)
    {
        $this->sliderImage = $sliderImage;
        return $this;
    }

    public function getSliderRank()
    {
        return $this->sliderRank;
    }

    public function setSliderRank($sliderRank)
    {
        $this->sliderRank = $sliderRank;
        return $this;
    }

    public function getSliderSlug()
    {
        return $this->sliderSlug;
    }

    public function setSliderSlug($sliderSlug)
    {
        $this->sliderSlug = $sliderSlug;
        return $this;
    }
}
