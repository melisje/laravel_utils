<?php


namespace Melit\Utils;


use Illuminate\Database\Eloquent\Model;

abstract class ViewModelMapper extends ViewModel
{
    /**
     * ViewModelMapper constructor.
     *
     * Map the given Model object using the mapping implementation of the abstract methode 'map'.
     *
     * @param Model $model
     * @param bool $append_model if true, the given $model will be available in the ViewModel object as attribute 'model'
     */
    public function __construct(Model $model, $append_model = false)
    {
        /*
         * Check if we also need to append the original model and make it
         * available in this ViewModel object via the 'model' attribute
         */
        if ($append_model)
        {
            parent::__construct(['model' => $model]);
        }

        /*
         * map model attributes
         */
        $this->map($model);
    }

    /**
     * This method maps attributes from the given model to available, formated, transformed, added, ...
     * attributes in this ViewModel.
     *
     * Add attributes to this ViewModel by
     *
     * @param $model
     * @return mixed
     */
    abstract protected function map($model);

    /**
     * Make a collection of ViewModels from the given
     * @param $models
     * @return mixed
     */
    static public function collection($models)
    {
        return $models->map(function ($model)
        {
            return new Static($model);
        });
    }
}
