<?php

namespace madpilot78\bottg\Http;

/**
 * Interface for a class wrapping http calls.
 *
 * Laid out to wrap curl PHP calls.
 */
interface HttpInterface
{
    /**
     * Sets options.
     *
     * @param array $options
     *
     * @return bool
     */
    public function setOpts(array $options);

    /**
     * Executes request.
     *
     * @return bool|string
     */
    public function exec();

    /**
     * Get connection info.
     *
     * @return array
     */
    public function getInfo();

    /**
     * Get error.
     *
     * @return array
     */
    public function getError();
}
