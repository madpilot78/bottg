<?php

namespace madpilot78\bottg\tests\API\Requests;

use madpilot78\bottg\API\Requests\DeleteWebhook;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;

class DeleteWebhookTest extends TestCase
{
    /**
     * Test creating a deleteWebhook object.
     *
     * @return void
     */
    public function testCanCreateDeleteWebhookObject()
    {
        $c = new DeleteWebhook([]);
        $this->assertInstanceOf(DeleteWebhook::class, $c);
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
            ->willReturn('{"ok":true,"description":"Webhook deleted"}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new DeleteWebhook([], null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->content->ok);
    }
}
