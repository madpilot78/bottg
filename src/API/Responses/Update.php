<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

class Update implements ResponseObjectInterface
{
    /**
     * @var array
     */
    private const FORMAT = [
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
            } elseif (property_exists($src, $k)) {
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
                        $t = (array) $src->$k;

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
