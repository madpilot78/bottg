<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\GetMe;
use madpilot78\bottg\tests\TestCase;

class GetMeTest extends TestCase
{
    /**
     * Test creating a getMe object.
     *
     * @return void
     */
    public function testCanCreateGetMeObject()
    {
        $c = new GetMe();
        $this->assertInstanceOf(GetMe::class, $c);
    }
}
