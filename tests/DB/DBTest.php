<?php

namespace madpilot78\bottg\tests\DB;

use InvalidArgumentException;
use madpilot78\bottg\DB\SQLite;
use madpilot78\bottg\Exceptions\DBException;
use madpilot78\bottg\tests\TestCase;
use PDO;

class DBTest extends TestCase
{
    /**
     * @var DBO DB handle being used for testing.
     */
    private $dbh;

    /**
     * create a DB handle to be used in the tests.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->dbh = new PDO('sqlite::memory:');
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * create a DB handle to be used in the tests.
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->dbh);
    }

    /**
     * Assert the DB has a table named $table
     *
     * @param string $table
     * @param string $message
     *
     * @return void
     */
    private function assertDBHasTable(string $table, string $message = '')
    {
        $sth = $this->dbh->prepare('SELECT count(*) FROM sqlite_master WHERE type = :type AND name = :name');
        $sth->execute([
            ':type' => 'table',
            ':name' => $table
        ]);
        $res = $sth->fetchColumn();

        $this->assertEquals(1, $res, $message);
    }

    /**
     * Assert DB version matches expected
     *
     * @param int $version
     * @param string $message
     *
     * @return void
     */
    private function assertDBVersion(int $version, string $message = '')
    {
        $sth = $this->dbh->query('SELECT MAX(version) FROM dbver');
        $res = $sth->fetchColumn();

        $this->assertEquals($version, $res, $message);
    }

    /**
     * Test creating an SQLite DB object and check it creates the schema
     *
     * @return void
     */
    public function testCretingSQLiteDB()
    {
        $db = new SQLite(':memory:');
        $this->assertInstanceOf(SQLite::class, $db);

        $this->assertDBHasTable('dbver');
        $this->assertDBHasTable('update_id');
        $this->assertDBVersion(SQLite::VERSION);
    }
}
