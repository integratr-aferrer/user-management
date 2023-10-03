<?php

namespace UserPackage\App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Http;

class ErrorException extends Exception
{
    private $result;

    /**
     * @var array
     */
    private $loggerData;

    /**
     * ErrorException constructor.
     * @param $result
     * @param $code
     * @param string $message
     * @param array $loggerData
     */
    public function __construct(
        $result,
        $code,
        $message = 'Oops! We encountered an unexpected problem. Please try again.',
        $loggerData = []
    ) {
        $this->result = $result;

        $this->loggerData = $loggerData;

        parent::__construct($message, $code);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }
}
