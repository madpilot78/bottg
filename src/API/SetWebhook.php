<?php

namespace madpilot78\bottg\API;

use CURLFile;
use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\Logger;

/**
 * Implements the Telegram Bot API setWebhook.
 */
class SetWebhook extends Request implements RequestInterface
{
    /**
     * Constructor, passes correct arguments to upstream constructor.
     *
     * NOTE: max_connections and allowed_updates to be implmented
     *
     *
     * @param string        $url
     * @param string        $cert
     * @param Config        $config
     * @param Logger        $logger
     * @param HttpInterface $http
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(
        string $url,
        string $cert = null,
        Config $config = null,
        Logger $logger = null,
        HttpInterface $http = null
    ) {
        if (strlen($url) == 0) {
            throw new InvalidArgumentException('URL cannot be empty');
        }

        $fields = [
            'url' => $url
        ];

        if (!is_null($cert)) {
            if (is_readable($cert)) {
                $fields['certificate'] = new CURLFile($cert, 'application/x-pem-file', 'certificate');
            } else {
                throw new InvalidArgumentException('Cert file must exist and be readable');
            }
        }

        parent::__construct(
            RequestInterface::MPART,
            'setWebhook',
            $fields,
            $config,
            $logger,
            $http
        );
    }
}
