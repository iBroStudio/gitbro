<?php

namespace App\Commands;

use App\Services\TemplateService;
use IBroStudio\DataRepository\ValueObjects\GitSshUrl;
use IBroStudio\Git\Data\GitUserOrganizationData;
use IBroStudio\Git\Data\RepositoryPropertiesData;
use IBroStudio\Git\Enums\GitProvidersEnum;
use IBroStudio\Git\GitRepository;
use IBroStudio\Git\GitUser;
use IBroStudio\Git\Processes\InitRemoteRepositoryProcess;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

class InitCommand extends AbstractGitbroCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * Execute the console command.
     */
    public function make(): int
    {
        $gitUserInfos = GitUser::infos();

        $templates = (new TemplateService)->get();

        $responses = form()
            ->intro('GITBRO:init')

            ->add(
                function () {
                    return Str::slug(
                        text('What is the name of the project?', required: true), '-'
                    );
                },
                name: 'name'
            )

            ->select(
                label: 'Visibility?',
                options: [
                    'public' => 'public',
                    'private' => 'private',
                ],
                default: 'public',
                name: 'visibility'
            )

            ->add(function () use ($gitUserInfos) {
                if (! $gitUserInfos->organizations->count()) {
                    return null;
                }

                $ownerChoices = [
                    $gitUserInfos->name => $gitUserInfos->name,
                ];

                $gitUserInfos->organizations->each(function (GitUserOrganizationData $organization) use (&$ownerChoices) {
                    $ownerChoices[$organization->name] = $organization->name;
                });

                return select(
                    label: 'Owner of the repository?',
                    options: $ownerChoices,
                    default: null
                );
            }, name: 'owner')

            ->select(
                label: 'Do you want to use a template repository?',
                options: [
                    null => 'No',
                    ...$templates->mapWithKeys(function (GitSshUrl $url) {
                        return [
                            $url->repository() => Str::of($url->repository())
                                ->prepend(' ')
                                ->prepend(Str::title($url->username())),
                        ];
                    })->toArray(),
                ],
                name: 'templateRepo'
            )

            ->add(function ($responses) use ($templates) {
                return $templates->first(function (GitSshUrl $url) use ($responses) {
                    return $url->repository() === $responses['templateRepo'];
                })?->username();
            }, name: 'templateOwner')

            ->submit();

        $responses = array_merge($responses, [
            'remote' => config('git.default.remote'),
            'branch' => config('git.default.branch'),
            'provider' => GitProvidersEnum::GITHUB,
            'localParentDirectory' => config('app.workingDirectory'),
        ]);

        $process = spin(
            callback: fn () => InitRemoteRepositoryProcess::handleWith(
                [RepositoryPropertiesData::from($responses)]
            ),
            message: 'Creating the repository...'
        );

        if ($process->getRepository() instanceof GitRepository) {
            info('Repository successfully created!');

            return self::SUCCESS;
        }

        return self::FAILURE;
    }
}
