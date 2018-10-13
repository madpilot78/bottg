<?php

namespace madpilot78\bottg\API;

interface ResponseInterface
{
    /**
     * Constructor taking required arguments.
     *
     * @param string $reply optional
     * @param int $code     HTTP Code, assumed 200 if omitted
     *
     * @throws InvalidJSONException
     *
     * @return void
     */
    public function __construct(string $reply = null, int $code = 200);

    /**
     * Takes a json string from which to populate the object.
     *
     * @param string $reply
     *
     * @throws InvalidJSONException
     *
     * @return bool
     */
    public function saveReply(string $reply);

    /**
     * Returns the raw json string used to populate the object.
     *
     * @return string
     */
    public function getRaw();
}
