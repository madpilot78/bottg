<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\SetWebhook;
use madpilot78\bottg\tests\TestCase;

class SetWebhookTest extends TestCase
{
    /**
     * Test creating a setWebHook object.
     *
     * @return void
     */
    public function testCanCreateSetWebhookObject()
    {
        $c = new SetWebhook();
        $this->assertInstanceOf(SetWebhook::class, $c);
    }
}
