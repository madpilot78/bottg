<?php

namespace madpilot78\bottg\tests;

use InvalidArgumentException;
use madpilot78\bottg\Bot;
use madpilot78\bottg\Config;

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
}
