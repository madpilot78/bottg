<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\SendChatAction;
use madpilot78\bottg\tests\TestCase;

class SendChatActionTest extends TestCase
{
    /**
     * Test creating a SendChatAction object.
     *
     * @return void
     */
    public function testCanCreateSendChatActionObject()
    {
        $c = new SendChatAction();
        $this->assertInstanceOf(SendChatAction::class, $c);
    }
}
