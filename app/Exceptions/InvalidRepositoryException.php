<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Config;

class InvalidRepositoryException extends Exception
{
    public function __construct()
    {
        parent::__construct(Config::get('app.repositoryDirectory').' is not a valid repository.');
    }
}
