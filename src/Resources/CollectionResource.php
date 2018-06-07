<?php

namespace Gaus57\LaravelApi\Resources;

use Gaus57\LaravelApi\Interfaces\FieldsResourceInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class CollectionResource
 * @package Gaus57\LaravelApi
 */
class CollectionResource extends ResourceCollection
{
    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) use ($request) {
            return $item instanceof FieldsResourceInterface
                ? $item->fieldsResource($this, $request)
                : $item->toArray();
        })->all();
    }
}
