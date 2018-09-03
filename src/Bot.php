<?php

namespace madpilot78\bottg;

class Bot
{
    /**
     * @var string The token being used
     */
    private $token;

    /**
     * @var \madpilot78\bottg\Config
     */
    private $config;

    /**
     * Constructor, requires valid bot token.
     *
     * @param string $token
     *
     * @return void
     */
    public function __construct(string $token, Config $config = null)
    {
        $this->token = $token;

        if (is_null($config)) {
            $config = new Config();
        }
        $this->config = $config;
    }
}
