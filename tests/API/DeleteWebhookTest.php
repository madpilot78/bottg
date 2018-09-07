<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\DeleteWebhook;
use madpilot78\bottg\tests\TestCase;

class DeleteWebhookTest extends TestCase
{
    /**
     * Test creating a deleteWebhook object.
     *
     * @return void
     */
    public function testCanCreateDeleteWebhookObject()
    {
        $c = new DeleteWebhook();
        $this->assertInstanceOf(DeleteWebhook::class, $c);
    }
}
