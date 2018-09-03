<?php

namespace madpilot78\bottg\tests\API;

use madpilot78\bottg\API\Response;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test creating a Response object.
     *
     * @return void
     */
    public function testCanCreateRResponseObject()
    {
        $res = new Response();
        $this->assertInstanceOf(Response::class, $res);
    }
}
