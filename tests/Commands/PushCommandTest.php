<?php

use App\Commands\PushCommand;
use Illuminate\Console\Command;

it('can run push command', function () {
    $this->artisan(PushCommand::class)
        ->expectsOutputToContain('Sending data...')
        ->expectsOutputToContain('Data successfully pushed.')
        ->assertExitCode(Command::SUCCESS);
});
