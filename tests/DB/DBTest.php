<?php

namespace madpilot78\bottg\tests\DB;

use madpilot78\bottg\DB\BackEnds\BackEndInterface;
use madpilot78\bottg\DB\DB;
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
    protected function setUp(): void
    {
        $this->mockBackEnd = $this->getMockBuilder(BackEndInterface::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept()
            ->getMock();
    }

    /**
     * Unset the DB handle, being the DB memory based, should clean up everything.
     *
     * @return void
     */
    protected function tearDown(): void
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
     * @return void
     */
    public function testFactoryWithUnknownBackend()
    {
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage('Unknown backend');

        $db = DB::factory('foo', ['path' => ':memory:']);
    }

    /**
     * Test DB returning wrong DB version.
     *
     * @return void
     */
    public function testWrongDBVersion()
    {
        $this->expectException('\madpilot78\bottg\Exceptions\DBException');
        $this->expectExceptionMessage('Unknown DB schema version 99');

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

    /**
     * Test saving and getting update ID.
     *
     * @return void
     */
    public function testGetSetUpdateID()
    {
        $this->mockBackEnd->expects($this->once())
            ->method('checkDbverExists')
            ->willReturn(true);

        $this->mockBackEnd->expects($this->once())
            ->method('getDBVer')
            ->willReturn(0);

        $nowTS = date('Y-m-d H:i:s');
        $this->mockBackEnd->expects($this->exactly(3))
            ->method('getUpdateID')
            ->will($this->onConsecutiveCalls(
                [
                    'value'     => 0,
                    'timestamp' => '1970-01-01 00:00:00'
                ],
                [
                    'value'     => 42,
                    'timestamp' => $nowTS
                ],
                [
                    'value'     => 42,
                    'timestamp' => '2018-01-01 11:12:13'
                ]
            ));

        $this->mockBackEnd->expects($this->once())
            ->method('setUpdateID')
            ->with($this->equalTo(42));

        $db = new DB($this->mockBackEnd);

        $res = $db->getUpdateID();
        $this->assertIsInt($res);
        $this->assertEquals(0, $res);

        $db->setUpdateID(42);

        $res = $db->getUpdateID();
        $this->assertIsInt($res);
        $this->assertEquals(42, $res);

        $res = $db->getUpdateID();
        $this->assertIsInt($res);
        $this->assertEquals(0, $res);
    }
}
