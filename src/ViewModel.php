<?php

namespace Melit\Utils;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;

class ViewModel implements Arrayable, Responsable
{
    protected $ignore     = [];
    protected $view       = '';
    protected $attributes = [];

    /**
     * ViewModel constructor.
     * You can give the view related to this ViewModel.
     * If given, you can return the ViewModel as a Response and the given view will open with this ViewModel
     * containing the available variables
     * @param null $view
     */
    public function __construct($view = null)
    {
        $this->view = $view;
    }

    /**
     * Magic getter
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->attributes[$name];
    }

    /**
     * Magic setter will store the parameter in the attributes array of this ViewModel
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this
            ->items()
            ->all()
            ;
    }

    /**
     * Make a Collection of all public attributes, methods and added attributes of this object
     * @return Collection
     * @throws \ReflectionException
     */
    protected function items(): Collection
    {
        $class = new ReflectionClass($this);

        $publicProperties = collect($class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->reject(function (ReflectionProperty $property)
            {
                return $this->shouldIgnore($property->getName());
            })
            ->mapWithKeys(function (ReflectionProperty $property)
            {
                return [$property->getName() => $this->{$property->getName()}];
            })
        ;

        $publicMethods = collect($class->getMethods(ReflectionMethod::IS_PUBLIC))
            ->reject(function (ReflectionMethod $method)
            {
                return $this->shouldIgnore($method->getName());
            })
            ->mapWithKeys(function (ReflectionMethod $method)
            {
                return [$method->getName() => $this->createVariableFromMethod($method)];
            })
        ;

        return $publicProperties->merge($publicMethods)
                                ->merge($this->attributes)
            ;
    }

    /**
     * Check if given methodname belongs to method that should be ignored.
     * Methodes should be ignored if it starts with '__', like __set, __get, __ contstruct or it is added to the internal objects ignoredMethods array.
     *
     * @param string $methodName
     * @return bool
     */
    protected function shouldIgnore(string $methodName): bool
    {
        if (Str::startsWith($methodName, '__'))
        {
            return true;
        }

        return in_array($methodName, $this->ignoredMethods());
    }

    /**
     * return an array of methods that should be ignored.
     * These are methods that are added to the inner ignore array extended with the toArray, toResponse and view methods
     * @return array
     */
    protected function ignoredMethods(): array
    {
        return array_merge([
                               'toArray',
                               'toResponse',
                               'view',
                           ], $this->ignore);
    }

    /**
     *
     * @param ReflectionMethod $method
     * @return Closure
     */
    protected function createVariableFromMethod(ReflectionMethod $method)
    {
        if ($method->getNumberOfParameters() === 0)
        {
            return $this->{$method->getName()}();
        }

        return Closure::fromCallable([$this, $method->getName()]);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        if ($request->wantsJson())
        {
            return new JsonResponse($this->items());
        }

        if ($this->view)
        {
            return response()->view($this->view, $this);
        }

        return new JsonResponse($this->items());
    }

    /**
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(string $view): View
    {
        return view($view, $this);
    }

    /**
     * Create a Flash message to be shown with a Messagbag in the view.
     * @param $key
     * @param $value
     * @return $this ViewModel, for chaining
     */
    public function flash($key, $value)
    {
        Session::flash($key, $value);
        return $this;
    }
}
