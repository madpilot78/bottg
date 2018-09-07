<?php

namespace madpilot78\bottg\API;

/**
 * Implements the Telegram Bot API getMe.
 */
class GetMe extends Request implements RequestInterface
{
    /**
     * Constructor, passes correct arguments to upstream constructor.
     *
     * @param Config        $config
     * @param Logger        $logger
     * @param HttpInterface $http
     *
     * @return void
     */
    public function __construct(
        Config $config = null,
        Logger $logger = null,
        HttpInterface $http = null
    ) {
        parent::__construct(
            RequestInterface::GET,
            'getMe',
            $http,
            $config,
            $logger
        );
    }
}
