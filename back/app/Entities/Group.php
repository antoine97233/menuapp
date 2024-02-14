<?php

namespace App\Entities;

class Group
{
    private $groupId;
    private $groupTitle;
    private $groupDescription;
    private $groupSlug;

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function getGroupTitle()
    {
        return $this->groupTitle;
    }

    public function setGroupTitle($groupTitle)
    {
        $this->groupTitle = $groupTitle;
        return $this;
    }

    public function getGroupDescription()
    {
        return $this->groupDescription;
    }

    public function setGroupDescription($groupDescription)
    {
        $this->groupDescription = $groupDescription;
        return $this;
    }

    public function getGroupSlug()
    {
        return $this->groupSlug;
    }

    public function setGroupSlug($groupSlug)
    {
        $this->groupSlug = $groupSlug;
        return $this;
    }
}
