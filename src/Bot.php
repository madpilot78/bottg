<?php

namespace madpilot78\bottg;

class Bot
{
    /**
     * Default connection timeout.
     *
     * @const
     */
    public const DEF_CONNECT_TIMEOUT = 10;

    /**
     * Default response timeout.
     *
     * @const
     */
    public const DEF_TIMEOUT = 30;

    /**
     * Default polling timeout.
     *
     * @const
     */
    public const DEF_POLL_TIMEOUT = 0;

    /**
     * Default polling limit.
     *
     * @const
     */
    public const DEF_POLL_LIMIT = 0;

    /**
     * The token being used.
     *
     * @var
     */
    private $token;

    /**
     * Connection timeout.
     *
     * @var
     */
    private $connectTimeout;

    /**
     * Response timeout.
     *
     * @var
     */
    private $timeout;

    /**
     * Polling timeout
     *
     * @var
     */
    private $pollTimeout;

    /**
     * Polling limit
     *
     * @var
     */
    private $pollLimit;

    /**
     * Constructor, requires valid bot token.
     *
     * @param string $token
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
        $this->connectTimeout = self::DEF_CONNECT_TIMEOUT;
        $this->timeout = self::DEF_TIMEOUT;
        $this->pollTimeout = self::DEF_POLL_TIMEOUT;
        $this->pollLimit = self::DEF_POLL_LIMIT;
    }

    /**
     * Common code for setters
     *
     * @param string $var
     * @param string $const
     * @param int $val
     *
     * @return bool
     */
    private function optSetter(string $var, string $const, int $val = null)
    {
        if (is_null($val)) {
            $this->$var = $const;
            return true;
        }

        if (!is_numeric($val) || $val < 0) {
            return false;
        }

        $this->$var = $val;
        return true;
    }

    /**
     * connectTimeout setter.
     *
     * In seconds.
     * null -> restores default.
     * 0    -> infinity.
     *
     * @param int $val
     *
     * @return bool
     */
    public function setConnectTimeout(int $val = null)
    {
        return $this->optSetter('connectTimeout', self::DEF_CONNECT_TIMEOUT, $val);
    }

    /**
     * connectTimeout getter.
     *
     * @return bool
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * timeout setter.
     *
     * In seconds.
     * null -> restores default.
     * 0    -> infinity.
     *
     * @param int $val
     *
     * @return bool
     */
    public function setTimeout(int $val = null)
    {
        return $this->optSetter('timeout', self::DEF_TIMEOUT, $val);
    }

    /**
     * timeout getter.
     *
     * @return bool
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * pollTimeout setter
     *
     * In seconds.
     * null -> restores default.
     * 0    -> infinity.
     *
     * @param int $val
     *
     * @return bool
     */
    public function setPollTimeout(int $val = null)
    {
        return $this->optSetter('pollTimeout', self::DEF_POLL_TIMEOUT, $val);
    }

    /**
     * pollTimeout getter.
     *
     * @return bool
     */
    public function getPollTimeout()
    {
        return $this->pollTimeout;
    }

    /**
     * pollLimit setter.
     *
     * In seconds.
     * null -> restores default.
     * 0    -> infinity.
     *
     * @param int $val
     *
     * @return bool
     */
    public function setPollLimit(int $val = null)
    {
        return $this->optSetter('pollLimit', self::DEF_POLL_LIMIT, $val);
    }

    /**
     * pollLimit getter.
     *
     * @return bool
     */
    public function getPollLimit()
    {
        return $this->pollLimit;
    }
}
