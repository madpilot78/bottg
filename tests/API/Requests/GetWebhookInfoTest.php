<?php

namespace madpilot78\bottg\tests\API\Requests;

use madpilot78\bottg\API\Requests\GetWebhookInfo;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;

class GetWebhookInfoTest extends TestCase
{
    /**
     * Test creating a getWebhookInfo object.
     *
     * @return void
     */
    public function testCanCreateGetWebhookInfoObject()
    {
        $c = new GetWebhookInfo([]);
        $this->assertInstanceOf(GetWebhookInfo::class, $c);
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
            ->willReturn('{"ok":true,"result":{"url":"https://test.test.org:8443/webhook","has_custom_certificate":true,"pending_update_count":0,"max_connections":40}}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new GetWebhookInfo([], null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->ok);
        $this->assertTrue($res->result->has_custom_certificate);
        $this->assertEquals(0, $res->result->pending_update_count);
    }
}
