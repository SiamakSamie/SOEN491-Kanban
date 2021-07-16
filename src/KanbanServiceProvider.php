<?php

namespace SiamakSamie\Kanban;

use Illuminate\Support\ServiceProvider;

class KanbanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('SiamakSamie\Kanban\Http\Controllers\KanbanController');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'SiamakSamie\Kanban');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/Http/Middleware');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/database/seeds');

        include __DIR__ . '/routes/web.php';

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/kanban'),
        ], 'kanban-assets');
    }
}
