<?php

use App\Commands\AddTemplateCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

it('can run the add template command', function () {
    Prompt::fake([
        'g', 'i', 't', '@', 'g', 'i', 't', 'h', 'u', 'b', '.', 'c', 'o', 'm', ':',
        'i', 'B', 'r', 'o', 'S', 't', 'u', 'd', 'i', 'o', '/',
        'a', 's', 't', 'r', 'o', '-', 's', 'k', 'e', 'l', 'e', 't', 'o', 'n',
        '.', 'g', 'i', 't',
        Key::ENTER, //git ssh url
    ]);

    $this->artisan(AddTemplateCommand::class)
        ->expectsOutputToContain('Current templates:')
        ->expectsOutputToContain('Enter the Git SSH repository url')
        ->expectsOutputToContain('Template successfully added.')
        ->assertExitCode(Command::SUCCESS);

    File::copy(
        base_path('vendor/ibrostudio/laravel-git/config/git.php'),
        Config::get('app.homeConfigDirectory').'git.php'
    );
});
