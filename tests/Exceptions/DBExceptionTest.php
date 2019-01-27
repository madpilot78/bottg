<?php

namespace madpilot78\bottg\tests\Exceptions;

use madpilot78\bottg\Exceptions\DBException;
use madpilot78\bottg\tests\TestCase;
use PDO;
use PDOException;

class DBExceptionTest extends TestCase
{
    /**
     * Test throwing a DBException.
     *
     * @expectedException        \madpilot78\bottg\Exceptions\DBException
     * @expectedExceptionCode    0
     *
     * @throws DBException
     *
     * @return void
     */
    public function testThrowingDBException()
    {
        throw new DBException();
    }

    /**
     * Test causing a PDO exception and rethrowing it as a DB Exception.
     *
     * @expectedException        \madpilot78\bottg\Exceptions\DBException
     * @expectedExceptionCode    1
     * @expectedExceptionMessage General error: 1 no such table: test
     *
     * @throws DBException
     *
     * @return void
     */
    public function testPDOToDBException()
    {
        try {
            $dbh = new PDO('sqlite::memory:');
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->query('SELECT * FROM test');
        } catch (PDOException $e) {
            throw new DBException($e->getMessage(), 1, $e);
        }
    }
}
