<?php

namespace App\Commands;

use IBroStudio\Git\Data\GitCommitData;
use IBroStudio\Git\Enums\GitCommitTypes;
use IBroStudio\Git\GitRepository;
use IBroStudio\Git\Processes\CreateCommitProcess;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\text;

class CommitCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'commit {--directory=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new commit';

    protected GitRepository $repository;

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        intro('GITBRO:commit');

        if (! $this->repository->hasChanges()) {
            error('Nothing to commit.');

            return self::FAILURE;
        }

        $responses = form()
            ->select(
                label: 'Select commit type',
                options: GitCommitTypes::values(),
                name: 'type'
            )

            ->add(
                function () {
                    return Str::lcfirst(
                        text('Message?', required: true)
                    );
                },
                name: 'message'
            )

            ->submit();

        $process = CreateCommitProcess::handleWith([
            $this->repository,
            GitCommitData::from(...$responses),
        ]);

        if ($process->getCommitData() instanceof GitCommitData) {
            info('Changes successfully commited!');

            if (confirm('Do want to push to the remote?')) {
                return $this->call(
                    SyncCommand::class,
                    ['--directory' => $this->option('directory')]
                );
            }

            return self::SUCCESS;
        }

        return self::FAILURE;
    }
}
