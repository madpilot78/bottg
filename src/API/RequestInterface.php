<?php

namespace madpilot78\bottg\API;

interface RequestInterface
{
    /**
     * @var int GET
     */
    public const GET = 0;

    /**
     * @var int GET
     */
    public const SUBMIT = 1;

    /**
     * @var int GET
     */
    public const MPART = 2;

    /**
     * @var int GET
     */
    public const JSON = 3;

    /**
     * Execute the request.
     *
     * @return \madpilot78\bottg\API\Response
     */
    public function exec();

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
    public function setType(int $type);

    /**
     * Type getter.
     *
     * @return int
     */
    public function getType();

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
    public function setAPI(string $api);

    /**
     * Api getter.
     *
     * @return string
     */
    public function getAPI();

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
    public function setFields(array $fields = null);

    /**
     * Fields getter.
     *
     * @return array
     */
    public function getFields();
}