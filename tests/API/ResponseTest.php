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
        $res = new Response('getMe');
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
        $reply = '{"ok":true,"result":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"}}';
        $res = new Response('getMe', $reply);
        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals($reply, $res->getRaw());
        $this->assertTrue($res->ok);
        $this->assertEquals(12345, $res->result->id);
        $this->assertEquals('testbot', $res->result->username);
    }

    /**
     * Test populating response using saveReply.
     *
     * @return void
     */
    public function testCreateAndPopulateResponseSaveReply()
    {
        $reply = '{"ok":true,"result":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"}}';
        $res = new Response('getMe');
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->saveReply($reply));
        $this->assertEquals($reply, $res->getRaw());
        $this->assertTrue($res->ok);
        $this->assertEquals(12345, $res->result->id);
        $this->assertEquals('testbot', $res->result->username);
    }

    /**
     * Test populating response with a failed request.
     *
     *  @return void
     */
    public function testCreateAndPopulateResponseFailed()
    {
        $reply = '{"ok":false,"error_code":404,"description":"Not Found: method not found"}';
        $res = new Response('foo', $reply, 200);
        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals($reply, $res->getRaw());
        $this->assertFalse($res->ok);
        $this->assertEquals('Not Found: method not found', $res->description);
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
        $res = new Response('getMe', "{'test': 'foo'}");
    }

    /**
     * Test invalid json in saveReply() throws exception.
     *
     * @expectedException        \madpilot78\bottg\Exceptions\InvalidJSONException
     * @expectedExceptionCode    JSON_ERROR_SYNTAX
     * @expectedExceptionMessage Syntax error
     *
     * @return void
     */
    public function testCreteResponseWithInvalidJSONSaveReply()
    {
        $res = new Response('getMe');
        $this->assertInstanceOf(Response::class, $res);
        $res->saveReply("{'test': 'foo'}");
    }
}
