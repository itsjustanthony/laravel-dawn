<?php
/**
 * Created by PhpStorm.
 * User: anthony
 * Date: 2/24/20
 * Time: 5:00 PM
 */

namespace itsjustanthony\Laravel\Dawn\Providers;



use Illuminate\Support\ServiceProvider;
use itsjustanthony\Laravel\Dawn\Console\DawnCommand;
use itsjustanthony\Laravel\Dawn\Console\DawnFailsCommand;

class DawnConsoleProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function register()
    {
        if ($this->app->environment('production')) {
            throw new Exception('It is unsafe to run Dawn in production.');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                DawnCommand::class,
                DawnFailsCommand::class,
            ]);
        }
    }
}