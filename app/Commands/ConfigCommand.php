<?php

namespace App\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\form;
use function Laravel\Prompts\info;

class ConfigCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'config';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Save the configuration file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $responses = form()
            ->intro('GITBRO:config')
            ->text('Github Personal Access Token?', required: true, name: 'token')
            ->submit();

        if (! File::isDirectory(Config::get('app.homeConfigDirectory'))) {
            File::makeDirectory(Config::get('app.homeConfigDirectory'));
        }

        if (! File::exists(Config::get('app.homeConfigDirectory').'git.php')) {
            File::copy(
                base_path('vendor/ibrostudio/laravel-git/config/git.php'),
                Config::get('app.homeConfigDirectory').'git.php');
        }

        File::put(
            Config::get('app.homeConfigDirectory').'.env',
            'GITHUB_PERSONAL_ACCESS_TOKEN='.$responses['token']
        );

        info('Configuration files written in '.Config::get('app.homeConfigDirectory'));

        return self::SUCCESS;
    }
}
