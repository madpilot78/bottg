<?php

namespace madpilot78\bottg\tests\API\Responses;

use InvalidArgumentException;
use madpilot78\bottg\API\Responses\Chat;
use madpilot78\bottg\API\Responses\Message;
use madpilot78\bottg\API\Responses\User;
use madpilot78\bottg\tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * Test creating a Message Response object.
     *
     * @return void
     */
    public function testCanCreateMessageObject()
    {
        $s = json_decode('{"message_id":4242,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"date":1539700746,"chat":{"id":12345,"type":"private","username":"testbot","first_name":"testbot"},"text":"test"}');
        $t = new Message($s);
        $this->assertInstanceOf(Message::class, $t);
        $this->assertEquals(4242, $t->message_id);
        $this->assertInstanceOf(User::class, $t->from);
        $this->assertTrue($t->from->is_bot);
        $this->assertInstanceOf(Chat::class, $t->chat);
        $this->assertEquals('private', $t->chat->type);
        $this->assertEquals('test', $t->text);
    }

    /**
     * Test creating a Message Response object simulating a channel message.
     *
     * @return void
     */
    public function testCanCreateMessageFromChannel()
    {
        $s = json_decode('{"message_id":4242,"from":{},"date":1539700746,"chat":{"id":12345,"type":"channel","title":"foo","username":"cuser"},"text":"test"}');
        $t = new Message($s);
        $this->assertInstanceOf(Message::class, $t);
        $this->assertEquals(4242, $t->message_id);
        $this->assertNull($t->from);
        $this->assertInstanceOf(Chat::class, $t->chat);
        $this->assertEquals('channel', $t->chat->type);
        $this->assertEquals('test', $t->text);
    }

    /**
     * Test creating a Message Response object simulating a reply.
     *
     * @return void
     */
    public function testCanCreateMessageReply()
    {
        $s = json_decode('{"message_id":4242,"from":{"id":12345,"is_bot":false,"username":"foouser","first_name":"Foo","last_name":"Bar"},"date":1539700746,"chat":{"id":67890,"type":"group","title":"misc"},"reply_to_message":{"message_id":3131,"from":{"id":4422,"is_bot":false,"username":"bazuser","first_name":"Baz","last_name":"Bar"},"date":1539700000,"chat":{"id":67890,"type":"group","title":"misc"},"text":"test"},"text":"test reply"}');
        $t = new Message($s);
        $this->assertInstanceOf(Message::class, $t);
        $this->assertEquals(4242, $t->message_id);
        $this->assertInstanceOf(User::class, $t->from);
        $this->assertFalse($t->from->is_bot);
        $this->assertInstanceOf(Chat::class, $t->chat);
        $this->assertEquals('group', $t->chat->type);
        $this->assertEquals('misc', $t->chat->title);
        $this->assertInstanceOf(Message::class, $t->reply_to_message);
        $this->assertInstanceOf(User::class, $t->reply_to_message->from);
        $this->assertEquals('bazuser', $t->reply_to_message->from->username);
        $this->assertInstanceOf(Chat::class, $t->reply_to_message->chat);
        $this->assertEquals($t->chat->title, $t->reply_to_message->chat->title);
        $this->assertEquals('test', $t->reply_to_message->text);
        $this->assertEquals('test reply', $t->text);
    }

    /**
     * Test creating a Message Response object simulating a forwarded message.
     *
     * @return void
     */
    public function testCanCreateMessageForwarded()
    {
        $s = json_decode('{"message_id":4242,"from":{"id":12345,"is_bot":false,"username":"foouser","first_name":"Foo","last_name":"Bar"},"date":1539700746,"chat":{"id":12345,"type":"private","username":"foouser","first_name":"Foo","last_name":"Bar"},"forward_from":{"id":4444,"is_bot":false,"username":"bazuser","first_name":"Baz","last_name":"Bar"},"forward_date":1539700000}');
        $t = new Message($s);
        $this->assertInstanceOf(Message::class, $t);
        $this->assertEquals(4242, $t->message_id);
        $this->assertInstanceOf(User::class, $t->from);
        $this->assertFalse($t->from->is_bot);
        $this->assertInstanceOf(Chat::class, $t->chat);
        $this->assertEquals('private', $t->chat->type);
        $this->assertInstanceOf(User::class, $t->forward_from);
        $this->assertEquals('Bar', $t->forward_from->last_name);
        $this->assertEquals(1539700000, $t->forward_date);
        $this->assertNull($t->text);
    }

    /**
     * Test creating Message with missing mandatory parts throws exception.
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Required value missing: date
     *
     * @return void
     */
    public function testCreateMessageWithMissingPartsFails()
    {
        $s = json_decode('{"message_id":4242,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"chat":{"id":12345,"type":"private","username":"testbot","first_name":"testbot"},"text":"test"}');
        $t = new Message($s);
    }

    /**
     * Test creating Message with invalid value.
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid value: date
     *
     * @return void
     */
    public function testCreateMessageWithInvalidValueFails()
    {
        $s = json_decode('{"message_id":4242,"from":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"},"date":"wrong","chat":{"id":12345,"type":"private","username":"testbot","first_name":"testbot"},"text":"test"}');
        $t = new Message($s);
    }
}
