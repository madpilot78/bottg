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
     * $args = [
     *      (string) chat ID,
     *      (string) text,
     *      (array)  options (optional)
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

        if ($c < 1 || $c > 3) {
            throw new InvalidArgumentException('Wrong argument count');
        }

        if (!is_string($args[0])) {
            throw new InvalidArgumentException('ChatID must be a string');
        }

        $this->checkChatID($args[0]);

        if (!is_string($args[1]) || strlen($args[1]) == 0) {
            throw new InvalidArgumentException('Message text cannot be empty');
        }

        $fields = [
            'chat_id' => $args[0],
            'text'    => $args[1]
        ];

        if ($c == 3 && is_array($args[2]) && count($args[2]) > 0) {
            foreach ($args[2] as $o => $v) {
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
