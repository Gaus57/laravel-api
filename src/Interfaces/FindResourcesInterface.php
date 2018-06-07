<?php

namespace Gaus57\LaravelApi\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Interface FindResourcesInterface
 * @package Gaus57\LaravelApi
 */
interface FindResourcesInterface
{
    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeFindResources(Builder $query, Request $request): Builder;
}
