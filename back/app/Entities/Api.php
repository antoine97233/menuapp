<?php

namespace App\Entities;

class Api
{
    private $apiId;
    private $googleKeyApi;
    private $googlePlaceId;


    public function getApiId()
    {
        return $this->apiId;
    }

    public function setApiId($apiId)
    {
        $this->apiId = $apiId;
        return $this;
    }

    public function getGoogleKeyApi()
    {
        return $this->googleKeyApi;
    }

    public function setGoogleKeyApi($googleKeyApi)
    {
        $this->googleKeyApi = $googleKeyApi;
        return $this;
    }

    public function getGooglePlaceId()
    {
        return $this->googlePlaceId;
    }

    public function setGooglePlaceId($googlePlaceId)
    {
        $this->googlePlaceId = $googlePlaceId;
        return $this;
    }
}
