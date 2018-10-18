<?php

namespace madpilot78\bottg\API\Requests;

use madpilot78\bottg\API\Request;
use madpilot78\bottg\API\RequestInterface;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\Logger;

/**
 * Implements the Telegram Bot API deleteWebhook.
 */
class DeleteWebhook extends Request implements RequestInterface
{
    /**
     * @var string
     */
    public const EXPECT = 'bool';

    /**
     * Constructor, passes correct arguments to upstream constructor.
     *
     * @param array         $args   UNUSED
     * @param Config        $config
     * @param Logger        $logger
     * @param HttpInterface $http
     *
     * @return void
     */
    public function __construct(
        array $args,
        Config $config = null,
        Logger $logger = null,
        HttpInterface $http = null
    ) {
        parent::__construct(
            RequestInterface::MPART,
            'deleteWebhook',
            null,
            $config,
            $logger,
            $http
        );
    }
}
