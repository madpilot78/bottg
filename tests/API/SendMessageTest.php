<?php

namespace madpilot78\bottg\tests\API;

use InvalidArgumentException;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\API\SendMessage;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;
use TypeError;

class SendMessageTest extends TestCase
{
    /**
     * Test SendMessage requires arguments.
     *
     * @return void
     */
    public function testSendMessageRequiresArguments()
    {
        $this->expectException(TypeError::class);
        $c = new SendMessage();
    }

    /**
     * Test creating a SendMessage object.
     *
     * @return void
     */
    public function testCanCreateSendMessageObject()
    {
        $c = new SendMessage('123', 'message');
        $this->assertInstanceOf(SendMessage::class, $c);
        $this->assertEquals(['chat_id' => '123', 'text' => 'message'], $c->getFields());
        $c = new SendMessage('@foo', 'message', ['disable_web_page_preview' => true]);
        $this->assertInstanceOf(SendMessage::class, $c);
        $this->assertEquals(['chat_id' => '@foo', 'text' => 'message', 'disable_web_page_preview' => true], $c->getFields());
    }

    /**
     * Test SendMessage with empty message throws exeception.
     *
     * @return void
     */
    public function testSendMessageThrowsExceptionOnEmptyMessage()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Message text cannot be empty');

        $c = new SendMessage('123', '');
    }

    /**
     * Test SendMessage with empty chat_id throws exception.
     *
     * @return void
     */
    public function testSendMessageThrowsExceptionOnEmptyChatID()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid chat_id');

        $c = new SendMessage('', 'message');
    }

    /**
     * Test SendMessage with invalid chat_id throws exception.
     *
     * @return void
     */
    public function testSendMessageThrowsExceptionOnInvalidChatID()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid chat_id');

        $c = new SendMessage('foo', 'message');
    }

    /**
     * Test SendMessage with invalid option throws exception.
     *
     * @return void
     */
    public function testSendMessageThrowsExceptionOnInvalidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown or unsupported option given');

        $c = new SendMessage('123', 'message', ['foo' => 'bar']);
    }

    /**
     * Provider to the failure options test.
     *
     * @return array
     */
    public function optionsTestProviderFail()
    {
        return [
            ['parse_mode', 'foo', 'parse_mode can be one of "Markdown" or "HTML"'],
            ['disable_web_page_preview', 42, 'disable_web_page_preview must be boolean'],
            ['disable_notification', 42, 'disable_notification must be boolean'],
            ['reply_to_message_id', 'xx', 'reply_to_message_id must be integer'],
            ['reply_markup', 42, 'Unknown or unsupported option given'],
            ['foo', 42, 'Unknown or unsupported option given']
        ];
    }

    /**
     * Test passing good and bad options to SendMessage.
     *
     * @dataProvider optionsTestProviderFail
     *
     * @param string $key
     * @param mixed  $val
     * @param string $expect
     *
     * @return void
     */
    public function testSendMessageOptionsFailures(string $key, $val, string $expect)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expect);

        $c = new SendMessage('123', 'message', [$key => $val]);
    }

    /**
     * Provider to the success options test.
     *
     * @return array
     */
    public function optionsTestProviderSuccess()
    {
        return [
            ['parse_mode', 'HTML'],
            ['disable_web_page_preview', true],
            ['disable_notification', false],
            ['reply_to_message_id', 42]
        ];
    }

    /**
     * Test passing good and bad options to SendMessage.
     *
     * @dataProvider optionsTestProviderSuccess
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return void
     */
    public function testSendMessageOptionsSuccesses(string $key, $val)
    {
        $c = new SendMessage('123', 'message', [$key => $val]);
        $this->assertInstanceOf(SendMessage::class, $c);
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
            ->willReturn('{"ok":true,"description":"Mock Success","message":{"message_id":42,"text":"test"}}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new SendMessage('42', 'test', null, null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->content['ok']);
        $this->assertTrue(is_array($res->content['message']));
        $this->assertEquals('test', $res->content['message']['text']);
    }
}
