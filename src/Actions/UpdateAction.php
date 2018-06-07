<?php

namespace Gaus57\LaravelApi\Actions;

use Gaus57\LaravelApi\Resources\ModelResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UpdateAction
 * @package Gaus57\LaravelApi
 */
class UpdateAction extends AbstractAction
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
        $model = $this->findModel();
        $model->fill($this->request->all())->save();

        return $this->resourceResponse($this->resource, $model);
    }
}
