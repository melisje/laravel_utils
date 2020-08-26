<?php

namespace Melit\Utils;

use App\Group;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table        = "applications";
    protected $guarded      = [];
    public    $incrementing = false;

    /**
     * Scope a query to only include active Applications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Find the group this Application belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'id');
    }

    /**
     * Find al Groups that have access to this application.
     * All Groups that are directly or indirectly attached via child relationship have access to this Application.
     */
    public function getAclAttribute()
    {
        $parent      = Group::find($this->group_id);
        $descendants = $parent->children();

        return $descendants->merge([$parent]);
    }


    /**
     * overide of standard save function.
     * We first check if a group exists for this Application. If not, create it.
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        /*
         * make sure the group for this application also exists
         */
        if ($this->id)
        {
            if (!Group::find($this->id))
            {
                $group = Group::create([
                                           'id'   => $this->id,
                                           'name' => $this->name . " application's ACL group",
                                       ]);

                /*
                 * make sure admin group has access to this Application
                 */
                Group::findOrFail('admin')->attachParent($group);

            }
        }

        return parent::save($options);
    }

}
