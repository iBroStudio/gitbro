<?php

namespace App\Exceptions;

use Exception;

class DirectoryNotFoundException extends Exception
{
    public function __construct(string $directory)
    {
        parent::__construct($directory.' directory not found.');
    }
}
