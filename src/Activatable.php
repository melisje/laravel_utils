<?php


namespace Melit\Utils;


trait Activatable
{
    /**
     * Check if this User is activated or not.
     *
     * @return bool
     */
    public function getIsActivatedAttribute()
    {
        return $this->activated ? true : false;
    }
}
