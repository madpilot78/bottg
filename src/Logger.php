<?php

namespace madpilot78\bottg;

/**
 * Custom logger class.
 *
 * Explicitly defining outside functions to get IDE autocomplete working
 * This logger does not throw execeptions, returns false when logging fails for whatever reason
 */
class Logger
{
    /**
     * Identification output at start of logged lines.
     *
     * @const LOGID
     */
    private const LOGID = 'bottg';

    /**
     * Known log levels
     *
     * @const LEVELS
     */
    private const LEVELS = [
        'debug',
        'info',
        'warn',
        'err'
    ];

    /**
     * Minimum logging level
     *
     * Levels below this one will not output messages
     * @const MINLEVEL
     */
    private const MINLEVEL = 1;

    /**
     * Format error message
     *
     * @param int $level relative to self::LEVELS
     * @param string $message
     * @param string $file
     * @param int $line
     * @return string|bool
     */
    private static function format(int $level, string $message, string $file = null, int $line = null)
    {
        $message = filter_var(
            trim($message),
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_STRIP_BACKTICK
        );

        $file = filter_var(
            trim($file),
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_STRIP_BACKTICK
        );

        if ((!is_string($message) || strlen($message) == 0) ||
            (strlen($file) == 0 && !is_null($line)) ||
            (!is_null($line) && (!is_numeric($line) || $line <= 0))) {
            return false;
        }

        $ret = self::LOGID . '(' . self::LEVELS[$level] . '): ' . $message;

        if (strlen($file) > 0) {
            $ret .= ' - ' . $file;
        }

        if (!is_null($line)) {
            $ret .= ':' . $line;
        }

        return $ret;
    }

    /**
     * Common logging code
     *
     * @paramint $level relative to self::LEVELS
     * @param string $message
     * @param string $file
     * @param int $line
     * @return bool
     */
    private static function logger(int $level, string $message, string $file = null, int $line = null)
    {
        if ($level < self::MINLEVEL) {
            return true;
        }

        if (($fmt = self::format($level, $message, $file, $line)) === false) {
            return false;
        }

        return error_log($fmt);
    }

    /**
     * Log debug messages
     *
     * @param string $message
     * @param string $file
     * @param int $line
     * @return bool
     */
    public static function debug(string $message, string $file = null, int $line = null)
    {
        return self::logger(0, $message, $file, $line);
    }

    /**
     * Log info messages
     *
     * @param string $message
     * @param string $file
     * @param int $line
     * @return bool
     */
    public static function info(string $message, string $file = null, int $line = null)
    {
        return self::logger(1, $message, $file, $line);
    }

    /**
     * Log warning messages
     *
     * @param string $message
     * @param string $file
     * @param int $line
     * @return bool
     */
    public static function warn(string $message, string $file = null, int $line = null)
    {
        return self::logger(2, $message, $file, $line);
    }

    /**
     * Log error messages
     *
     * @param string $message
     * @param string $file
     * @param int $line
     * @return bool
     */
    public static function err(string $message, string $file = null, int $line = null)
    {
        return self::logger(3, $message, $file, $line);
    }
}
