<?php

namespace madpilot78\bottg\API;

class Response implements ResponseInterface
{
    /**
     * @var string
     */
    public $raw;

    /**
     * @var string
     */
    public $content;

    /**
     * @var int
     */
    public $code;
}
