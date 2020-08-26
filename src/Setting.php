<?php

namespace Melit\Utils;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * The Setting class is meant for application specific settings that must be adjustable in the application it self.
 * System wide settings should probably better be saved in Laravel's .env settings file
 *
 * @package App
 */
class Setting extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * Whether or not timestamp fields are used or not.
     * created_at
     * modified_at
     *
     * @var bool
     */
    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     *
     */

    protected $guarded = [];

    /**
     * return the value of this Setting
     * The Setting's type can be
     * - varchar
     * - double
     * - int
     * - boolean
     * - longtext
     * - date
     * - datetime
     * - timestamp
     * @return mixed
     */
    public function getValueAttribute()
    {
        $type  = $this->type;
        $field = "value_$type";

        /*
         * Cast date, datetime and timestamps to Carbon
         */
        switch ($type)
        {
            case 'date':
            case 'datetime':
            case 'timestamp':
                return new Carbon($this->$field);
                break;
            default:
                return  $this->$field;
                break;
        }
    }

    /**
     * Set the value of this Setting.
     *
     * If the given value can not be stored in the appropriate value field,
     * a QueryException is thrown when saving the object to the database
     *
     * @param $value
     * @param bool $save
     * @return $this
     */
    public function setValueAttribute($value)
    {
        $type         = $this->type;
        $field        = "value_$type";
        $this->$field = $value;

        return $this;
    }

    /**
     * Return the value with the given id valuue, as stored in in the Setting's type attribute
     * @param $id
     * @return string
     */
    static public function value($id)
    {
        try
        {
            $setting = Setting::findOrFail($id);
            //            $type=$setting->type;
            //            $field = "value_$type";
            return $setting->value;
        }
        catch (\Exception $exc)
        {
            return "Setting $id not found!";
        }


    }

    /**
     * Return a list of all Settings in a given group
     * @param $group
     * @return mixed
     */
    static public function group($group)
    {
        return Setting::where('group', $group)
                      ->get()
                      ->map(function ($item)
                      {
                          return $item->value;
                      })
            ;
    }
}
