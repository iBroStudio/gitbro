<?php

namespace App\Commands\Concerns;

use App\Exceptions\DirectoryNotFoundException;
use App\Exceptions\InvalidRepositoryException;
use IBroStudio\Git\GitRepository;
use Illuminate\Process\Exceptions\ProcessFailedException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

trait HasRepository
{
    public function bindRepository(): void
    {
        $directory = $this->option('directory') ?? Config::get('app.repositoryDirectory');

        if (! File::isDirectory($directory)) {
            throw new DirectoryNotFoundException($directory);
        }

        try {
            $this->repository = GitRepository::open($directory);
        } catch (ProcessFailedException $e) {
            throw new InvalidRepositoryException;
        }
    }
}
