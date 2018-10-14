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
     * @var string;
     */
    private $api;

    /**
     * @var bool
     */
    public $ok;

    /**
     * @var object|bool
     */
    public $result;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $error_code;

    /**
     * Constructor taking required arguments.
     *
     * @param string $api   The requested API
     * @param string $reply
     * @param int    $code  HTTP Code, assumed 200 if omitted
     *
     * @throws InvalidJSONException
     *
     * @return void
     */
    public function __construct(string $api, string $reply = null, int $code = 200)
    {
        $this->api = $api;
        $this->code = $code;
        if (!is_null($reply)) {
            $this->saveReply($reply);
        }
    }

    /**
     * Takes a json string from which to populate the object.
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
            $this->result = null;
            $this->raw = null;

            throw new InvalidJSONException();
        }

        $this->raw = $reply;
        $this->ok = $j->ok;
        if ($this->ok) {
            $this->result = $j->result; // stub
        } else {
            $this->error_code = $j->error_code;
            $this->description = $j->description;
        }

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
