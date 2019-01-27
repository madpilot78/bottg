<?php

namespace madpilot78\bottg\DB;

use InvalidArgumentException;
use madpilot78\bottg\Exceptions\DBException;
use madpilot78\madpilot78\bottg\DB\BackEnds\BackEndInterface;
use PDO;

class DB implements DBInterface
{
    /**
     * @var object
     */
    private $backend;

    /**
     * Factory returning populated DB object wit correct parameters
     *
     * @param string $name
     * @param array $params
     *
     * @return DB
     */
    public static function factory(string $name, array $params): DB
    {
        $class = '\\madpilot78\\bottg\\DB\\BackEnds\\' . $name;

        if (!class_exists($class)) {
            throw new InvalidArgumentException('Unknown backend');
        }

        $backend = new $class($params);

        return new DB($backend);
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
    public function __construct(BackEndInterface $backend)
    {
        $this->backend = $backend;

        try {
            if (!$backend->checkDbverExists()) {
                $backend->createSchema();
                return;
            }

            $version = $backend->getDBVer();

            if ($version < self::VERSION) {
                $backend->updateSchema($res);
            } elseif ($version > self::VERSION) {
                $backend->dbh = null;
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
