<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

class User implements ResponseObjectInterface
{
    /**
     * @var array
     */
    private const FORMAT = [
        'id' => ['int', true],
        'is_bot' => ['bool', true],
        'first_name' => ['string', true],
        'last_name' => ['string', false],
        'username' => ['string', false],
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
            $is_v = 'is_' . $v[0];
            if ($v[1] && (!property_exists($src, $k) || !$is_v($src->$k))) {
                throw new InvalidArgumentException('Required value missing: ' . $k);
            }

            if (property_exists($src, $k)) {
                $this->$k = $src->$k;
            }
        }
    }
}
