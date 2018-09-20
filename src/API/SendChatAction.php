<?php

namespace madpilot78\bottg\API;

use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\Logger;

/**
 * Implements the Telegram Bot API sendChatAction.
 */
class SendChatAction extends Request implements RequestInterface
{
    /**
     * @var array
     */
    private const KNOWN_ACTIONS = [
        'typing',
        'upload_photo',
        'record_video',
        'upload_video',
        'record_audio',
        'upload_audio',
        'upload_document',
        'find_location',
        'record_video_note',
        'upload_video_note'
    ];

    /**
     * Constructor, passes correct arguments to upstream constructor.
     *
     * $args = [
     *      (string) chat ID,
     *      (string) action
     * ]
     *
     * @param Array         $args
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

        if ($c != 2) {
            throw new InvalidArgumentException('Wrong argument count');
        }

        $this->checkChatID($args[0]);

        if (!in_array($args[1], self::KNOWN_ACTIONS, true)) {
            throw new InvalidArgumentException('Unknown chat action requested');
        }

        parent::__construct(
            RequestInterface::JSON,
            'sendChatAction',
            [
                'chat_id' => $args[0],
                'action'  => $args[1]
            ],
            $config,
            $logger,
            $http
        );
    }
}
