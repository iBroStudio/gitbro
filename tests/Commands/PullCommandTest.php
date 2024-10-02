<?php

use App\Commands\PullCommand;
use IBroStudio\Git\GitRepository;
use Illuminate\Console\Command;

it('can run pull command', function () {
    $this->artisan(PullCommand::class)
        ->expectsOutputToContain('Retrieving data...')
        ->expectsOutputToContain('Data successfully pulled.')
        ->assertExitCode(Command::SUCCESS);
});

it('prevents pull from running if there are unstaged changes', function () {
    File::put(
        path: base_path('playground').'/test/CHANGELOG.md',
        contents: ''
    );

    $this->artisan(PullCommand::class)
        ->expectsOutputToContain('You have unstaged changes.')
        ->expectsOutputToContain('Please commit or stash them.')
        ->assertExitCode(Command::FAILURE);

    GitRepository::open(base_path('playground').'/test')->restore();
});
