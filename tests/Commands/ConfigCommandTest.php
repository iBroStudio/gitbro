<?php

use App\Commands\ConfigCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

it('can run the config command', function () {
    Prompt::fake([
        'g', 'i', 't', 'h', 'u', 'b', '_', 't', 'o', 'k', 'e', 'n',
        Key::ENTER,
    ]);

    File::copy(
        Config::get('app.homeConfigDirectory').'.env',
        Config::get('app.homeConfigDirectory').'.env.bak'
    );

    File::delete(Config::get('app.homeConfigDirectory').'git.php');

    $this->artisan(ConfigCommand::class)
        ->expectsOutputToContain('Github Personal Access Token?')
        ->expectsOutputToContain('Configuration files written in '.Config::get('app.homeConfigDirectory'))
        ->assertExitCode(Command::SUCCESS);

    expect(
        File::get(Config::get('app.homeConfigDirectory').'.env')
    )->toContain('GITHUB_PERSONAL_ACCESS_TOKEN=github_token')
        ->and(File::exists(Config::get('app.homeConfigDirectory').'git.php'))->toBeTrue();

    File::copy(
        Config::get('app.homeConfigDirectory').'.env.bak',
        Config::get('app.homeConfigDirectory').'.env'
    );
    File::delete(Config::get('app.homeConfigDirectory').'.env.bak');
});
