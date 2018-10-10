<?php

namespace madpilot78\bottg\API;

interface ResponseInterface
{
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
