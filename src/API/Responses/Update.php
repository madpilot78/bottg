<?php

namespace madpilot78\bottg\API\Responses;

class Update extends ResponseObject implements ResponseObjectInterface
{
    /**
     * @var array
     */
    protected $format = [
        'update_id'             => ['int', true],
        'message'               => ['Message', false],
        'edited_message'        => ['Message', false],
        'channel_post'          => ['Message', false],
        'edited_channel_post'   => ['Message', false]
    ];

    /**
     * @var int
     */
    public $update_id;

    /**
     * @var \madpilot78\bottg\API\Replies\Message
     */
    public $message;

    /**
     * @var \madpilot78\bottg\API\Replies\Message
     */
    public $edited_message;

    /**
     * @var \madpilot78\bottg\API\Replies\Message
     */
    public $channel_post;

    /**
     * @var \madpilot78\bottg\API\Replies\Message
     */
    public $edited_channel_post;
}
