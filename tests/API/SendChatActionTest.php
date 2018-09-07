<?php

namespace madpilot78\bottg\tests\API;

use InvalidArgumentException;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\API\SendChatAction;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;
use TypeError;

class SendChatActionTest extends TestCase
{
    /**
     * Test SendChatAction requires arguments.
     *
     * @return void
     */
    public function testSendChatActionRequiresArguments()
    {
        $this->expectException(TypeError::class);
        $c = new SendChatAction();
    }

    /**
     * Test creating a SendChatAction object.
     *
     * @return void
     */
    public function testCanCreateSendChatActionObject()
    {
        $c = new SendChatAction('123', 'typing');
        $this->assertInstanceOf(SendChatAction::class, $c);
        $this->assertEquals(['chat_id' => '123', 'action' => 'typing'], $c->getFields());
        $c = new SendChatAction('@test', 'upload_photo');
        $this->assertInstanceOf(SendChatAction::class, $c);
        $this->assertEquals(['chat_id' => '@test', 'action' => 'upload_photo'], $c->getFields());
    }

    /**
     * Test SendChatAction with invalid action throws exception.
     *
     * @return void
     */
    public function testSendChatActionThrowsExceptionOnInvalidAction()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown chat action requested');

        $c = new SendChatAction('123', 'singing');
    }

    /**
     * Test SendChatAction with empty chat_id throws exception.
     *
     * @return void
     */
    public function testSendChatActionThrowsExceptionOnEmptyChatID()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid chat_id');

        $c = new SendChatAction('', 'typing');
    }

    /**
     * Test SendChatAction with invalid chat_id throws exception.
     *
     * @return void
     */
    public function testSendChatActionThrowsExceptionOnInvalidChatID()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid chat_id');

        $c = new SendChatAction('foo', 'typing');
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
            ->willReturn('{"ok":true,"description":"Action sent"}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new SendChatAction('123', 'typing', null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->content['ok']);
    }
}
