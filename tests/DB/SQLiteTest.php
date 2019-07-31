<?php

namespace madpilot78\bottg\tests\DB;

use madpilot78\bottg\DB\BackEnds\SQLite;
use madpilot78\bottg\tests\TestCase;
use PDO;

class SQLiteTest extends TestCase
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
    protected function setUp(): void
    {
        $this->dbh = new PDO('sqlite::memory:');
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Unset the DB handle, being the DB memory based, should clean up everything.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->dbh);
    }

    /**
     * Assert the DB has a table named $table.
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
     * Assert DB version matches expected.
     *
     * @param int    $version
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
     * Test creating an SQLite object.
     *
     * @return void
     */
    public function testCretingSQLite()
    {
        $db = new SQLite($this->dbh);
        $this->assertInstanceOf(SQLite::class, $db);
    }

    /**
     * Test populating DB.
     *
     * @return void
     */
    public function testPopulatingSQLite()
    {
        $db = new SQLite($this->dbh);
        $db->createSchema();

        $this->assertDBHasTable('dbver');
        $this->assertDBVersion(0);
        $this->assertDBHasTable('update_id');
    }

    /**
     * Test saving and getting update ID from SQLite
     *
     * @return void
     */
    public function testGetSetUpdateID()
    {
        date_default_timezone_set('UTC');

        $db = new SQLite($this->dbh);
        $db->createSchema();

        $res = $db->getUpdateID();
        $this->assertIsArray($res);
        $this->assertEquals('1970-01-01 00:00:00', $res['timestamp']);
        $this->assertEquals(0, $res['value']);

        $expTS = date('Y-m-d H:i:s');
        $db->setUpdateID(42);
        $sth = $this->dbh->query('SELECT count(*) FROM update_id WHERE value = 42');
        $res = $sth->fetchColumn();
        $this->assertEquals(1, $res);

        $res = $db->getUpdateID();
        $this->assertIsArray($res);
        $this->assertEquals($expTS, $res['timestamp']);
        $this->assertEquals(42, $res['value']);
    }
}
