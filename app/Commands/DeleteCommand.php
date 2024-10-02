<?php

namespace App\Commands;

use IBroStudio\Git\GitRepository;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class DeleteCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'delete {--directory=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete a repository locally and remotely';

    protected GitRepository $repository;

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        $responses = form()
            ->intro('GITBRO:delete')
            ->warning('WARNING: the repository will be deleted locally and remotely.')
            ->confirm(label: 'Do you confirm?', name: 'confirm')
            ->submit();

        if ($responses['confirm']) {
            spin(
                callback: fn () => $this->repository->delete(),
                message: 'Deleting repository...'
            );

            info('Repository successfully deleted.');
        } else {
            info('Aborted.');
        }

        return self::SUCCESS;
    }
}
