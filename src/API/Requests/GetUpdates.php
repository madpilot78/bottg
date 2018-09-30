<?php

namespace madpilot78\bottg\API\Requests;

use madpilot78\bottg\API\Request;
use madpilot78\bottg\API\RequestInterface;
use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\Logger;

/**
 * Implements the Telegram Bot API getUpdates.
 */
class GetUpdates extends Request implements RequestInterface
{
    /**
     * Constructor, passes correct arguments to upstream constructor.
     *
     * $args = [
     *      (int) offset
     * ]
     *
     * @param array         $args
     * @param Config        $config
     * @param Logger        $logger
     * @param HttpInterface $http
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(
        array $args,
        Config $config = null,
        Logger $logger = null,
        HttpInterface $http = null
    ) {
        $c = count($args);

        if ($c > 1) {
            throw new InvalidArgumentException('Wrong argument count');
        } elseif ($c == 0 || ($c == 1 && strlen($args[0]) == 0)) {
            $fields = [];
        } else {
            if (!is_int($args[0]) && !ctype_digit($args[0])) {
                throw new InvalidArgumentException('Offset must be numeric or null');
            }
            $fields = ['offset' => $args[0]];
        }

        parent::__construct(
            RequestInterface::GET,
            'getUpdates',
            $fields,
            $config,
            $logger,
            $http
        );
    }
}
