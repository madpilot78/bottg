<?php

namespace madpilot78\bottg\API;

use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\Curl;
use madpilot78\bottg\Http\HttpInterface;

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
    private $fields;

    /**
     * @var \madpilot78\bottg\Http\HttpInterface
     */
    private $http;

    /**
     * @var \madpilot78\bottg\Config
     */
    private $config;

    /**
     * Checks $type.
     *
     *
     * @param int $type
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function validateType(int $type)
    {
        if (!in_array($type, [
            RequestInterface::GET,
            RequestInterface::SUBMIT,
            RequestInterface::MPART,
            RequestInterface::JSON
        ])) {
            throw new InvalidArgumentException(self::INVALID_TYPE_ERR);
        }
    }

    /**
     * Checks $api.
     *
     *
     * @param string $api
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function validateAPI(string $api)
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
    private function checkFields(&$fields)
    {
        if (is_array($fields) && count($fields) == 0) {
            $fields = null;
        }
    }

    /**
     * Constructor, checks for valid values.
     *
     *
     * @param int    $type
     * @param string $api
     * @param array  $fields
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __construct(
        int $type,
        string $api,
        array $fields = null,
        HttpInterface $http = null,
        Config $config = null
    )
    {
        $this->validateType($type);
        $this->validateAPI($api);
        $this->checkFields($fields);

        $this->type = $type;
        $this->api = $api;
        $this->fields = $fields;

        if (is_null($http)) {
            $http = new Curl();
        }
        $this->http = $http;

        if (is_null($config)) {
            $config = new Config();
        }
        $this->config = $config;
    }

    /**
     * Execute the request.
     *
     * @return \madpilot78\bottg\API\Response
     */
    public function exec()
    {
        $this->http->setOpts([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->config->connectTimeout,
            CURLOPT_TIMEOUT        => $this->config->timeout,
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        /* Set further option depending on type of request here */

        $res = new Response();

        $res->reply = $this->http->exec();
        $info = $this->http->getInfo();
        $res->code = $info['http_code'];

        return $res;
    }

    /**
     * Type setter.
     *
     *
     * @param int $type
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setType(int $type)
    {
        $this->validateType($type);

        $this->type = $type;
    }

    /**
     * Type getter.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Api setter.
     *
     *
     * @param string $api
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setAPI(string $api)
    {
        $this->validateAPI($api);

        $this->api = $api;
    }

    /**
     * Api getter.
     *
     * @return string
     */
    public function getAPI()
    {
        return $this->api;
    }

    /**
     * Fields setter.
     *
     *
     * @param array $fields
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setFields(array $fields = null)
    {
        $this->checkFields($fields);

        $this->fields = $fields;
    }

    /**
     * Fields getter.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
