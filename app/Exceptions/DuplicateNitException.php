<?php

namespace App\Exceptions;

use Exception;

class DuplicateNitException extends Exception
{
    public function __construct(string $nit)
    {
        parent::__construct("El NIT '{$nit}' ya está registrado en el sistema.", 409);
    }
}
