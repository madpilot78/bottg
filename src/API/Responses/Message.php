<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

class Message extends ResponseObject implements ResponseObjectInterface
{
    /**
     * @var array
     */
    protected $format = [
        'message_id'                => ['int', true],
        'from'                      => ['User', true], // May be an empty object, tranformed in null
        'date'                      => ['int', true],
        'chat'                      => ['Chat', false],
        'forward_from'              => ['User', false],
        'forward_from_chat'         => ['Chat', false],
        'forward_from_message_id'   => ['int', false],
        'forward_signature'         => ['string', false],
        'forward_date'              => ['int', false],
        'reply_to_message'          => ['Message', false],
        'edit_date'                 => ['int', false],
        'media_group_id'            => ['string', false],
        'author_signature'          => ['string', false],
        'text'                      => ['string', false]
    ];

    /**
     * @var int
     */
    public $message_id;

    /**
     * @var \madpilot78\bottg\API\Replies\User
     */
    public $from;

    /**
     * @var int
     */
    public $date;

    /**
     * @var \madpilot78\bottg\API\Replies\Chat
     */
    public $chat;

    /**
     * @var \madpilot78\bottg\API\Replies\User
     */
    public $forward_from;

    /**
     * @var \madpilot78\bottg\API\Replies\Chat
     */
    public $forward_from_chat;

    /**
     * @var int
     */
    public $forward_from_message_id;

    /**
     * @var string
     */
    public $forward_signature;

    /**
     * @var int
     */
    public $forward_date;

    /**
     * @var \madpilot78\bottg\API\Replies\Message
     */
    public $reply_to_message;

    /**
     * @var int
     */
    public $edit_date;

    /**
     * @var string
     */
    public $media_group_id;

    /**
     * @var string
     */
    public $author_signature;

    /**
     * @var string
     */
    public $text;
}
