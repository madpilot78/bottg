<?php

namespace madpilot78\bottg\API;

use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Exceptions\HttpException;
use madpilot78\bottg\Http\Curl;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\Logger;

class Request implements RequestInterface
{
    /**
     * @var string INVALID_TYPE_ERR Error message for invalid type.
     */
    private const INVALID_TYPE_ERR = 'Unknown Request Type';

    /**
     * @var string INVALID_API_ERR  Error message for invalid API.
     */
    private const INVALID_API_ERR = 'API string cannot be empty';

    /**
     * @var string
     */
    private const BASEURL = 'https://api.telegram.org/bot';

    /**
     * @var int Type of request.
     */
    private $type;

    /**
     * @var string Requested API
     */
    private $api;

    /**
     * @var array Additional fields to the API
     */
    protected $fields;

    /**
     * @var \madpilot78\bottg\Http\HttpInterface
     */
    private $http;

    /**
     * @var \madpilot78\bottg\Config
     */
    private $config;

    /**
     * @var \madpilot78\bottg\Logger
     */
    private $logger;

    /**
     * Checks $type.
     *
     * @param int $type
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function validateType(int $type): void
    {
        if (!in_array($type, [
            RequestInterface::GET,
            RequestInterface::MPART,
            RequestInterface::JSON
        ], true)) {
            throw new InvalidArgumentException(self::INVALID_TYPE_ERR);
        }
    }

    /**
     * Checks $api.
     *
     * @param string $api
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function validateAPI(string $api): void
    {
        if (strlen($api) == 0) {
            throw new InvalidArgumentException(self::INVALID_API_ERR);
        }
    }

    /**
     * Checks $fields, sets to null if it's [].
     *
     * @param mixed $fields
     *
     * @return void
     */
    private function checkFields(&$fields): void
    {
        if (is_array($fields) && count($fields) == 0) {
            $fields = [];
        }
    }

    /**
     * Format check for chat IDs.
     *
     *
     * @param string $chatid
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function checkChatID(string $chatid): void
    {
        if (strlen($chatid) == 0 || (!ctype_digit($chatid) && strpos($chatid, '@') !== 0)) {
            throw new InvalidArgumentException('Invalid chat_id');
        }
    }

    /**
     * Constructor, checks for valid values.
     *
     * @param int           $type
     * @param string        $api
     * @param array         $fields
     * @param Config        $config
     * @param Logger        $logger
     * @param HttpInterface $http
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(
        int $type,
        string $api,
        array $fields = null,
        Config $config = null,
        Logger $logger = null,
        HttpInterface $http = null
    ) {
        $this->validateType($type);
        $this->validateAPI($api);
        $this->checkFields($fields);

        $this->type = $type;
        $this->api = $api;
        if (is_null($fields)) {
            $fields = [];
        }
        $this->fields = $fields;

        if (is_null($http)) {
            $http = new Curl();
        }
        $this->http = $http;

        if (is_null($config)) {
            $config = new Config();
        }
        $this->config = $config;

        if (is_null($logger)) {
            $logger = new Logger();
        }
        $this->logger = $logger;
    }

    /**
     * Execute the request.
     *
     * @throws HttpException
     *
     * @return \madpilot78\bottg\API\ResponseInterface
     */
    public function exec(): \madpilot78\bottg\API\ResponseInterface
    {
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->config->getConnectTimeout(),
            CURLOPT_TIMEOUT        => $this->config->getTimeout(),
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
            CURLOPT_SSL_VERIFYPEER => true
        ];

        if (!is_null($this->config->getProxyHost())) {
            $opts += [
                CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
                CURLOPT_PROXY     => $this->config->getProxyHost(),
                CURLOPT_PROXYPORT => $this->config->getProxyPort()
            ];
            if (!is_null($this->config->getProxyAuth())) {
                $opts[CURLOPT_PROXYUSERPWD] = $this->config->getProxyAuth();
            }
        }

        $url = self::BASEURL . $this->config->getToken() . '/' . $this->api;

        /* Set further options depending on type of request here */
        switch ($this->type) {
            case RequestInterface::GET:
                foreach ($this->fields as $k => &$v) {
                    if (!is_numeric($v) && !is_string($v)) {
                        $v = json_encode($v);
                    }
                }

                $qs = http_build_query($this->fields);
                if ($qs) {
                    $url .= '?' . $qs;
                }

                $opts += [
                    CURLOPT_URL => $url
                ];
                break;

            case RequestInterface::MPART:
                $opts += [
                    CURLOPT_URL        => $url,
                    CURLOPT_POST       => true,
                    CURLOPT_POSTFIELDS => $this->fields
                ];
                break;

            case RequestInterface::JSON:
                $opts += [
                    CURLOPT_URL        => $url,
                    CURLOPT_POST       => true,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                    CURLOPT_POSTFIELDS => json_encode($this->fields)
                ];
                break;
        }

        $this->http->setOpts($opts);
        $this->logger->debug('Curl options: ' . var_export($opts, true));
        $reply = $this->http->exec();
        $this->logger->debug('Raw reply: ' . $reply . PHP_EOL);
        $info = $this->http->getInfo();
        $code = $info['http_code'];

        if ($reply === false) {
            $error = $this->http->getError();
            $err = 'Error contacting server: (' . $error['errno'] . ') ' . $error['error'];
            $this->logger->err($err);

            throw new HttpException($err);
        }

        if ($code >= 500) {
            throw new HttpException('Server error');
        }

        return new Response($this->api, $reply, $code);
    }

    /**
     * Type setter.
     *
     * @param int $type
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setType(int $type): void
    {
        $this->validateType($type);

        $this->type = $type;
    }

    /**
     * Type getter.
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Api setter.
     *
     * @param string $api
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setAPI(string $api): void
    {
        $this->validateAPI($api);

        $this->api = $api;
    }

    /**
     * Api getter.
     *
     * @return string
     */
    public function getAPI(): string
    {
        return $this->api;
    }

    /**
     * Fields setter.
     *
     * @param array $fields
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setFields(array $fields = null): void
    {
        $this->checkFields($fields);

        $this->fields = $fields;
    }

    /**
     * Fields getter.
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
