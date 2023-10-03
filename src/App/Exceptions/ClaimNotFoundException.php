<?php

namespace UserPackage\App\Exceptions;

use Exception;

class ClaimNotFoundException extends Exception
{
    /**
     * ClaimNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Unauthorized access.', 401);
    }
}
