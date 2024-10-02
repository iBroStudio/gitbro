<?php

namespace App\Commands;

use IBroStudio\DataRepository\Enums\SemanticVersionSegments;
use IBroStudio\Git\Data\GitReleaseData;
use IBroStudio\Git\GitRepository;
use IBroStudio\Git\Processes\CreateRepositoryReleaseProcess;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class ReleaseCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'release {--directory=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new release';

    protected GitRepository $repository;

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        $currentVersion = $this->repository->release()->latest();
        $newVersion = null;

        $responses = form()
            ->intro('GITBRO:release')
            ->info('Current version: '.$currentVersion->value())
            ->select(
                label: 'Do you want to publish...',
                options: [
                    'patch' => 'a patch version',
                    'minor' => 'a minor version',
                    'major' => 'a major version',
                ],
                name: 'type'
            )
            ->add(function ($responses) use ($currentVersion, &$newVersion) {
                $newVersion = $currentVersion->increment(SemanticVersionSegments::from($responses['type']));

                return info('New version: '.$newVersion->value());
            })
            ->confirm(label: 'Do you confirm?', name: 'confirm')
            ->submit();

        if ($responses['confirm']) {
            $process = spin(
                callback: fn () => CreateRepositoryReleaseProcess::handleWith([
                    $this->repository,
                    GitReleaseData::from($newVersion, $currentVersion),
                ]),
                message: 'Publishing the release...'
            );

            if ($process->getRepository() instanceof GitRepository) {
                info('Repository successfully created!');

                return self::SUCCESS;
            }

            return self::FAILURE;
        } else {
            info('Aborted.');
        }

        return self::SUCCESS;
    }
}
