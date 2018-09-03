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
     * @var \madpilot78\bottg\Logger
     */
    private $logger;

    /**
     * Constructor, requires valid bot token.
     *
     * @param string $token
     *
     * @return void
     */
    public function __construct(string $token, Config $config = null, Logger $logger = null)
    {
        $this->token = $token;

        if (is_null($config)) {
            $config = new Config();
        }
        $this->config = $config;

        if (is_null($logger)) {
            $logger = new Logger();
        }
        $this->logger = $logger;
    }
}
