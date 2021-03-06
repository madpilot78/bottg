<?php

namespace madpilot78\bottg\tests\API\Requests;

use madpilot78\bottg\API\Requests\GetMe;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;

class GetMeTest extends TestCase
{
    /**
     * Test creating a getMe object.
     *
     * @return void
     */
    public function testCanCreateGetMeObject()
    {
        $c = new GetMe([]);
        $this->assertInstanceOf(GetMe::class, $c);
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
            ->willReturn('{"ok":true,"result":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"}}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new GetMe([], null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->ok);
        $this->assertEquals(12345, $res->result->id);
    }
}
