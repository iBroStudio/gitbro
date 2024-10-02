<?php

use App\Commands\CommitCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

it('can run commit command', function () {
    File::append(
        path: base_path('playground').'/test/test.txt',
        data: 'test'
    );

    Prompt::fake([
        Key::DOWN, Key::ENTER, //commit type: feat
        't', 'e', 's', 't', ' ', 'c', 'o', 'm', 'm', 'i', 't', Key::ENTER, //message
        Key::DOWN, Key::ENTER, //confirm sync: false
    ]);

    $this->artisan(CommitCommand::class)
        ->expectsOutputToContain('Select commit type')
        ->expectsOutputToContain('Message?')
        ->expectsOutputToContain('Changes successfully commited!')
        ->expectsOutputToContain('Do want to push to the remote?')
        ->assertExitCode(Command::SUCCESS);
});

it('can run commit command and sync', function () {
    File::put(
        path: base_path('playground').'/test/test.txt',
        contents: File::get(path: base_path('playground').'/test/test.txt') === 'test' ? '' : 'test'
    );

    Prompt::fake([
        Key::DOWN, Key::ENTER, //commit type: feat
        't', 'e', 's', 't', ' ', 'c', 'o', 'm', 'm', 'i', 't', Key::ENTER, //message
        Key::ENTER, //confirm sync: true
    ]);

    $this->artisan(CommitCommand::class)
        ->expectsOutputToContain('Data successfully synced.')
        ->assertExitCode(Command::SUCCESS);
});

it('prevents commit from running if there is change', function () {
    $this->artisan(CommitCommand::class)
        ->expectsOutputToContain('Nothing to commit.')
        ->assertExitCode(Command::FAILURE);
});
