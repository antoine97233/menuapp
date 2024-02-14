<?php

namespace App\Entities;

class Admin
{
    private $adminId;
    private $adminEmail;
    private $adminPassword;
    private $adminName;
    private $adminSuper;

    public function getAdminId()
    {
        return $this->adminId;
    }

    public function setAdminId($adminId)
    {
        $this->adminId = $adminId;
        return $this;
    }

    public function getAdminEmail()
    {
        return $this->adminEmail;
    }

    public function setAdminEmail($adminEmail)
    {
        $this->adminEmail = $adminEmail;
        return $this;
    }

    public function getAdminPassword()
    {
        return $this->adminPassword;
    }

    public function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;
        return $this;
    }

    public function getAdminName()
    {
        return $this->adminName;
    }

    public function setAdminName($adminName)
    {
        $this->adminName = $adminName;
        return $this;
    }

    public function isAdminSuper()
    {
        return $this->adminSuper;
    }

    public function setAdminSuper($adminSuper)
    {
        $this->adminSuper = $adminSuper;
        return $this;
    }
}
