<?php

namespace madpilot78\bottg\API;

use InvalidArgumentException;

/**
 * Http implementation backed by curl.
 *
 * @codeCoverageIgnore
 */
class Request implements RequestInterface
{
    /**
     * @var string INVALID_TYPE_ERR Error message for invalid type
     */
    private const INVALID_TYPE_ERR = 'Unknown Request Type';

    /**
     * @var stringINVALID_API_ERR  Error message for invalid API
     */
    private const INVALID_API_ERR = 'API string cannot be empty';

    /**
     * @var int $type
     */
    private $type;

    /**
     * @var string $api
     */
    private $api;

    /**
     * @var array $fields
     */
    private $fields;

    /**
     * Internal function to make sure the request type is valid.
     *
     * @param int $type
     *
     * @return bool
     */
    private function validateType(int $type)
    {
        return in_array($type, [
            RequestInterface::GET,
            RequestInterface::SUBMIT,
            RequestInterface::MPART,
            RequestInterface::JSON
        ]);
    }

    /**
     * Constructor, checks for valid values.
     *
     * @throws InvalidArgumentException
     *
     * @param int $type
     * @param string $api
     * @param array $fields
     *
     * @return void
     */
    public function __construct(int $type, string $api, array $fields = null)
    {
        if (!$this->validateType($type)) {
            throw new InvalidArgumentException(self::INVALID_TYPE_ERR);
        }

        if (strlen($api) == 0) {
            throw new InvalidArgumentException(self::INVALID_API_ERR);
        }

        if (is_array($fields) && count($fields) == 0) {
            $fields = null;
        }

        $this->type = $type;
        $this->api = $api;
        $this->fields = $fields;
    }

    /**
     * Type setter
     *
     * @throws InvalidArgumentException
     *
     * @param int $type
     *
     * @return void
     */
    public function setType(int $type)
    {
        if (!$this->validateType($type)) {
            throw new InvalidArgumentException(self::INVALID_TYPE_ERR);
        }

        $this->type = $type;
    }

    /**
     * Type getter
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Api setter
     *
     * @throws InvalidArgumentException
     *
     * @param string $api
     *
     * @return void
     */
    public function setAPI(string $api)
    {
        if (strlen($api) == 0) {
            throw new InvalidArgumentException(self::INVALID_API_ERR);
        }

        $this->api = $api;
    }

    /**
     * Api getter
     *
     * @return string
     */
    public function getAPI()
    {
        return $this->api;
    }

    /**
     * Fields setter
     *
     * @throws InvalidArgumentException
     *
     * @param array $fields
     *
     * @return void
     */
    public function setFields(array $fields = null)
    {
        if (is_array($fields) && count($fields) == 0) {
            $fields = null;
        }

        $this->fields = $fields;
    }

    /**
     * Fields getter
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
