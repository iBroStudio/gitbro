<?php

namespace App\Commands;

use IBroStudio\Git\GitRepository;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;

class PushCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'push {--directory=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Push data to remote repository';

    protected GitRepository $repository;

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        intro('GITBRO:push');

        spin(
            callback: fn () => $this->repository->push(),
            message: 'Sending data...'
        );

        info('Data successfully pushed.');

        return self::SUCCESS;
    }
}
