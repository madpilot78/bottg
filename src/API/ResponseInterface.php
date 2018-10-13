<?php

namespace madpilot78\bottg\API;

interface ResponseInterface
{
    /**
     * Constructor taking required arguments.
     *
     * @param string $reply
     * @param int $code     HTTP Code, assumed 200 if omitted
     *
     * @throws InvalidJSONException
     *
     * @return void
     */
    public __construct(string $reply, int $code);

    /**
     * Takes a json string from which to populate the object.
     *
     * @param string $reply
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
