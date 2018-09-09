<?php

namespace madpilot78\bottg;

use InvalidArgumentException;

class Config
{
    /**
     * @var string Default token value
     */
    public const DEF_TOKEN = null;
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
     * @var string Identification output at start of logged lines
     */
    public const DEF_LOGID = 'bottg';

    /**
     * @var int Default minimum level
     */
    public const DEF_LOGMIN = Logger::INFO;

    /**
     * @var string BOT Token
     */
    private $token;

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
     * @var string Logging ID
     */
    private $logID;

    /**
     * @var int Minimum logging level
     */
    private $logMin;

    /**
     * Checks integer options for valid input.
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

    /**
     * Check for valid minimum level.
     *
     * @param int $level
     *
     * @return bool
     */
    private function checkLogmin(int $level = null)
    {
        if (in_array($level, [Logger::DEBUG, Logger::INFO, Logger::WARN, Logger::ERR], true)) {
            return true;
        }

        return false;
    }

    /**
     * Constructor allowing population via arguments.
     *
     * @param string $token       Bot token
     * @param string $logid       ID used in log headings
     * @param int    $logmin      Minimum logging level
     * @param int    $ctimeout    Connection timeout
     * @param int    $polltimeout Timeout when polling
     * @param int    $polllimit   Updates per request limit when poling
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(
        string $token = null,
        string $logid = null,
        int $logmin = null,
        int $ctimeout = null,
        int $timeout = null,
        int $polltimeout = null,
        int $polllimit = null
    ) {
        if (is_null($token)) {
            $this->token = self::DEF_TOKEN;
        } elseif (strlen($token) > 0) {
            $this->token = $token;
        } else {
            throw new InvalidArgumentException();
        }

        if (is_null($logid)) {
            $this->logID = self::DEF_LOGID;
        } elseif (strlen($logid) > 0) {
            $this->logID = $logid;
        } else {
            throw new InvalidArgumentException();
        }

        if (is_null($logmin)) {
            $this->logMin = self::DEF_LOGMIN;
        } elseif ($this->checkLogmin($logmin)) {
            $this->logMin = $logmin;
        } else {
            throw new InvalidArgumentException();
        }

        if (is_null($ctimeout)) {
            $this->connectTimeout = self::DEF_CONNECT_TIMEOUT;
        } elseif ($this->checkIntOpt($ctimeout)) {
            $this->connectTimeout = $ctimeout;
        } else {
            throw new InvalidArgumentException();
        }

        if (is_null($timeout)) {
            $this->timeout = self::DEF_TIMEOUT;
        } elseif ($this->checkIntOpt($timeout)) {
            $this->timeout = $timeout;
        } else {
            throw new InvalidArgumentException();
        }

        if (is_null($polltimeout)) {
            $this->pollTimeout = self::DEF_POLL_TIMEOUT;
        } elseif ($this->checkIntOpt($polltimeout)) {
            $this->pollTimeout = $polltimeout;
        } else {
            throw new InvalidArgumentException();
        }

        if (is_null($polllimit)) {
            $this->pollLimit = self::DEF_POLL_LIMIT;
        } elseif ($this->checkIntOpt($polllimit)) {
            $this->pollLimit = $polllimit;
        } else {
            throw new InvalidArgumentException();
        }
    }

    /**
     * Token setter.
     *
     * @param string $val
     *
     * @return bool
     */
    public function setToken(string $val = null)
    {
        if (is_null($val)) {
            $this->token = self::DEF_TOKEN;

            return true;
        }

        if (strlen($val) > 0) {
            $this->token = $val;

            return true;
        }

        return false;
    }

    /**
     * Token getter.
     *
     * @return bool
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * LogID setter.
     *
     * @param string $val
     *
     * @return bool
     */
    public function setLogID(string $val = null)
    {
        if (is_null($val)) {
            $this->logID = self::DEF_LOGID;

            return true;
        }

        if (strlen($val) > 0) {
            $this->logID = $val;

            return true;
        }

        return false;
    }

    /**
     * LogID getter.
     *
     * @return bool
     */
    public function getLogID()
    {
        return $this->logID;
    }

    /**
     * LogMin setter.
     *
     * @param int $val
     *
     * @return bool
     */
    public function setLogMin(int $val = null)
    {
        if (is_null($val)) {
            $this->logMin = self::DEF_LOGMIN;

            return true;
        }

        if ($this->checkLogmin($val)) {
            $this->logMin = $val;

            return true;
        }

        return false;
    }

    /**
     * connectTimeout getter.
     *
     * @return bool
     */
    public function getLogMin()
    {
        return $this->logMin;
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
