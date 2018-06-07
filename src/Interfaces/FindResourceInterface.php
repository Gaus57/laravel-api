<?php

namespace Gaus57\LaravelApi\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Interface FindResourceInterface
 * @package Gaus57\LaravelApi
 */
interface FindResourceInterface
{
    /**
     * Find resource model.
     *
     * @param Request $request
     * @param string $id
     * @return Model|null
     */
    public function findResource(Request $request, string $id): ?Model;
}
