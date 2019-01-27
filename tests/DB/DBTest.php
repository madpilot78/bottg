<?php

namespace madpilot78\bottg\tests\DB;

use InvalidArgumentException;
use madpilot78\bottg\DB\DB;
use madpilot78\bottg\Exceptions\DBException;
use madpilot78\bottg\tests\TestCase;
use PDO;

class DBTest extends TestCase
{
    /**
     * Test factory returns correct object
     *
     * @return void
     */
    public function testFactory()
    {
        $db = DB::factory('SQLite', [':memory:']);
        $this->assertInstanceOf(DB::class, $db);
    }
}
