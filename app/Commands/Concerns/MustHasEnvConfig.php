<?php

namespace App\Commands\Concerns;

use App\Commands\ConfigCommand;
use App\Exceptions\MissingConfigurationException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

trait MustHasEnvConfig
{
    public function ensureEnvConfig(): void
    {
        if (! File::exists(Config::get('app.homeConfigDirectory').'.env')) {

            if (Config::get('app.env') === 'testing') {
                throw new MissingConfigurationException;
            }

            $this->call(ConfigCommand::class);
        }
    }
}
