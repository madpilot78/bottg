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

    /**
     * Test populating response with fake reply data.
     *
     * This is a simple test for a stub implementation.
     *
     * @return void
     */
    public function testCreateAndPopulateResponse()
    {
        $reply = '{"ok":true,"description":"User info","user":{"id":222,"is_bot":true,"first_name":"test"}}';
        $res = new Response();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->saveReply($reply));
        $this->assertEquals($reply, $res->getRaw());
        $this->assertTrue($res->content->ok);
        $this->assertEquals(222, $res->content->user->id);
    }

    /**
     * Test invalid json throws exception.
     *
     * @expectedException        \madpilot78\bottg\Exceptions\InvalidJSONException
     * @expectedExceptionCode    JSON_ERROR_SYNTAX
     * @expectedExceptionMessage Syntax error
     *
     * @return void
     */
    public function testCreteResponseWithInvalidJSON()
    {
        $res = new Response();
        $this->assertInstanceOf(Response::class, $res);
        $res->saveReply("{'test': 'foo'}");
    }
}
