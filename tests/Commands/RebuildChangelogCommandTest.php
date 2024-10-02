<?php

use App\Commands\RebuildChangelogCommand;
use Illuminate\Console\Command;

it('can run CHANGELOG rebuild command', function () {
    $this->artisan(RebuildChangelogCommand::class)
        ->expectsOutputToContain('Rebuilding CHANGELOG...')
        ->expectsOutputToContain('CHANGELOG successfully rebuilt.')
        ->assertExitCode(Command::SUCCESS);
});
