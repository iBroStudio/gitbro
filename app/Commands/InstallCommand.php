<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;

class InstallCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install an existing project';

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        intro('GITBRO:install');

        error('Command to implement');

        return self::SUCCESS;
    }
}
