<?php


namespace Melit\Utils;


trait Adminable
{
    /**
     * Check this user is an Administrator
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isMemberOf('admin');
    }

    /**
     * Check this user is a Super Administrator
     * @return bool
     */
    public function isSuper()
    {
        return $this->isMemberOf('super');
    }
}
