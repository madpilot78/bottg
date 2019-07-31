<?php

namespace madpilot78\bottg\DB\BackEnds;

use PDO;

interface BackEndInterface
{
    /**
     * Constructor.
     *
     * @param array $params
     *
     * @return void
     */
    public function __construct(PDO $dbh);

    /**
     * Factory to create new DB.
     *
     * @param array $params
     *
     * @return self
     */
    public static function factory(array $params);

    /**
     * Check if dbver table exists and has values.
     *
     * @return bool
     */
    public function checkDbverExists(): bool;

    /**
     * Get DB version.
     *
     * @return int
     */
    public function getDBVer(): int;

    /**
     * Creates the latest version of the DB schema.
     *
     * @return void
     */
    public function createSchema(): void;

    /**
     * Updates to the latest version of the DB schema.
     *
     * @param int $oldver
     *
     * @return void
     */
    public function updateSchema(int $oldver): void;

    /**
     * Get UpdateID from backend.
     *
     * @return array
     */
    public function getUpdateID(): array;

    /**
     * Save UpdateID in backend.
     *
     * @param int $id
     *
     * @return void
     */
    public function setUpdateID(int $id): void;
}
