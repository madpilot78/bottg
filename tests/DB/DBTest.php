<?php

namespace madpilot78\bottg\tests\DB;

use madpilot78\bottg\DB\DB;
use madpilot78\bottg\tests\TestCase;

class DBTest extends TestCase
{
    /**
     * Test factory returns correct object.
     *
     * @return void
     */
    public function testFactory()
    {
        $db = DB::factory('SQLite', ['path' => ':memory:']);
        $this->assertInstanceOf(DB::class, $db);
    }

    /**
     * Test factory throws exception for invalid backend.
     *
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Unknown backend
     *
     * @return void
     */
    public function testFactoryWithUnknownBackend()
    {
        $db = DB::factory('foo', ['path' => ':memory:']);
    }
}
