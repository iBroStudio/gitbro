<?php

use App\Commands\ReleaseCommand;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;
use Symfony\Component\Console\Command\Command;

it('can run release command', function () {
    Prompt::fake([
        Key::ENTER, //release type : patch
        Key::ENTER, //confirm : yes
    ]);

    $this->artisan(ReleaseCommand::class)
        ->expectsOutputToContain('Current version: ')
        ->expectsOutputToContain('Do you want to publish...')
        ->expectsOutputToContain('New version: ')
        ->expectsOutputToContain('Do you confirm?')
        ->expectsOutputToContain('Publishing the release...')
        ->expectsOutputToContain('Repository successfully created!')
        ->assertExitCode(Command::SUCCESS);
});

it('can abort releasing process', function () {
    Prompt::fake([
        Key::ENTER, //release type : patch
        Key::DOWN, Key::ENTER, //confirm: no
    ]);

    $this->artisan(ReleaseCommand::class)
        ->expectsOutputToContain('Current version: ')
        ->expectsOutputToContain('Do you want to publish...')
        ->expectsOutputToContain('New version: ')
        ->expectsOutputToContain('Do you confirm?')
        ->expectsOutputToContain('Aborted.')
        ->assertExitCode(Command::SUCCESS);
});
