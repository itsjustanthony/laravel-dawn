<?php

namespace itsjustanthony\Laravel\Dawn\Console;

use Illuminate\Console\Command;
use Laravel\Dusk\Console\DuskFailsCommand;

class DawnFailsCommand extends DawnCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dawn:fails {--without-tty : Disable output to TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the failing Dusk tests from the last run and stop on failure, using a server defined $APP_ENV';

    /**
     * Get the array of arguments for running PHPUnit.
     *
     * @param  array  $options
     * @return array
     */
    protected function phpunitArguments($options)
    {
        return array_unique(array_merge(parent::phpunitArguments($options), [
            '--cache-result', '--order-by=defects', '--stop-on-failure',
        ]));
    }
}
