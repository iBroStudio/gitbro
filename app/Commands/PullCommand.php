<?php

namespace App\Commands;

use IBroStudio\Git\GitRepository;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

class PullCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'pull {--directory=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Fetch data from remote repository';

    protected GitRepository $repository;

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        intro('GITBRO:pull');

        if ($this->repository->hasChanges()) {
            warning('You have unstaged changes.');
            warning('Please commit or stash them.');

            return self::FAILURE;
        }

        spin(
            callback: fn () => $this->repository->pull(),
            message: 'Retrieving data...'
        );

        info('Data successfully pulled.');

        return self::SUCCESS;
    }
}
