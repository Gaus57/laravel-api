<?php

namespace Gaus57\LaravelApi\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Interface FieldsResourceInterface
 * @package Gaus57\LaravelApi
 */
interface FieldsResourceInterface
{
    /**
     * @param JsonResource $resource
     * @param Request $request
     * @return array
     */
    public function fieldsResource(JsonResource $resource, Request $request): array;
}
