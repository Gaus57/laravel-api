<?php

namespace Gaus57\LaravelApi\Actions;

use Gaus57\LaravelApi\Resources\ModelResource;
use Symfony\Component\HttpFoundation\Response;;

/**
 * Class ShowAction
 * @package Gaus57\LaravelApi
 */
class ShowAction extends AbstractAction
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

        return $this->resourceResponse($this->resource, $this->findModel());
    }
}
