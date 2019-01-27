<?php

namespace madpilot78\bottg\Http;

use madpilot78\bottg\Exceptions\HttpException;

/**
 * Http implementation backed by curl.
 *
 * @codeCoverageIgnore
 */
class Curl implements HttpInterface
{
    private $ch;

    /**
     * Initializes curl.
     *
     * @throws HttpException if initialization fails.
     *
     * @return void
     */
    public function __construct()
    {
        if (($this->ch = curl_init()) === false) {
            $this->ch = null;

            throw new HttpException('Curl initialization failed');
        }
    }

    /**
     * Destructor, closes curl library.
     *
     * @return void
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }

    /**
     * Sets options.
     *
     * @param array $options
     *
     * @return bool
     */
    public function setOpts(array $options): bool
    {
        return curl_setopt_array($this->ch, $options);
    }

    /**
     * Executes request.
     *
     * @return bool|string
     */
    public function exec()
    {
        return curl_exec($this->ch);
    }

    /**
     * Get connection info.
     *
     * @return array
     */
    public function getInfo()
    {
        return curl_getinfo($this->ch);
    }

    /**
     * Get error.
     *
     * @return array
     */
    public function getError(): array
    {
        return [
            'errno' => curl_errno($this->ch),
            'error' => curl_error($this->ch)
        ];
    }
}
