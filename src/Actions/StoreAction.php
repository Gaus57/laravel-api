<?php

namespace Gaus57\LaravelApi\Actions;

use Gaus57\LaravelApi\Resources\ModelResource;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;;

/**
 * Class StoreAction
 * @package Gaus57\LaravelApi
 */
class StoreAction extends AbstractAction
{
    protected $resource = ModelResource::class;

    /**
     * @inheritdoc
     */
    public function run(): Response
    {
        if (!$this->validateRequest($errors)) {
            return $this->resourceResponse($this->resourceErrors, $errors, 422);
        }
        /** @var Model $model */
        $model = new $this->model();
        $model->fill($this->request->all())->save();

        return $this->resourceResponse($this->resource, $model, 201);
    }
}
