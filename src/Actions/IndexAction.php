<?php

namespace Gaus57\LaravelApi\Actions;

use Gaus57\LaravelApi\Resources\CollectionResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexAction
 * @package Gaus57\LaravelApi
 */
class IndexAction extends AbstractAction
{
    protected $resource = CollectionResource::class;

    /**
     * @inheritdoc
     */
    public function run(): Response
    {
        if (!$this->validateRequest($errors)) {
            return $this->resourceResponse($this->resourceErrors, $errors, 422);
        }

        return $this->resourceResponse($this->resource, $this->findModels());
    }
}
