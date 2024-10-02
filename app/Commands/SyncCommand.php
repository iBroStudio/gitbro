<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;

class SyncCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'sync {--directory=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Pull then push data to remote repository';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        intro('GITBRO:sync');

        spin(
            callback: function () {
                if (
                    $this->callSilent(
                        PullCommand::class,
                        ['--directory' => $this->option('directory')]
                    ) === self::FAILURE) {
                    return self::FAILURE;
                }

                $this->callSilent(
                    PushCommand::class,
                    ['--directory' => $this->option('directory')]
                );
            },
            message: 'Syncing data...'
        );

        info('Data successfully synced.');

        return self::SUCCESS;
    }
}
