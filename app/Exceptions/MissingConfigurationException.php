<?php

namespace App\Exceptions;

use Exception;

class MissingConfigurationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Missing configuration. Please run "gitbro config".');
    }
}
