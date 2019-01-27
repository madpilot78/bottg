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
     * @var string Default proxy value (none)
     */
    public const DEF_PROXY = null;

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
     * @var string Proxy host
     */
    private $proxyHost;

    /**
     * @var int Proxy port
     */
    private $proxyPort;

    /**
     * @var string Proxy user
     */
    private $proxyUser;

    /**
     * @var string Proxy password
     */
    private $proxyPassword;

    /**
     * Checks integer options for valid input.
     *
     * @param int $val
     *
     * @return bool
     */
    private function checkIntOpt(int $val = null): bool
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
    private function checkLogmin(int $level = null): bool
    {
        if (in_array($level, [Logger::DEBUG, Logger::INFO, Logger::WARN, Logger::ERR], true)) {
            return true;
        }

        return false;
    }

    /**
     * Extract data from the proxy string.
     *
     * Doubles as consistency check.
     *
     * @param string $proxystr
     *
     * @return bool
     */
    private function saveProxy(string $proxyStr): bool
    {
        $proxyHost = $proxyPort = $proxyUser = $proxyPassword = null;

        if (($p = strpos($proxyStr, '@')) !== false) {
            if ($p == strlen($proxyStr) - 1) {
                return false;
            }

            list($auth, $host) = explode('@', $proxyStr);
        } else {
            $host = $proxyStr;
        }

        if (isset($auth)) {
            if (($s = strpos($auth, ':')) !== false) {
                if ($s == 0) {
                    return false;
                }

                list($proxyUser, $proxyPassword) = explode(':', $auth);
            } else {
                return false;
            }
        }

        $pos = strpos($host, ':');

        if ($pos === false) {
            $proxyHost = $host;
        } elseif ($pos === 0) {
            return false;
        } elseif ($pos < strlen($host)) {
            list($proxyHost, $proxyPort) = explode(':', $host);
        }

        $this->proxyHost = $proxyHost;
        $this->proxyPort = isset($proxyPort) ? $proxyPort : 8080;
        $this->proxyUser = $proxyUser;
        $this->proxyPassword = $proxyPassword;

        return true;
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
     * @param string $proxystr    The proxy setting string
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
        int $polllimit = null,
        string $proxystr = null
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

        if (is_null($proxystr) || strlen($proxystr) == 0) {
        } elseif (!$this->saveProxy($proxystr)) {
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
    public function setToken(string $val = null): bool
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
     * @return string
     */
    public function getToken(): ?string
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
    public function setLogID(string $val = null): bool
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
     * @return string
     */
    public function getLogID(): ?string
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
    public function setLogMin(int $val = null): bool
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
     * LogMin getter.
     *
     * @return int
     */
    public function getLogMin(): ?int
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
    public function setConnectTimeout(int $val = null): bool
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
     * @return int
     */
    public function getConnectTimeout(): ?int
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
    public function setTimeout(int $val = null): bool
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
     * @return int
     */
    public function getTimeout(): ?int
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
    public function setPollTimeout(int $val = null): bool
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
     * @return int
     */
    public function getPollTimeout(): ?int
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
    public function setPollLimit(int $val = null): bool
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
     * @return int
     */
    public function getPollLimit(): ?int
    {
        return $this->pollLimit;
    }

    /**
     * Proxy setter.
     *
     * Expect string with format:
     *      user:password@host
     *
     * @param string $val
     *
     * @return bool
     */
    public function setProxy(string $val = null): bool
    {
        if (is_null($val) || strlen($val) == 0) {
            $this->proxyHost = self::DEF_PROXY;
            $this->proxyPort = self::DEF_PROXY;
            $this->proxyUser = self::DEF_PROXY;
            $this->proxyPassword = self::DEF_PROXY;

            return true;
        }

        return $this->saveProxy($val);
    }

    /**
     * Proxy host getter.
     *
     * @return string
     */
    public function getProxyHost(): ?string
    {
        return $this->proxyHost;
    }

    /**
     * Proxy port getter.
     *
     * @return int
     */
    public function getProxyPort(): ?int
    {
        return $this->proxyPort;
    }

    /**
     * Proxy auth getter.
     *
     * @return string
     */
    public function getProxyAuth(): ?string
    {
        if (is_null($this->proxyUser) || strlen($this->proxyUser) == 0) {
            return null;
        }

        return $this->proxyUser . ':' . $this->proxyPassword;
    }
}
