<?php

namespace madpilot78\bottg\tests\API;

use InvalidArgumentException;
use madpilot78\bottg\API\GetUpdates;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;

class GetUpdatesTest extends TestCase
{
    /**
     * Test creating a getUpdates object.
     *
     * @return void
     */
    public function testCanCreateGetUpdatesObject()
    {
        $c = new GetUpdates([]);
        $this->assertInstanceOf(GetUpdates::class, $c);
        $f = $c->getFields();
        $this->assertNull($f['offset']);
        $c = new GetUpdates([42]);
        $this->assertInstanceOf(GetUpdates::class, $c);
        $f = $c->getFields();
        $this->assertEquals(42, $f['offset']);
    }

    /**
     * Test getUpdates with too many args throws exception.
     *
     * @return void
     */
    public function testGetUpdatesThrowsExceptionWithTooManyArgs()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong argument count');

        $c = new GetUpdates([42, 'foo']);
    }

    /**
     * Test getUpdates with non numeric offset throws exception.
     *
     * @return void
     */
    public function testGetUpdatesThrowsExceptionOnNonNumericOffset()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Offset must be numeric or null');

        $c = new GetUpdates(['fortytwo']);
    }

    /**
     * Test getUpdates with numeric string works.
     *
     * @return void
     */
    public function testGetUpdatesWorksWithNumericString()
    {
        $c = new GetUpdates(['42']);
        $this->assertInstanceOf(GetUpdates::class, $c);
        $f = $c->getFields();
        $this->assertEquals(42, $f['offset']);
    }

    /**
     * Test exec method returns a success response.
     *
     * @return void
     */
    public function testExecReturnsReponseOnSuccess()
    {
        $http = $this->getMockBuilder(HttpInterface::class)
            ->setMethods(['setOpts', 'exec', 'getInfo', 'getError'])
            ->getMock();

        $http->expects($this->atLeastOnce())
            ->method('setOpts')
            ->with($this->callback(function ($s) {
                return is_array($s);
            }))
            ->willReturn(true);

        $http->expects($this->once())
            ->method('exec')
            ->willReturn('{"ok":true,"description":"Mock Success","update":{"update_id":222,"message":{"message_id":123,"text":"test"}}}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new GetUpdates([], null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->content['ok']);
        $this->assertTrue(is_array($res->content['update']['message']));
        $this->assertEquals('test', $res->content['update']['message']['text']);
    }
}
