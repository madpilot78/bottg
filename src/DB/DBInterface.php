<?php

namespace madpilot78\bottg\DB

interface DBInterface
{
    /**
     * Gets the update ID from the DB
     *
     * @return int
     */
    public function getUpdateID(): int;

    /**
     * Saves the Update ID to the DB
     *
     * @param   int $id
     *
     * @return  void
     */
    public function setUpdateID(int $id): void;
}
