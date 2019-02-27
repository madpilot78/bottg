<?php

namespace madpilot78\bottg\tests\DB;

use madpilot78\bottg\DB\BackEnds\BackEndInterface;
use madpilot78\bottg\DB\DB;
use madpilot78\bottg\Exceptions\DBException;
use madpilot78\bottg\tests\TestCase;

class DBTest extends TestCase
{
    /**
     * @var DBO DB handle being used for testing.
     */
    private $mockBackEnd;

    /**
     * Create a mock DB back end for use in tests.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->mockBackEnd = $this->getMockBuilder(BackEndInterface::class)
            ->setMethodsExcept()
            ->getMock();
    }

    /**
     * Unset the DB handle, being the DB memory based, should clean up everything.
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->mockBackEnd);
    }

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

    /**
     * Test DB returning wrong DB version.
     *
     * @expectedException        \madpilot78\bottg\Exceptions\DBException
     * @expectedExceptionMessage Unknown DB schema version 99
     *
     * @return void
     */
    public function testWrongDBVersion()
    {
        $this->mockBackEnd->expects($this->once())
            ->method('checkDbverExists')
            ->willReturn(true);

        $this->mockBackEnd->expects($this->once())
            ->method('getDBVer')
            ->willReturn(99);

        $db = new DB($this->mockBackEnd);
    }

    /**
     * Test DB returning old DB version calls updateSchema.
     *
     * @return void
     */
    public function testOldDBVersion()
    {
        $this->mockBackEnd->expects($this->once())
            ->method('checkDbverExists')
            ->willReturn(true);

        $this->mockBackEnd->expects($this->once())
            ->method('getDBVer')
            ->willReturn(-1);

        $this->mockBackEnd->expects($this->once())
            ->method('updateSchema')
            ->with($this->equalTo(-1));

        $db = new DB($this->mockBackEnd);
        $this->assertInstanceOf(DB::class, $db);
    }
}
