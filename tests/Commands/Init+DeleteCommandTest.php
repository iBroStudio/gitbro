<?php

use App\Commands\DeleteCommand;
use App\Commands\InitCommand;
use Illuminate\Support\Facades\Config;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;
use Symfony\Component\Console\Command\Command;

it('can init a repository', function () {
    Prompt::fake([
        't', 'e', 's', 't', '-', 'g', 'i', 't', 'b', 'r', 'o', Key::ENTER,  //repo name
        Key::DOWN, Key::ENTER, //visibility : private
        Key::ENTER, //owner : user
        Key::ENTER, //use template: no
    ]);

    $this->artisan(InitCommand::class)
        ->expectsOutputToContain('What is the name of the project?')
        ->expectsOutputToContain('Visibility?')
        ->expectsOutputToContain('Owner of the repository?')
        ->expectsOutputToContain('Do you want to use a template repository?')
        ->expectsOutputToContain('Creating the repository...')
        ->expectsOutputToContain('Repository successfully created!')
        ->assertExitCode(Command::SUCCESS);
})
    ->depends('it can init a repository with template');

it('can init a repository with template', function () {
    Prompt::fake([
        't', 'e', 's', 't', '-', 'g', 'i', 't', 'b', 'r', 'o', '-', 't', 'e', 'm', 'p', 'l', 'a', 't', 'e', Key::ENTER,  //repo name
        Key::DOWN, Key::ENTER, //visibility : private
        Key::ENTER, //owner : user
        Key::DOWN, Key::ENTER, //use template: spatie-skeleton
    ]);

    $this->artisan(InitCommand::class)
        ->expectsOutputToContain('What is the name of the project?')
        ->expectsOutputToContain('Visibility?')
        ->expectsOutputToContain('Owner of the repository?')
        ->expectsOutputToContain('Do you want to use a template repository?')
        ->expectsOutputToContain('Creating the repository...')
        ->expectsOutputToContain('Repository successfully created!')
        ->assertExitCode(Command::SUCCESS);
});

it('can run delete command', function (string $repository) {
    Config::set('app.repositoryDirectory', Config::get('app.workingDirectory').$repository);
    Prompt::fake([
        Key::ENTER, //confirm: yes
    ]);

    $this->artisan(DeleteCommand::class, ['--directory' => Config::get('app.workingDirectory').$repository])
        ->expectsOutputToContain('WARNING: the repository will be deleted locally and remotely.')
        ->expectsOutputToContain('Do you confirm?')
        ->expectsOutputToContain('Deleting repository...')
        ->expectsOutputToContain('Repository successfully deleted.')
        ->assertExitCode(Command::SUCCESS);
})
    ->with(['/test-gitbro', '/test-gitbro-template'])
    //*
    ->depends(
        'it can init a repository',
        'it can init a repository with template',
        'it can abort deleting process'
    );
//*/

it('can abort deleting process', function () {
    Prompt::fake([
        Key::DOWN, Key::ENTER, //confirm: no
    ]);

    $this->artisan(DeleteCommand::class, ['--directory' => Config::get('app.workingDirectory').'/test-gitbro'])
        ->expectsOutputToContain('WARNING: the repository will be deleted locally and remotely.')
        ->expectsOutputToContain('Do you confirm?')
        ->expectsOutputToContain('Aborted.')
        ->assertExitCode(Command::SUCCESS);
})
    ->depends('it can init a repository');
