<?php

namespace madpilot78\bottg\DB\BackEnds;

use InvalidArgumentException;
use madpilot78\bottg\DB\DB;
use madpilot78\bottg\Exceptions\DBException;
use PDO;

class SQLite implements BackEndInterface
{
    /**
     * @var PDO
     */
    private $dbh;

    /**
     * Factory to create DB and inject in constructor.
     *
     * @param array $params
     *
     * @throws InvalidArgumentException
     *
     * @return self
     */
    public static function factory(array $params): self
    {
        if (!array_key_exists('path', $params) && strlen($params['path']) == 0) {
            throw new InvalidArgumentException('SQLite Database path missing');
        }

        $dbh = new PDO('sqlite:' . $params['path']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return new self($dbh);
    }

    /**
     * Constructor needs to check if DB exists, check version, create or update schema.
     *
     * @param PDO $dbh
     *
     * @throws DBException
     *
     * @return void
     */
    public function __construct(PDO $dbh)
    {
        if (!($dbh instanceof PDO)) {
            throw new DBException('Invalid DB handle', 1);
        }

        $this->dbh = $dbh;
    }

    /**
     * Check if dbver table exists and has values.
     *
     * @return bool
     */
    public function checkDbverExists(): bool
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
    public function getDBVer(): int
    {
        $sth = $this->dbh->query('SELECT MAX(ver) FROM dbver');
        $ret = $sth->fetchColumn();

        return $ret;
    }

    /**
     * Creates the latest version of the DB schema.
     *
     * @return void
     */
    public function createSchema(): void
    {
        $this->dbh->exec('CREATE TABLE dbver (version INTEGER NOT NULL UNIQUE, timestamp TEXT DEFAULT CURRENT_TIMESTAMP)');
        $this->dbh->exec('INSERT INTO dbver (version) VALUES (' . DB::VERSION . ')');
        $this->dbh->exec('CREATE TABLE update_id (value INTEGER NOT NULL, timestamp TEXT DEFAULT CURRENT_TIMESTAMP)');
        $this->dbh->exec('INSERT INTO update_id (value, timestamp) VALUES (0, "1970-01-01 00:00:00")');
    }

    /**
     * Updates to the latest version of the DB schema.
     *
     * @param int $oldver
     *
     * @return void
     */
    public function updateSchema(int $oldver): void
    {
    }

    /**
     * Get UpdateID from backend.
     *
     * @return array
     */
    public function getUpdateID(): array
    {
        $sth = $this->dbh->query('SELECT value, timestamp FROM update_id');

        return $sth->fetch();
    }

    /**
     * Save UpdateID in backend.
     *
     * @param int $id
     *
     * @return void
     */
    public function setUpdateID(int $id): void
    {
        try {
            $this->dbh->beginTransaction();

            $this->dbh->exec('DELETE FROM update_id');
            $sth = $this->dbh->prepare('INSERT INTO update_id (value) VALUES (:value)');
            $sth->execute([
                ':value' => $id
            ]);

            $this->dbh->commit();
        } catch (\Throwable $e) {
            $this->dbh->rollBack();

            throw new DBException('Error updating UpdateID', 42, $e);
        }
    }
}
