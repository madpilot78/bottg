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
     * @throws DBException
     *
     * @return void
     */
    public function testThrowingDBException()
    {
        $this->expectException('\madpilot78\bottg\Exceptions\DBException');
        $this->expectExceptionCode(0);

        throw new DBException();
    }

    /**
     * Test causing a PDO exception and rethrowing it as a DB Exception.
     *
     * @throws DBException
     *
     * @return void
     */
    public function testPDOToDBException()
    {
        $this->expectException('\madpilot78\bottg\Exceptions\DBException');
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('General error: 1 no such table: test');

        try {
            $dbh = new PDO('sqlite::memory:');
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->query('SELECT * FROM test');
        } catch (PDOException $e) {
            throw new DBException($e->getMessage(), 1, $e);
        }
    }
}
