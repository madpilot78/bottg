<?php

namespace madpilot78\bottg\API\Responses;

class WebhookInfo extends ResponseObject implements ResponseObjectInterface
{
    /**
     * @var array
     */
    protected $format = [
        'url'                       => ['string', true],
        'has_custom_certificate'    => ['bool', true],
        'pending_update_count'      => ['int', true],
        'last_error_date'           => ['int', false],
        'last_error_message'        => ['string', false],
        'max_connections'           => ['int', false],
        'allowed_updates'           => ['array', false]
    ];

    /**
     * @var string
     */
    public $url;

    /**
     * @var bool
     */
    public $has_custom_certificate;

    /**
     * @var int
     */
    public $pending_update_count;

    /**
     * @var int
     */
    public $last_error_date;

    /**
     * @var string
     */
    public $last_error_message;

    /**
     * @var int
     */
    public $max_connections;

    /**
     * @var array
     */
    public $allowed_updates;
}
