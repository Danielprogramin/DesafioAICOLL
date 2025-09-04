<?php

namespace App\Exceptions;

use Exception;

class EmpresaNotFoundException extends Exception
{
    public function __construct(string $identifier = null)
    {
        $message = $identifier
            ? "Empresa con identificador '{$identifier}' no encontrada."
            : "Empresa no encontrada.";

        parent::__construct($message, 404);
    }
}
