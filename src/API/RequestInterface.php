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
    public const MPART = 1;

    /**
     * @var int GET
     */
    public const JSON = 2;

    /**
     * Execute the request.
     *
     * @return \madpilot78\bottg\API\ResponseInterface
     */
    public function exec(): \madpilot78\bottg\API\ResponseInterface;

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
    public function setType(int $type): void;

    /**
     * Type getter.
     *
     * @return int
     */
    public function getType(): int;

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
    public function setAPI(string $api): void;

    /**
     * Api getter.
     *
     * @return string
     */
    public function getAPI(): string;

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
    public function setFields(array $fields = null): void;

    /**
     * Fields getter.
     *
     * @return array
     */
    public function getFields(): array;
}
