<?php

namespace madpilot78\bottg;

class Bot
{
    /**
     * The token being used.
     *
     * @var
     */
    private $token;

    /**
     * Constructor, requires valid bot token.
     *
     * @param   string $token
     * @return  void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
