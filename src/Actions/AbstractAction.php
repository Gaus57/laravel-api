<?php

namespace Gaus57\LaravelApi\Actions;

use Gaus57\LaravelApi\Interfaces\FindResourceInterface;
use Gaus57\LaravelApi\Interfaces\FindResourcesInterface;
use Gaus57\LaravelApi\Resources\ErrorsResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Abstract Class AbstractAction
 * @package Gaus57\LaravelApi
 */
abstract class AbstractAction
{
    /**
     * @var array
     */
    protected $allowedOptions = [
        'request',
        'resource',
        'resourceErrors',
        'model',
        'allowedPerPage',
        'defaultPerPage',
    ];

    /**
     * @var string|Request
     */
    protected $request = Request::class;

    /**
     * @var string|Resource
     */
    protected $resource = Resource::class;

    /**
     * @var string|Resource
     */
    protected $resourceErrors = ErrorsResource::class;

    /**
     * @var string|Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $allowedPerPage = [
        '10', '20', '50', '100'
    ];

    /**
     * @var string
     */
    protected $defaultPerPage = '20';

    /**
     * AbstractAction constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        foreach (array_only($options, $this->allowedOptions) as $key => $value) {
            $this->$key = $value;
        }
        $this->request = app($this->request);
    }

    /**
     * Validate request.
     *
     * @param null $errors Validation errors
     * @return bool
     */
    protected function validateRequest(&$errors = null): bool
    {
        $result = true;
        if ($this->request instanceof FormRequest) {
            try {
                $this->request->validated();
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                $result = false;
            }
        }

        return $result;
    }

    protected function findModel(): Model
    {
        /** @var Model $model */
        $model = new $this->model();
        $id = $this->request->route()->parameter('id');
        if ($model instanceof FindResourceInterface) {
            $result = $model->findResource($this->request, $id);
        } else {
            $result = $model::find($id);
        }

        if (!$result) {
            throw new NotFoundHttpException();
        }

        return $result;
    }

    protected function findModels()
    {
        /** @var Model $model */
        $model = new $this->model();
        if ($model instanceof FindResourcesInterface) {
            $result = $model->findResources($this->request);
        } else {
            $result = $model;
        }
        $perPage = $this->request->get('per_page');
        $perPage = \in_array($perPage, $this->allowedPerPage)
            ? $perPage
            : $this->defaultPerPage;

        return $perPage
            ? $result->paginate($perPage)
            : $result->all();
    }

    protected function resourceResponse(?string $resourceClass, $resource, int $statusCode = 200): \Symfony\Component\HttpFoundation\Response
    {
        $response = $resourceClass
            ? (new $resourceClass($resource))->response()
            : new Response;

        return $response->setStatusCode($statusCode);
    }

    /**
     * Run action.
     *
     * @return \Symfony\Component\HttpFoundation\Response;
     */
    abstract public function run(): \Symfony\Component\HttpFoundation\Response;
}
