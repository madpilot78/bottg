<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

class User extends ResponseObject implements ResponseObjectInterface
{
    /**
     * @var array
     */
    protected $format = [
        'id'            => ['int', true],
        'is_bot'        => ['bool', true],
        'first_name'    => ['string', true],
        'last_name'     => ['string', false],
        'username'      => ['string', false],
        'language_code' => ['string', false]
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var bool
     */
    public $is_bot;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string optional
     */
    public $last_name;

    /**
     * @var string optional
     */
    public $username;

    /**
     * @var string optional
     */
    public $language_code;
}
