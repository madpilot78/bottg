<?php

namespace madpilot78\bottg\API;

use madpilot78\bottg\Exceptions\InvalidJSONException;

class Response implements ResponseInterface
{
    /**
     * @var string
     */
    private $raw;

    /**
     * @var object
     */
    public $content;

    /**
     * @var int
     */
    public $code;

    /**
     * Takes a json string from which to populate the object.
     *
     *
     * @param string $reply
     *
     * @throws InvalidJSONException
     *
     * @return bool
     */
    public function saveReply(string $reply)
    {
        $j = json_decode($reply);

        if (is_null($j)) {
            $this->content = null;
            $this->raw = null;

            throw new InvalidJSONException();
        }

        $this->raw = $reply;
        $this->content = $j; // stub

        return true;
    }

    /**
     * Returns the raw json string used to populate the object.
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }
}
