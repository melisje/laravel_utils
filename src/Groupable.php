<?php

namespace Melit\Utils;

use App\Group;

trait Groupable
{
    /**
     * The groups that belong to the user.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }


    /**
     * Check if current User is member of the given Group
     * @param Group | string $group
     * @return bool
     */
    public function isDirectMemberOf($group)
    {
        $group = ($group instanceof Group) ? $group->id : $group;
        return $this->groups()->where('group_user.group_id', $group)->exists();
    }

    /**
     * Check if this User is member of the given group or one of it's child Groups.
     *
     * @param $group Group: can be the id of the Group object or the Group object it self
     * @param $recursive bool: If $recursive=true, this method checks if this User is also member of one of the Group's child Groups
     * @return bool
     */
    public function isMemberOf($group, $recursive = true)
    {

        $group = ($group instanceof Group) ? $group : Group::find($group);
        if ($group)
        {
            return $group->hasMember($this, $recursive);
        }
        else
        {
            return false;
        } // group could not be found, hence this User could also not be a member of it
    }
}
