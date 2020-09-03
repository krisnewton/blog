<?php

namespace Harishariyanto\Blog;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->publishes([
            __DIR__ . '/../publishes/app'           => app_path(),
            __DIR__ . '/../publishes/database'      => database_path(),
            __DIR__ . '/../publishes/public'        => public_path(),
            __DIR__ . '/../publishes/resources'     => resource_path()
        ]);
    }
}
