<?php

namespace madpilot78\bottg\tests\Exceptions;

use madpilot78\bottg\Exceptions\InvalidJSONException;
use madpilot78\bottg\tests\TestCase;

class InvalidJSONExceptionTest extends TestCase
{
    /**
     * Test Throwing exception.
     *
     * @expectedException        \madpilot78\bottg\Exceptions\InvalidJSONException
     * @expectedExceptionCode    JSON_ERROR_NONE
     * @expectedExceptionMessage No error
     *
     * @throws InvalidJSONException
     *
     * @return void
     */
    public function testThrowingInvalidJSONException()
    {
        throw new InvalidJSONException();
    }

    /**
     * Test causing json_decode error.
     *
     * @expectedException        \madpilot78\bottg\Exceptions\InvalidJSONException
     * @expectedExceptionCode    JSON_ERROR_SYNTAX
     * @expectedExceptionMessage Syntax error
     *
     * @throws InvalidJSONException
     *
     * @return void
     */
    public function testInvalidJSONException()
    {
        $o = json_decode("{'test': 'foo'}");
        $this->assertNull($o);

        throw new InvalidJSONException();
    }
}
