<?php

namespace madpilot78\bottg\tests;

use madpilot78\bottg\Bot;

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
    }
}
