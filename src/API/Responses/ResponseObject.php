<?php

namespace madpilot78\bottg\API\Responses;

use InvalidArgumentException;

abstract class ResponseObject implements ResponseObjectInterface
{
    /**
     * @var array
     */
    protected $format;

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
        foreach ($this->format as $k => $v) {
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
