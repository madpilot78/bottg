<?php

namespace madpilot78\bottg\API;

use madpilot78\bottg\Exceptions\InvalidJSONException;
use RuntimeException;

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
     * @throws RuntimeException
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
            $apiClass = '\\madpilot78\\bottg\\API\\Requests\\' . ucfirst($this->api);

            if (!class_exists($apiClass)) {
                throw new RuntimeException('Unknown or unsupported Telegram API');
            }

            if (ctype_upper(substr($apiClass::EXPECT, 0, 1))) {
                list($c, $q) = explode(':', $apiClass::EXPECT);
                $expectedClass = '\\madpilot78\\bottg\\API\\Responses\\' . $c;

                if (!class_exists($expectedClass)) {
                    throw new RuntimeException('Unknown or unsupporte Telegram reply'); // @codeCoverageIgnore
                }

                if (strlen($q)) {
                    if ($q == 'array') {
                        $this->result = [];
                        foreach ($j->result as $v) {
                            $this->result[] = new $expectedClass($v);
                        }
                    } else {
                        throw new RuntimeException('Unknown or unsupporte Telegram reply format'); // @codeCoverageIgnore
                    }
                } else {
                    $this->result = new $expectedClass($j->result);
                }
            } else {
                // result has a native type
                $this->result = $j->result;
            }
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
