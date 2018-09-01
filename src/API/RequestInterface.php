<?php

namespace madpilot78\bottg\API;

interface RequestInterface
{
    /**
     * @var int GET
     */
    public const GET = 0;

    /**
     * @var int GET
     */
    public const SUBMIT = 1;

    /**
     * @var int GET
     */
    public const MPART = 2;

    /**
     * @var int GET
     */
    public const JSON = 3;
}
