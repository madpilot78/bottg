<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

class Chat extends ResponseObject implements ResponseObjectInterface
{
    /**
     * @var array
     */
    protected $format = [
        'id'                                => ['int', true],
        'type'                              => ['string', true],
        'title'                             => ['string', false],
        'username'                          => ['string', false],
        'first_name'                        => ['string', false],
        'last_name'                         => ['string', false],
        'all_members_are_administrators'    => ['bool', false]
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string optional
     */
    public $title;

    /**
     * @var string optional
     */
    public $username;

    /**
     * @var string optional
     */
    public $first_name;

    /**
     * @var string optional
     */
    public $last_name;

    /**
     * @var bool optional
     */
    public $all_members_are_administrators;
}
