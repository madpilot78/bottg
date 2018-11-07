<?php

namespace madpilot78\bottg\API\Requests;

use InvalidArgumentException;
use madpilot78\bottg\API\Request;
use madpilot78\bottg\API\RequestInterface;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\Logger;

/**
 * Implements the Telegram Bot API getUpdates.
 */
class GetUpdates extends Request implements RequestInterface
{
    /**
     * @var array
     */
    public const SUPPORTED_UPDATES = [
        'message',
        'edited_message',
        'channel_post',
        'edited_channel_post'
    ];

    /**
     * @var string
     */
    public const EXPECT = 'Update:array';

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
        $fields = ['allowed_updates' => self::SUPPORTED_UPDATES];

        $c = count($args);

        if (count($args) > 2) {
            throw new InvalidArgumentException('Wrong argument count');
        }

        if (isset($args[0])) {
            if (!is_int($args[0]) && !ctype_digit($args[0])) {
                throw new InvalidArgumentException('Offset must be numeric or null');
            }
            $fields += ['offset' => $args[0]];
        }

        if (isset($args[1])) {
            if (!is_array($args[1])) {
                throw new InvalidArgumentException('Wrong argument type');
            }
            if (!(count(array_intersect($args[1], self::SUPPORTED_UPDATES)) == count($args[1]))) {
                throw new InvalidArgumentException('Unknown update type');
            }
            $fields['allowed_updates'] = $args[1];
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
