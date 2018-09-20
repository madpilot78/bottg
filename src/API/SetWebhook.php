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
     * $args = [
     *      'url' => webhook URL,
     *      'cert' => Certificate file (optional)
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

        if ($c == 0 || $c > 2) {
            throw new InvalidArgumentException('Wrong argument count');
        }

        if (strlen($args[0]) == 0) {
            throw new InvalidArgumentException('URL cannot be empty');
        }

        if (strpos($args[0], 'https://') !== 0) {
            throw new InvalidArgumentException('URL must start with "https://"');
        }

        $fields = [
            'url' => $args[0]
        ];

        if ($c == 2 && !is_null($args[1])) {
            if (is_readable($args[1])) {
                $fields['certificate'] = new CURLFile($args[1], 'application/x-pem-file', 'certificate');
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
