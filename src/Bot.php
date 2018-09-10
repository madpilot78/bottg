<?php

namespace madpilot78\bottg;

use InvalidArgumentException;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\Http\HttpInterface;

class Bot
{
    /**
     * @var string Error message constant
     */
    private const NOTOKEN_ERROR = 'Token cannot be empty';

    /**
     * @var \madpilot78\bottg\Config
     */
    public $config;

    /**
     * @var \madpilot78\bottg\Logger
     */
    private $logger;

    /**
     * @var \madpilot78\bottg\Http\HttpInterface
     */
    private $http;

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
    public function __construct($confortoken, Logger $logger = null, HttpInterface $http = null)
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

        $this->http = $http;
    }

    /**
     * Generic __call for Request methods.
     *
     * Will instantiate and run Request objects and return the reply.
     *
     * @param string $name
     * @param array  $args
     *
     * @throws InvalidArgumentException
     *
     * @return \madpilot78\bottg\API\Response
     */
    public function __call(string $name, array $args)
    {
        $class = '\\madpilot78\\bottg\\API\\' . $name;

        if (!class_exists($class)) {
            throw new InvalidArgumentException('Unknown method');
        }

        $req = new $class($this->config, $this->logger, $this->http);
        return $req->exec();
    }
}
