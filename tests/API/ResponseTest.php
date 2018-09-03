<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\Response;
use madpilot78\bottg\tests\TestCase;

class ResponseTest extends TestCase
{
    /**
     * Test creating a Response object.
     *
     * @return void
     */
    public function testCanCreateResponseObject()
    {
        $res = new Response();
        $this->assertInstanceOf(Response::class, $res);
    }
}
