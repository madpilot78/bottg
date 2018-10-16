<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

class Message implements ResponseObjectInterface
{
    /**
     * @var array
     */
    private const FORMAT = [
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

        /**
     * Populates object with data from relevant decoded reply part.
     *
     * @param object $src
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(object $src)
    {
        foreach (self::FORMAT as $k => $v) {
            if ($v[1] && !property_exists($src, $k)) {
                throw new InvalidArgumentException('Required value missing: ' . $k);
            } else if (property_exists($src, $k)) {

                $class = null;

                // if $v[0] starts with an upper case it's a class name
                if (ctype_upper(substr($v[0], 0, 1))) {
                    $class = '\\madpilot78\\bottg\\API\\Responses\\' . $v[0];
                    $valid = class_exists($class);
                } else {
                    $is_v = 'is_' . $v[0];
                    $valid = $is_v($src->$k);
                }

                if (!$valid) {
                    throw new InvalidArgumentException('Invalid value: ' . $k);
                }

                if (property_exists($src, $k)) {
                    if (isset($class)) {
                        // Empty properties are converted to null.
                        $t = (array)$src->$k;

                        if (empty($t)) {
                            $this->$k = null;
                        } else {
                            $this->$k = new $class($src->$k);
                        }
                    } else {
                        $this->$k = $src->$k;
                    }
                }
            }
        }
    }
}
