<?php

namespace madpilot78\bottg\tests\API\Responses;

use madpilot78\bottg\API\Responses\Chat;
use madpilot78\bottg\tests\TestCase;

class ChatTest extends TestCase
{
    /**
     * Test creating a Chat Response object.
     *
     * @return void
     */
    public function testCanCreateChatObject()
    {
        $s = json_decode('{"id":12345,"type":"private","username":"testbot","first_name":"testbot"}');
        $t = new Chat($s);
        $this->assertInstanceOf(Chat::class, $t);
        $this->assertEquals(12345, $t->id);
        $this->assertEquals('private', $t->type);
    }

    /**
     * Test creating Chat with missing mandatory parts throws exception.
     *
     * @return void
     */
    public function testCreateChatWithMissingPartsFails()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Required value missing: id');

        $s = json_decode('{"first_name":"testbot","username":"testbot"}');
        $t = new Chat($s);
    }

    /**
     * Test creating Chat with invalid mandatory parts throws exception.
     *
     * @return void
     */
    public function testCreateChatWithInvalidPartsFails()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid value: id');

        $s = json_decode('{"id":"12345","type":"private","username":"testbot","first_name":"testbot"}');
        $t = new Chat($s);
    }
}
