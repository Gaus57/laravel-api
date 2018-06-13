<?php

namespace Gaus57\LaravelApi;

use Gaus57\LaravelApi\Actions\DestroyAction;
use Gaus57\LaravelApi\Actions\IndexAction;
use Gaus57\LaravelApi\Actions\ShowAction;
use Gaus57\LaravelApi\Actions\StoreAction;
use Gaus57\LaravelApi\Actions\UpdateAction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class ApiServiceProvider
 * @package Gaus57\LaravelApi
 */
class ApiServiceProvider extends ServiceProvider
{
    protected $defaultActions = [
        'index' => IndexAction::class,
        'show' => ShowAction::class,
        'store' => StoreAction::class,
        'update' => UpdateAction::class,
        'destroy' => DestroyAction::class,
    ];

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->initRoutes();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {

    }

    /**
     * Publish config.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            realpath(__DIR__ . '/config/api.php') => base_path('config/api.php'),
        ]);
    }

    protected function initRoutes(): void
    {
        $defaultActions = config('api.defaultActions', []);
        $modules = config('api.modules', []);
        foreach ($modules as $module) {
            $routeParams = array_only($module, ['prefix', 'middleware']);
            Route::group($routeParams, function () use ($module, $defaultActions) {
                $this->initModuleRouteGroup($module, $defaultActions);
            });
        }
    }

    protected function initModuleRouteGroup(array $module, array $defaultActions): void
    {
        $defaultActions = array_replace_recursive(
            $defaultActions,
            array_get($module, 'defaultActions', [])
        );
        foreach (array_get($module, 'resources', []) as $name => $resource) {
            $routeParams = array_merge(
                array_only($resource, ['middleware']),
                ['prefix' => $name]
            );
            Route::group($routeParams, function () use ($defaultActions, $resource) {
                $this->initResourceRouteGroup($resource, $defaultActions);
            });
        }
    }

    protected function initResourceRouteGroup(array $resource, array $defaultActions): void
    {
        $defaultActions = array_replace_recursive(
            $defaultActions,
            array_get($resource, 'actions', [])
        );
        $model = array_get($resource, 'model');
        $allowedActions = array_diff(
            array_keys($this->defaultActions),
            array_get($resource, 'disallowed', [])
        );
        if ($allowed = array_get($resource, 'allowed')) {
            $allowedActions = array_intersect($allowedActions, $allowed);
        }
        foreach ($allowedActions as $action) {
            $options = array_merge(
                $defaultActions[$action] ?? [],
                ['model' => $model]
            );
            $this->initActionRoute($action, $options);
        }
    }

    protected function initActionRoute(string $action, array $options): void
    {
        $class = $options['class'] ?? $this->defaultActions[$action];
        switch ($action) {
            case 'index':
                Route::get('', function () use ($class, $options) {
                    return (new $class($options))->run();
                });
                break;
            case 'show':
                Route::get('{id}', function () use ($class, $options) {
                    return (new $class($options))->run();
                });
                break;
            case 'store':
                Route::post('', function () use ($class, $options) {
                    return (new $class($options))->run();
                });
                break;
            case 'update':
                Route::put('{id}', function () use ($class, $options) {
                    return (new $class($options))->run();
                });
                break;
            case 'destroy':
                Route::delete('{id}', function () use ($class, $options) {
                    return (new $class($options))->run();
                });
                break;
        }
    }
}
