<?php

namespace Blinktag\Providers;

use Blinktag\Console\Commands\FindTodos;
use Illuminate\Support\ServiceProvider;

class FindTodosServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register command.
        if ($this->app->runningInConsole()) {
            $this->commands([
                FindTodos::class,
            ]);
        }

        // Publish vendor files
        $this->publishes([
            __DIR__ . '/../config/findtodos.php' => config_path('findtodos.php'),
        ], 'findtodos');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
