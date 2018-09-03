<?php

namespace madpilot78\bottg;

/**
 * Custom logger class.
 *
 * This logger does not throw execeptions, returns false when logging fails for whatever reason
 */
class Logger
{
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
     * @var madpilot78\bottg\Config
     */
    private $config;

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
     * Constructor.
     *
     * Accepts optional values, otherwiise applied defauts
     *
     * @param string $logID
     * @param string $min
     *
     * @return void
     */
    public function __construct(Config $config = null)
    {
        if (is_null($config)) {
            $config = new Config();
        }

        $this->config = $config;
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

        $ret = $this->config->getLogID() . ' (' . self::LEVELS[$level] . '): ' . $message;

        if (strlen($file) > 0) {
            $ret .= ' - ' . $file;
        }

        if (!is_null($line)) {
            $ret .= ':' . $line;
        }

        return $ret;
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
        if ($level < $this->config->getLogMin()) {
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
