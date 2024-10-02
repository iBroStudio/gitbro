<?php

namespace App\Commands;

use AllowDynamicProperties;
use App\Commands\Concerns\HasRepository;
use App\Commands\Concerns\MustHasEnvConfig;
use LaravelZero\Framework\Commands\Command;

#[AllowDynamicProperties]
abstract class AbstractGitbroCommand extends Command
{
    use HasRepository;
    use MustHasEnvConfig;

    abstract public function make(): int;

    public function handle(): int
    {
        $this->ensureEnvConfig();

        if (property_exists($this, 'repository')) {
            $this->bindRepository();
        }

        return $this->make();
    }
}
