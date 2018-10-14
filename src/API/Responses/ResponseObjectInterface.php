<?php

namespace madpilot78\bottg\API\Responses;

interface ResponseObjectInterface
{
    /**
     * Constructor using the relevant part of the object extracted from
     * the json reply.
     *
     * @param object $src
     * 
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(object $src);
}
