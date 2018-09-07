<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\GetWebhookInfo;
use madpilot78\bottg\tests\TestCase;

class GetWebhookInfoTest extends TestCase
{
    /**
     * Test creating a getWebhookInfo object.
     *
     * @return void
     */
    public function testCanCreateGetWebhookInfoObject()
    {
        $c = new GetWebhookInfo();
        $this->assertInstanceOf(GetWebhookInfo::class, $c);
    }
}
