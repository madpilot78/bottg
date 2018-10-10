<?php

namespace madpilot78\bottg\Exceptions;

class InvalidJSONException extends \RuntimeException
{
    /**
     * Custom constructor populating error code and error message
     *
     * @return void
     */
    public function __construct(Exception $previous = null)
    {
        $code = json_last_error();
        $message = json_last_error_msg();

        parent::__construct($message, $code, $previous);
    }
}
