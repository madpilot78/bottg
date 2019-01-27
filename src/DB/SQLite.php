<?php

namespace madpilot78\bottg\DB;

use madpilot78\bottg\Exceptions\DBException;
use PDO;

class SQLite implements DBInterface
{
    /**
     * @var int DB schema version.
     */
    public const VERSION = 0;

    /**
     * @var PDO
     */
    private $dbh;

    /**
     * Check if dbver table exists and has values.
     *
     * @return bool
     */
    private function checkDbverExists(): bool
    {
        $sth = $this->dbh->query('SELECT count(*) FROM sqlite_master WHERE type = "table" AND name = "dbver"');
        $res = $sth->fetchColumn();
        if ($res !== 0) {
            return false;
        }

        $sth = $this->dbh->query('SELECT count(*) FROM dbver');
        $res = $sth->fetchColumn();

        return $res > 0 ? true : false;
    }

    /**
     * Get DB version.
     *
     * @return int
     */
    private function getDBVer(): int
    {
        $sth = $this->dbh->query('SELECT MAX(ver) FROM dbver');
        $ret = $sth->fetchColumn();

        return $ret;
    }

    /**
     * Creates the latest version of the DB schema
     *
     * @return void
     */
    private function createSchema(): void
    {
        $this->dbh->exec('CREATE TABLE dbver (version INTEGER NOT NULL UNIQUE, timestamp TEXT DEFAULT CURRENT_TIMESTAMP)');
        $this->dbh->exec('INSERT INTO dbver (version) VALUES (' . self::VERSION . ')');
        $this->dbh->exec('CREATE TABLE update_id (value INTEGER NOT NULL, timestamp TEXT DEFAULT CURRENT_TIMESTAMP)');
        $this->dbh->exec('INSERT INTO update_id (value) VALUES (0)');
    }

    /**
     * Updates to the latest version of the DB schema
     *
     * @param int $oldver
     *
     * @return void
     */
    private function updateSchema(int $oldver): void
    {
    }

    /**
     * Constructor needs to check if DB exists, check version, create or update schema.
     *
     * @param PDO $dbh
     * @param string $path
     *
     * @throws DBException
     *
     * @return void
     */
    public function __construct(PDO $dbh = null, string $path = null)
    {
        try {
            if (!is_null($dbh) && $dbh instanceof PDO) {
                $this->dbh = $dbh;
            } else {
                $this->dbh = new PDO('sqlite:' . $path);
            }
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (!$this->checkDbverExists()) {
                $this->createSchema();
                return;
            }

            $version = $this->getDBVer();

            if ($version < self::VERSION) {
                $this->updateSchema($res);
            } elseif ($version > self::VERSION) {
                $this->dbh = null;
                throw new DBException('Unknown DB schema version ' . $version, 99);
            }
        } catch (PDOException $e) {
            throw new DBException($e->getMessage(), 1, $e);
        }
    }

    /**
     * Gets the update ID from the DB
     *
     * @return int
     */
    public function getUpdateID(): int
    {
    }

    /**
     * Saves the Update ID to the DB
     *
     * @param   int $id
     *
     * @return  void
     */
    public function setUpdateID(int $id): void
    {
    }
}
