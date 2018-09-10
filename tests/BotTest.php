<?php

namespace madpilot78\bottg\tests;

use InvalidArgumentException;
use madpilot78\bottg\Bot;
use madpilot78\bottg\Config;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\API\Response;

class BotTest extends TestCase
{
    /**
     * Test creating Bot specifying token.
     *
     * @return void
     */
    public function testCreateBotWithToken()
    {
        $bot = new Bot('token');
        $this->assertInstanceOf(Bot::class, $bot);
        $this->assertInstanceOf(Config::class, $bot->config);
        $this->assertEquals('token', $bot->config->getToken());
    }

    /**
     * Test creating Bot with Token in config.
     *
     * @return void
     */
    public function testCreateBotWithConfig()
    {
        $conf = new Config('token', 'test');
        $bot = new Bot($conf);
        $this->assertInstanceOf(Bot::class, $bot);
        $this->assertInstanceOf(Config::class, $bot->config);
        $this->assertEquals('token', $bot->config->getToken());
        $this->assertEquals('test', $bot->config->getLogID());
    }

    /**
     * Test creating Bot with empty config.
     *
     * @return void
     */
    public function testCreateBotWithEmptyConfig()
    {
        $conf = new Config();
        $bot = new Bot($conf);
        $this->assertInstanceOf(Bot::class, $bot);
        $this->assertInstanceOf(Config::class, $bot->config);
        $this->assertNull($bot->config->getToken());
    }

    /**
     * Test creating bot with empty string fails.
     *
     * @return void
     */
    public function testCreateBotWithEmptyTokenFails()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Token cannot be empty');
        $bot = new Bot('');
    }

    /**
     * Test creating bot with wrong object type fails.
     *
     * @return void
     */
    public function testCreateBotWithWrongObjectFails()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Token or Config object required');
        $bot = new Bot([]);
    }

    /**
     * Test creating a bot and making request.
     *
     * @return void
     */
    public function testCreateBotAndMakeRequestReturnsResponse()
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
            ->willReturn('{"ok":true,"description":"User info","user":{"id":222,"is_bot":true,"first_name":"test"}}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $bot = new Bot('token', null, $http);
        $res = $bot->getMe();
        $this->assertInstanceOf(Response::class, $res);
    }

    /**
     * test calling wrong Request method from bot object
     *
     * @return void
     */
    public function testCreateBotAndCallWrongRequestMethod()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown method');

        $bot = new Bot('token');
        $res = $bot->foo();
    }
}
