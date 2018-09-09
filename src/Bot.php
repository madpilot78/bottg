<?php

namespace madpilot78\bottg;

use InvalidArgumentException;

class Bot
{
    /**
     * @var string Error message constant
     */
    private const NOTOKEN_ERROR = 'Token cannot be empty';

    /**
     * @var string The token being used
     */
    private $token;

    /**
     * @var \madpilot78\bottg\Config
     */
    public $config;

    /**
     * @var \madpilot78\bottg\Logger
     */
    private $logger;

    /**
     * Constructor, requires valid bot token.
     *
     * @param string|Config $confortoken
     * @param Logger        $logger
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct($confortoken, Logger $logger = null)
    {
        if (is_string($confortoken)) {
            if (strlen($confortoken) == 0) {
                throw new InvalidArgumentException(self::NOTOKEN_ERROR);
            }

            $this->config = new Config($confortoken);
        } elseif (is_object($confortoken) && get_class($confortoken) === 'madpilot78\bottg\Config') {
            $this->config = $confortoken;
        } else {
            throw new InvalidArgumentException('Token or Config object required');
        }

        if (is_null($logger)) {
            $logger = new Logger();
        }
        $this->logger = $logger;
    }
}
