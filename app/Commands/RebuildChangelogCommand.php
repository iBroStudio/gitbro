<?php

namespace App\Commands;

use IBroStudio\Git\GitRepository;
use IBroStudio\Git\Processes\RebuildChangelogProcess;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

class RebuildChangelogCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'changelog:rebuild {--directory=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Rebuild the CHANGELOG history';

    protected GitRepository $repository;

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        intro('GITBRO:changelog:rebuild');

        if ($this->repository->hasChanges()) {
            warning('You have unstaged changes.');
            warning('Please commit or stash them.');

            return self::FAILURE;
        }

        spin(
            callback: fn () => RebuildChangelogProcess::handleWith([$this->repository]),
            message: 'Rebuilding CHANGELOG...'
        );

        info('CHANGELOG successfully rebuilt.');

        return self::SUCCESS;
    }
}
