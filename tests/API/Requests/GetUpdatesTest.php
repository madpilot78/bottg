<?php

namespace madpilot78\bottg\tests\API\Requests;

use InvalidArgumentException;
use madpilot78\bottg\API\Requests\GetUpdates;
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
        $this->assertNotTrue(array_key_exists('offset', $f));
        $this->assertEquals(
            GetUpdates::SUPPORTED_UPDATES,
            $f['allowed_updates']
        );
        $c = new GetUpdates([42]);
        $this->assertInstanceOf(GetUpdates::class, $c);
        $f = $c->getFields();
        $this->assertEquals(42, $f['offset']);
        $c = new GetUpdates([null, ['message']]);
        $this->assertInstanceOf(GetUpdates::class, $c);
        $f = $c->getFields();
        $this->assertEquals(['message'], $f['allowed_updates']);
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

        $c = new GetUpdates([42, [], 'foo']);
    }

    /**
     * Test getUpdates with wrong argument type throws exception.
     *
     * @return void
     */
    public function testGetUpdatesThrowsExceptionWithWrongArgs()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong argument type');

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
     * Test getUpdates with unknown types throws exception.
     *
     * @return void
     */
    public function testGetUpdatesWithUnknownTypeFails()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown update type');

        $c = new GetUpdates(['42', ['petunia bowl']]);
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
            ->willReturn('{"ok":true,"result":[{"update_id":222,"message":{"message_id":123,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"date":1539700746,"text":"test"}},{"update_id":223,"message":{"message_id":124,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"date":1539700900,"text":"another test"}}]}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new GetUpdates([], null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->ok);
        $this->assertEquals(123, $res->result[0]->message->message_id);
        $this->assertEquals('test', $res->result[0]->message->text);
        $this->assertEquals(124, $res->result[1]->message->message_id);
        $this->assertEquals('another test', $res->result[1]->message->text);
    }
}
