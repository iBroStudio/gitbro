<?php

use App\Commands\CommitCommand;
use App\Exceptions\InvalidRepositoryException;
use Illuminate\Support\Facades\Config;

it('has a repository', function () {
    new CommitCommand;
})->throwsNoExceptions();

it('has no repository', function () {
    Config::set('app.repositoryDirectory', Config::get('app.workingDirectory'));
    new CommitCommand;
})->throws(InvalidRepositoryException::class);
