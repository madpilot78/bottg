<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\SendMessage;
use madpilot78\bottg\tests\TestCase;

class SendMessageTest extends TestCase
{
    /**
     * Test creating a SendMessage object.
     *
     * @return void
     */
    public function testCanCreateSendMessageObject()
    {
        $c = new SendMessage();
        $this->assertInstanceOf(SendMessage::class, $c);
    }
}
