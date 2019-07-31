<?php

namespace madpilot78\bottg\DB;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use madpilot78\bottg\DB\BackEnds\BackEndInterface;
use madpilot78\bottg\Exceptions\DBException;
use PDO;

class DB implements DBInterface
{
    /**
     * @var int DB schema version.
     */
    public const VERSION = 0;

    /**
     * @var object
     */
    private $backend;

    /**
     * Factory returning populated DB object wit correct parameters.
     *
     * @param string $name
     * @param array  $params
     *
     * @return DB
     */
    public static function factory(string $name, array $params): self
    {
        $class = '\\madpilot78\\bottg\\DB\\BackEnds\\' . $name;

        if (!class_exists($class)) {
            throw new InvalidArgumentException('Unknown backend');
        }

        $backend = $class::factory($params);

        return new self($backend);
    }

    /**
     * Constructor needs to check if DB exists, check version, create or update schema.
     *
     * @param PDO    $dbh
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
                $backend->updateSchema($version);
            } elseif ($version > self::VERSION) {
                $backend->dbh = null;

                throw new DBException('Unknown DB schema version ' . $version, 99);
            }
        } catch (PDOException $e) {
            throw new DBException($e->getMessage(), 1, $e);
        }
    }

    /**
     * Gets the update ID from the DB.
     *
     * @return int
     */
    public function getUpdateID(): int
    {
        try {
            $row = $this->backend->getUpdateID();

            // If save UpdateID older than 6 months, return 0.
            $ts = new DateTime($row['timestamp'], new DateTimeZone('UTC'));
            $chk = new DateTime('now', new DateTimeZone('UTC'));
            $chk->modify('-6 months');
            if($ts < $chk) {
                $ret = 0;
            } else {
                $ret = $row['value'];
            }
        } catch (\Throwable $e) {
            // return 0, should log the error.
            $ret = 0;
        }

        return $ret;
    }

    /**
     * Saves the Update ID to the DB.
     *
     * @param int $id
     *
     * @return void
     */
    public function setUpdateID(int $id): void
    {
        $this->backend->setUpdateID($id);
    }
}
