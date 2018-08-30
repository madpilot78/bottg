<?php

namespace madpilot78\bottg\tests;

use madpilot78\bottg\Logger;

Class LoggerTest extends \PHPUnit\Framework\TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Common mock object setup
     *
     * @param string $expected
     * @return void
     */
    private function mockSetUp(string $expected)
    {
        $elog = $this->getFunctionMock('madpilot78\bottg', 'error_log');
        $elog->expects($this->once())
            ->with($this->equalTo($expected))
            ->willReturn(true);
    }

    /**
     * Mock setup for failure tests
     *
     * In this case the error_log() function should never be called.
     *
     * @return void
     */
    private function mockFailSetUp()
    {
        $elog = $this->getFunctionMock('madpilot78\bottg', 'error_log');
        $elog->expects($this->never());
    }

    /**
     * Data provider for logger test with all data
     *
     * @return array
     */
    public function loggerAllProvider()
    {
        return [
            [ 'info', 'Info Message', '/directory/info.php', 17, 'bottg(info): Info Message - /directory/info.php:17' ],
            [ 'warn', 'Warning Message', '/directory/warn.php', 13, 'bottg(warn): Warning Message - /directory/warn.php:13' ],
            [ 'err', 'Error Message', '/directory/err.php', 7, 'bottg(err): Error Message - /directory/err.php:7' ]
        ];
    }

    /**
     * Test logger formatting correctly messages
     *
     * @dataProvider loggerAllProvider
     *
     * @param string $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @param string $expected
     * @return void
     */
    public function testLoggerAll(string $level, string $message, string $file, int $line, string $expected)
    {
        $this->mockSetUp($expected);

        $this->assertTrue(Logger::$level($message, $file, $line));
    }

    /**
     * Data provider for logger test without line numbers
     *
     * @return array
     */
    public function loggerNoLineProvider()
    {
        return [
            [ 'info', 'Info Message', '/directory/info.php', 'bottg(info): Info Message - /directory/info.php' ],
            [ 'warn', 'Warning Message', '/directory/warn.php', 'bottg(warn): Warning Message - /directory/warn.php' ],
            [ 'err', 'Error Message', '/directory/err.php', 'bottg(err): Error Message - /directory/err.php' ]
        ];
    }

    /**
     * Test logger without line information
     *
     * @dataProvider loggerNoLineProvider
     *
     * @param string $level
     * @param string $message
     * @param string $file
     * @param string $expected
     * @return void
     */
    public function testLoggerWithoutLine(string $level, string $message, string $file, string $expected)
    {
        $this->mockSetUp($expected);

        $this->assertTrue(Logger::$level($message, $file));
    }

    /**
     * Data provider for logger test without file name and line numbers
     *
     * @return array
     */
    public function loggerNoFileProvider()
    {
        return [
            [ 'info', 'Info Message', 'bottg(info): Info Message' ],
            [ 'warn', 'Warning Message', 'bottg(warn): Warning Message' ],
            [ 'err', 'Error Message', 'bottg(err): Error Message' ]
        ];
    }

    /**
     * Test logger without file or line information
     *
     * @dataProvider loggerNoFileProvider
     *
     * @param string $level
     * @param string $message
     * @param string $expected
     * @return void
     */
    public function testLoggerWithoutFile(string $level, string $message, string $expected)
    {
        $this->mockSetUp($expected);

        $this->assertTrue(Logger::$level($message));
    }

    /**
     * Test debug messages get ignored by default
     *
     * @return void
     */
    public function testLoggerIgnoresDebugMessages()
    {
        $this->mockFailSetUp();

        $this->assertTrue(Logger::debug('Debug Message', '/path/file.php', 33));
        $this->assertTrue(Logger::debug('Debug Message'));
    }

    /**
     * Test logger returns false when called with line number and no file
     *
     * @return void
     */
    public function testLoggerFailsWithLineAndNoFile()
    {
        $this->mockFailSetUp();

        $this->assertFalse(Logger::info('Failing test', '', 33));
        $this->assertFalse(Logger::info('Failing test', null, 33));
    }

    /**
     * Test logger returns false when called without a message
     *
     * @return void
     */
    public function testLoggerFailsWithoutMessage()
    {
        $this->mockFailSetUp();

        $this->assertFalse(Logger::info(''));
    }
}
