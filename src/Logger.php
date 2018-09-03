<?php

namespace madpilot78\bottg;

/**
 * Custom logger class.
 *
 * This logger does not throw execeptions, returns false when logging fails for whatever reason
 */
class Logger
{
    /**
     * @var string Identification output at start of logged lines
     */
    private const DEF_LOGID = 'bottg';

    /*
     * Known log levels
     */
    public const DEBUG = 0;
    public const INFO = 1;
    public const WARN = 2;
    public const ERR = 3;
    private const LEVELS = [
        'DEBUG',
        'INFO',
        'WARN',
        'ERR'
    ];

    /**
     * @var int Default minimum level
     */
    public const DEF_MIN = self::INFO;

    /**
     * @var string ID to be output for log lines
     */
    private $logID;

    /**
     * Minimum logging level.
     *
     * Levels below this one will not output messages
     *
     * @var int
     */
    private $minimumLevel;

    /**
     * Sanitize strings which are being output.
     *
     * @param string $s
     *
     * @return string
     */
    private function filterString(string $s = null)
    {
        return filter_var(
            trim($s),
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_STRIP_BACKTICK
        );
    }

    /**
     * Check for valid minimum level.
     *
     * @param int $level
     *
     * @return void
     */
    private function checkLevel(int $level = null)
    {
        if (is_null($level) || !in_array($level, [self::DEBUG, self::INFO, self::WARN, self::ERR])) {
            return false;
        }

        return true;
    }

    /**
     * Constructor.
     *
     * Accepts optional values, otherwiise applied defauts
     *
     * @param string $logID
     * @param string $min
     *
     * @return void
     */
    public function __construct(string $logID = null, int $min = null)
    {
        if (is_null($logID) || strlen($logID) == 0) {
            $this->logID = self::DEF_LOGID;
        } else {
            $this->logID = $this->filterString($logID);
        }

        if ($this->checkLevel($min)) {
            $this->minimumLevel = $min;
        } else {
            $this->minimumLevel = self::DEF_MIN;
        }
    }

    /**
     * Format error message.
     *
     * @param int    $level   relative to self::LEVELS
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @return string|bool
     */
    private function format(int $level, string $message, string $file = null, int $line = null)
    {
        $message = $this->filterString($message);

        $file = $this->filterString($file);

        if (strlen($message) == 0 ||
            (strlen($file) == 0 && !is_null($line)) ||
            (!is_null($line) && (!is_numeric($line) || $line <= 0))) {
            return false;
        }

        $ret = $this->logID . '(' . self::LEVELS[$level] . '): ' . $message;

        if (strlen($file) > 0) {
            $ret .= ' - ' . $file;
        }

        if (!is_null($line)) {
            $ret .= ':' . $line;
        }

        return $ret;
    }

    /**
     * minimumLevel setter.
     *
     * @param int level
     *
     * @return void
     */
    public function setMinimumLevel(int $min)
    {
        if ($this->checkLevel($min)) {
            $this->minimumLevel = $min;

            return true;
        }

        return false;
    }

    /**
     * minimumLevel getter.
     *
     * @return int
     */
    public function getMinimumLevel()
    {
        return $this->minimumLevel;
    }

    /**
     * Common logging code.
     *
     * @paramint $level relative to self::LEVELS
     *
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @return bool
     */
    private function logger(int $level, string $message, string $file = null, int $line = null)
    {
        if ($level < $this->minimumLevel) {
            return true;
        }

        if (($fmt = $this->format($level, $message, $file, $line)) === false) {
            return false;
        }

        return error_log($fmt);
    }

    /**
     * Log debug messages.
     *
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @return bool
     */
    public function debug(string $message, string $file = null, int $line = null)
    {
        return $this->logger(self::DEBUG, $message, $file, $line);
    }

    /**
     * Log info messages.
     *
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @return bool
     */
    public function info(string $message, string $file = null, int $line = null)
    {
        return $this->logger(self::INFO, $message, $file, $line);
    }

    /**
     * Log warning messages.
     *
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @return bool
     */
    public function warn(string $message, string $file = null, int $line = null)
    {
        return $this->logger(self::WARN, $message, $file, $line);
    }

    /**
     * Log error messages.
     *
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @return bool
     */
    public function err(string $message, string $file = null, int $line = null)
    {
        return $this->logger(self::ERR, $message, $file, $line);
    }
}
