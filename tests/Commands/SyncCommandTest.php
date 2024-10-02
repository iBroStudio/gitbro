<?php

use App\Commands\SyncCommand;
use IBroStudio\Git\GitRepository;
use Illuminate\Console\Command;

it('can run sync command', function () {
    $this->artisan(SyncCommand::class)
        ->expectsOutputToContain('Syncing data...')
        ->expectsOutputToContain('Data successfully synced.')
        ->assertExitCode(Command::SUCCESS);
});

it('prevents sync from running if there are unstaged changes', function () {
    File::put(
        path: base_path('playground').'/test/CHANGELOG.md',
        contents: ''
    );

    $this->artisan(SyncCommand::class)
        ->expectsOutputToContain('You have unstaged changes.')
        ->expectsOutputToContain('Please commit or stash them.')
        ->assertExitCode(Command::FAILURE);

    GitRepository::open(base_path('playground').'/test')->restore();
});
