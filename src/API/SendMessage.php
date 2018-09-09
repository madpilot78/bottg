<?php

namespace madpilot78\bottg\API;

use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\Logger;

/**
 * Implements the Telegram Bot API sendMessage.
 */
class SendMessage extends Request implements RequestInterface
{
    /**
     * Constructor, passes correct arguments to upstream constructor.
     *
     * @param Config        $config
     * @param Logger        $logger
     * @param HttpInterface $http
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(
        string $chatid,
        string $text,
        array $options = null,
        Config $config = null,
        Logger $logger = null,
        HttpInterface $http = null
    ) {
        $this->checkChatID($chatid);

        if (strlen($text) == 0) {
            throw new InvalidArgumentException('Message text cannot be empty');
        }

        $fields = [
            'chat_id' => $chatid,
            'text'    => $text
        ];

        if (is_null($options)) {
            $options = [];
        }

        foreach ($options as $o => $v) {
            switch ($o) {
                case 'parse_mode':
                    if (!in_array($v, ['Markdown', 'HTML'], true)) {
                        throw new InvalidArgumentException($o . ' can be one of "Markdown" or "HTML"');
                    }
                    break;

                case 'disable_web_page_preview':
                case 'disable_notification':
                    if (!is_bool($v)) {
                        throw new InvalidArgumentException($o . ' must be boolean');
                    }
                    break;

                case 'reply_to_message_id':
                    if (!is_int($v)) {
                        throw new InvalidArgumentException($o . ' must be integer');
                    }
                    break;

                case 'reply_markup':
                default:
                    throw new InvalidArgumentException('Unknown or unsupported option given');
                    break;
            }
            $fields[$o] = $v;
        }

        parent::__construct(
            RequestInterface::JSON,
            'sendMessage',
            $fields,
            $config,
            $logger,
            $http
        );
    }
}
