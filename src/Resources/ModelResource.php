<?php

namespace Gaus57\LaravelApi\Resources;

use Gaus57\LaravelApi\Interfaces\FieldsResourceInterface;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ModelResource
 * @package Gaus57\LaravelApi
 */
class ModelResource extends Resource
{
    /**
     * @inheritdoc
     */
    public function toArray($request): array
    {
        return $this->resource instanceof FieldsResourceInterface
            ? $this->resource->fieldsResource($this, $request)
            : parent::toArray($request);
    }
}
