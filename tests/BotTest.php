<?php

namespace madpilot78\bottg\tests;

use madpilot78\bottg\Bot;
use madpilot78\bottg\Config;

class BotTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test object is correctly insstantiated.
     *
     * @return void
     */
    public function testCreatedObject()
    {
        $bot = new Bot('token');
        $this->assertInstanceOf(Bot::class, $bot);
        $conf = new Config('test');
        $bot = new Bot('token', $conf);
        $this->assertInstanceOf(Bot::class, $bot);
    }
}
