<?php

namespace itsjustanthony\Laravel\Dawn\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Console\DuskCommand;


class DawnCommand extends DuskCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dawn {--without-tty : Disable output to TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Dusk tests with a server defined $APP_ENV';
    /**
     * Setup the Dusk environment.
     *
     * @return void
     */
    protected function setupDuskEnvironment()
    {
        if (file_exists(base_path($this->duskFile()))) {
            $this->backupEnvironment(); // Always back up the environment, no reason not to.

            $this->refreshEnvironment();
        }

        $this->writeConfiguration();

        $this->setupSignalHandler();
    }

    /**
     * Backup the current environment file.
     *
     * @return void
     */
    protected function backupEnvironment()
    {
        $fileNamesInBasePath = scandir(base_path());
        foreach ($fileNamesInBasePath as $fileNameToCheck) {
            if (stripos($fileNameToCheck, '.env') === 0) {

                // We have a .env file that might be loaded by laravel,
                // back it up if it's not the dusk file
                if ($fileNameToCheck != $this->duskFile()) {
                    $backupFileName = ".dawn.backup{$fileNameToCheck}";
                    // dusk.backup.{name} means we can test for files starting with .env,
                    // and then to restore we look for files starting with .dawn.backup
                    // using dusk. also helps clarify where the file is coming from
                    copy(base_path($fileNameToCheck), base_path($backupFileName));
                }
            }
        }

        copy(base_path($this->duskFile()), base_path('.env')); // We should only have $this->duskFile() and .env
    }

    protected function teardownDuskEnviroment()
    {
        $this->removeConfiguration();

        $this->restoreEnvironment(); // Always back up and restore.
    }

    protected function restoreEnvironment()
    {
        // Now we reverse the process
        $fileNamesInBasePath = scandir(base_path());
        foreach ($fileNamesInBasePath as $fileNameToCheck) {
            if (stripos($fileNameToCheck, '.dawn.backup') === 0) {

                // This is a file backup up by dusk on a previous run, just restore it
                $restoredFileName = str_ireplace('.dawn.backup', '', $fileNameToCheck);
                copy(base_path($fileNameToCheck), base_path($restoredFileName));
                unlink(base_path($fileNameToCheck));
            }
        }
    }
}
