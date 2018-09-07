<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\GetUpdates;
use madpilot78\bottg\tests\TestCase;

class GetUpdatesTest extends TestCase
{
    /**
     * Test creating a getUpdates object.
     *
     * @return void
     */
    public function testCanCreateGetUpdatesObject()
    {
        $c = new GetUpdates();
        $this->assertInstanceOf(GetUpdates::class, $c);
    }
}
