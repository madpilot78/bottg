<?php

namespace madpilot78\bottg\tests\Exceptions;

use madpilot78\bottg\Exceptions\InvalidJSONException;
use madpilot78\bottg\tests\TestCase;

class InvalidJSONExceptionTest extends TestCase
{
    /**
     * Test Throwing exception.
     *
     * @throws InvalidJSONException
     *
     * @return void
     */
    public function testThrowingInvalidJSONException()
    {
        $this->expectException('\madpilot78\bottg\Exceptions\InvalidJSONException');
        $this->expectExceptionCode(JSON_ERROR_NONE);
        $this->expectExceptionMessage('No error');

        throw new InvalidJSONException();
    }

    /**
     * Test causing json_decode error.
     *
     * @throws InvalidJSONException
     *
     * @return void
     */
    public function testInvalidJSONException()
    {
        $this->expectException('\madpilot78\bottg\Exceptions\InvalidJSONException');
        $this->expectExceptionCode(JSON_ERROR_SYNTAX);
        $this->expectExceptionMessage('Syntax error');

        $o = json_decode("{'test': 'foo'}");
        $this->assertNull($o);

        throw new InvalidJSONException();
    }
}
