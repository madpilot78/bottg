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
     * Checks integer options for valid input
     *
     * @param int $val
     *
     * @return bool
     */
    private function checkIntOpt(int $val = null)
    {
        if (!is_numeric($val) || $val < 0) {
            return false;
        }

        return true;
    }

    public function __construct(
        int $ctimeout = null,
        int $timeout = null,
        int $polltimeout = null,
        int $polllimit = null
    ) {
        if (is_null($ctimeout)) {
            $this->connectTimeout = self::DEF_CONNECT_TIMEOUT;
        } elseif ($this->checkIntOpt($ctimeout)) {
            $this->connectTimeout = $ctimeout;
        } else {
            throw new InvalidArgumentException;
        }

        if (is_null($timeout)) {
            $this->timeout = self::DEF_TIMEOUT;
        } elseif ($this->checkIntOpt($timeout)) {
            $this->timeout = $timeout;
        } else {
            throw new InvalidArgumentException;
        }

        if (is_null($polltimeout)) {
            $this->pollTimeout = self::DEF_POLL_TIMEOUT;
        } elseif ($this->checkIntOpt($polltimeout)) {
            $this->pollTimeout = $polltimeout;
        } else {
            throw new InvalidArgumentException;
        }

        if (is_null($polllimit)) {
            $this->pollLimit = self::DEF_POLL_LIMIT;
        } elseif ($this->checkIntOpt($polllimit)) {
            $this->pollLimit = $polllimit;
        } else {
            throw new InvalidArgumentException;
        }
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
        if (is_null($val)) {
            $this->connectTimeout = self::DEF_CONNECT_TIMEOUT;
            return true;
        }

        if ($this->checkIntOpt($val)) {
            $this->connectTimeout = $val;
            return true;
        }

        return false;
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
        if (is_null($val)) {
            $this->timeout = self::DEF_TIMEOUT;

            return true;
        }

        if ($this->checkIntOpt($val)) {
            $this->timeout = $val;

            return true;
        }

        return false;
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
        if (is_null($val)) {
            $this->pollTimeout = self::DEF_POLL_TIMEOUT;

            return true;
        }

        if ($this->checkIntOpt($val)) {
            $this->pollTimeout = $val;

            return true;
        }

        return false;
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
        if (is_null($val)) {
            $this->pollLimit = self::DEF_POLL_LIMIT;

            return true;
        }

        if ($this->checkIntOpt($val)) {
            $this->pollLimit = $val;

            return true;
        }

        return false;
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
