<?php

namespace madpilot78\bottg\API;

/**
 * Implements the Telegram Bot API sendChatAction
 */
class SendChatAction extends Request implements RequestInterface
{
    /**
     * Constructor, passes correct arguments to upstream constructor.
     *
     * @param HttpInterface $http
     * @param Config        $config
     * @param Logger        $logger
     *
     * @return void
     */
    public function __construct(
        Config $config = null,
        Logger $logger = null,
        HttpInterface $http = null
    ) {
        parent::__construct(
            RequestInterface::JSON,
            'sendChatAction',
            $http,
            $config,
            $logger
        );
    }
}
