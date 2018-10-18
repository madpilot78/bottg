<?php

namespace madpilot78\bottg\tests\API\Responses;

use InvalidArgumentException;
use madpilot78\bottg\API\Responses\Message;
use madpilot78\bottg\API\Responses\Update;
use madpilot78\bottg\tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * Test creating an Update Response object.
     *
     * @return void
     */
    public function testCanCreateUpdateObject()
    {
        $s = json_decode('{"update_id":1138,"message":{"message_id":4242,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"date":1539700746,"chat":{"id":12345,"type":"private","username":"testbot","first_name":"testbot"},"text":"test"}}');
        $t = new Update($s);
        $this->assertInstanceOf(Update::class, $t);
        $this->assertEquals(1138, $t->update_id);
        $this->assertInstanceOf(Message::class, $t->message);
        $this->assertTrue($t->message->from->is_bot);
        $this->assertEquals('test', $t->message->text);
    }

    /**
     * Test creating Update with missing mandatory parts throws exception.
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Required value missing: update_id
     *
     * @return void
     */
    public function testCreateUpdateWithMissingPartsFails()
    {
        $s = json_decode('{"message":{"message_id":4242,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"date":1539700746,"chat":{"id":12345,"type":"private","username":"testbot","first_name":"testbot"},"text":"test"}}');
        $t = new Update($s);
    }

    /**
     * Test creating Update with invalid value.
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid value: update_id
     *
     * @return void
     */
    public function testCreateUpdateWithInvalidValueFails()
    {
        $s = json_decode('{"update_id":"1138","message":{"message_id":4242,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"date":1539700746,"chat":{"id":12345,"type":"private","username":"testbot","first_name":"testbot"},"text":"test"}}');
        $t = new Update($s);
    }
}
