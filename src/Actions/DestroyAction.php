<?php

namespace Gaus57\LaravelApi\Actions;

use Symfony\Component\HttpFoundation\Response;;

/**
 * Class DestroyAction
 * @package Gaus57\LaravelApi
 */
class DestroyAction extends AbstractAction
{
    protected $resource = null;

    /**
     * @inheritdoc
     */
    public function run(): Response
    {
        if (!$this->validateRequest($errors)) {
            return $this->resourceResponse($this->resourceErrors, $errors, 422);
        }
        $model = $this->findModel();
        $model->delete();

        return $this->resourceResponse($this->resource, $model, $this->resource ? 200 : 204);
    }
}
