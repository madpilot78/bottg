<?php

namespace madpilot78\bottg;

use InvalidArgumentException;

class Config
{
    /**
     * @var int Default connection timeout
     */
    public const DEF_CONNECT_TIMEOUT = 10;

    /**
     * @var int Default response timeout
     */
    public const DEF_TIMEOUT = 30;

    /**
     * @var int Default polling timeout
     */
    public const DEF_POLL_TIMEOUT = 0;

    /**
     * @var int Default polling limit
     */
    public const DEF_POLL_LIMIT = 0;

    /**
     * @var int Connection timeout
     */
    private $connectTimeout;

    /**
     * @var int Response timeout
     */
    private $timeout;

    /**
     * @var int Polling timeout
     */
    private $pollTimeout;

    /**
     * @var int Polling limit
     */
    private $pollLimit;

    /**
     * Common code for setters.
     *
     * @param string $var
     * @param string $const
     * @param int    $val
     *
     * @return bool
     */
    private function optSetter(string $var, int $const, int $val = null)
    {
        if (is_null($val) || !is_numeric($val) || $val < 0) {
            $this->$var = $const;
            return;
        }

        $this->$var = $val;
    }

    public function __construct(
        int $ctimeout = null,
        int $timeout = null,
        int $polltimeout = null,
        int $polllimit = null
    )
    {
        $this->optSetter('connectTimeout', self::DEF_CONNECT_TIMEOUT, $ctimeout);
        $this->optSetter('timeout', self::DEF_TIMEOUT, $timeout);
        $this->optSetter('pollTimeout', self::DEF_POLL_TIMEOUT, $polltimeout);
        $this->optSetter('pollLimit', self::DEF_POLL_LIMIT, $polllimit);
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
     * pollTimeout setter.
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
