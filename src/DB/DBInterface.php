<?php

namespace madpilot78\bottg\DB;

use madpilot78\bottg\DB\BackEnds\BackEndInterface;

interface DBInterface
{
    /**
     * Constructor requires a backend.
     *
     * @param BackEndInterface $backend
     *
     * @return void
     */
    public function __construct(BackEndInterface $backend);

    /**
     * Gets the update ID from the DB.
     *
     * @return int
     */
    public function getUpdateID(): int;

    /**
     * Saves the Update ID to the DB.
     *
     * @param int $id
     *
     * @return void
     */
    public function setUpdateID(int $id): void;
}
