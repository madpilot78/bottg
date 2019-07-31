<?php

namespace madpilot78\bottg\tests\API\Responses;

use madpilot78\bottg\API\Responses\User;
use madpilot78\bottg\tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test creating a User Response object.
     *
     * @return void
     */
    public function testCanCreateUserObject()
    {
        $s = json_decode('{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"}');
        $t = new User($s);
        $this->assertInstanceOf(User::class, $t);
        $this->assertEquals(12345, $t->id);
        $this->assertTrue($t->is_bot);
    }

    /**
     * Test creating User with missing mandatory parts throws exception.
     *
     * @return void
     */
    public function testCreateUserWithMissingPartsFails()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Required value missing: is_bot');

        $s = json_decode('{"id":12345,"first_name":"testbot","username":"testbot"}');
        $t = new User($s);
    }

    /**
     * Test creating User with invalid mandatory parts throws exception.
     *
     * @return void
     */
    public function testCreateUserWithInvalidPartsFails()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid value: id');

        $s = json_decode('{"id":"12345","is_bot":true,"first_name":"testbot","username":"testbot"}');
        $t = new User($s);
    }
}
