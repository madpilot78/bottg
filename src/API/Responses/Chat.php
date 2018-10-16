<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

class Chat implements ResponseObjectInterface
{
    /**
     * Present implementaation intentionally ignores parts returned by
     * unimplemented getChat method.
     */

    /**
     * @var array
     */
    private const FORMAT = [
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
