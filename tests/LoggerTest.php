<?php

namespace madpilot78\bottg\tests;

use madpilot78\bottg\Config;
use madpilot78\bottg\Logger;

class LoggerTest extends \PHPUnit\Framework\TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Common mock object setup.
     *
     * @param string $expected
     *
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
     * Mock setup for failure tests.
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
     * Test constructor.
     *
     * @return void
     */
    public function testCreatingLoggerObject()
    {
        $logger = new Logger();
        $this->assertInstanceOf(Logger::class, $logger);
        $config = new Config('test', Logger::ERR);
        $logger = new Logger($config);
        $this->assertInstanceOf(Logger::class, $logger);
    }

    /**
     * Data provider for logger test with all data.
     *
     * @return array
     */
    public function loggerAllProvider()
    {
        return [
            [
                'info',
                'Info Message',
                '/directory/info.php',
                17,
                'bottg (INFO): Info Message - /directory/info.php:17'
            ],
            [
                'warn',
                'Warning Message',
                '/directory/warn.php',
                13,
                'bottg (WARN): Warning Message - /directory/warn.php:13'
            ],
            [
                'err',
                'Error Message',
                '/directory/err.php',
                7,
                'bottg (ERR): Error Message - /directory/err.php:7'
            ]
        ];
    }

    /**
     * Test logger formatting correctly messages.
     *
     * @dataProvider loggerAllProvider
     *
     * @param string $level
     * @param string $message
     * @param string $file
     * @param int    $line
     * @param string $expected
     *
     * @return void
     */
    public function testLoggerAll(string $level, string $message, string $file, int $line, string $expected)
    {
        $this->mockSetUp($expected);

        $logger = new Logger();
        $this->assertTrue($logger->$level($message, $file, $line));
    }

    /**
     * Data provider for logger test without line numbers.
     *
     * @return array
     */
    public function loggerNoLineProvider()
    {
        return [
            ['info', 'Info Message', '/directory/info.php', 'bottg (INFO): Info Message - /directory/info.php'],
            ['warn', 'Warning Message', '/directory/warn.php', 'bottg (WARN): Warning Message - /directory/warn.php'],
            ['err', 'Error Message', '/directory/err.php', 'bottg (ERR): Error Message - /directory/err.php']
        ];
    }

    /**
     * Test logger without line information.
     *
     * @dataProvider loggerNoLineProvider
     *
     * @param string $level
     * @param string $message
     * @param string $file
     * @param string $expected
     *
     * @return void
     */
    public function testLoggerWithoutLine(string $level, string $message, string $file, string $expected)
    {
        $this->mockSetUp($expected);

        $logger = new Logger();
        $this->assertTrue($logger->$level($message, $file));
    }

    /**
     * Data provider for logger test without file name and line numbers.
     *
     * @return array
     */
    public function loggerNoFileProvider()
    {
        return [
            ['info', 'Info Message', 'bottg (INFO): Info Message'],
            ['warn', 'Warning Message', 'bottg (WARN): Warning Message'],
            ['err', 'Error Message', 'bottg (ERR): Error Message']
        ];
    }

    /**
     * Test logger without file or line information.
     *
     * @dataProvider loggerNoFileProvider
     *
     * @param string $level
     * @param string $message
     * @param string $expected
     *
     * @return void
     */
    public function testLoggerWithoutFile(string $level, string $message, string $expected)
    {
        $this->mockSetUp($expected);

        $logger = new Logger();
        $this->assertTrue($logger->$level($message));
    }

    /**
     * Test Debug level ignored by default.
     *
     * @return void
     */
    public function testLoggerIgnoresDebugMessages()
    {
        $this->mockFailSetUp();

        $logger = new Logger();
        $this->assertTrue($logger->debug('Debug Message', '/path/file.php', 33));
        $this->assertTrue($logger->debug('Debug Message'));
    }

    /**
     * Test enabling debug level.
     *
     * @return void
     */
    public function testLoggerEnablingDebugLevel()
    {
        $this->mockSetUp('bottg (DEBUG): Debug Message');

        $conf = new Config(null, Logger::DEBUG);
        $logger = new Logger($conf);
        $this->assertTrue($logger->debug('Debug Message'));
    }

    /**
     * Test changing logger ID.
     *
     * @return void
     */
    public function testLoggerWithCustomID()
    {
        $this->mockSetUp('testme (WARN): Warning Message');

        $conf = new Config('testme');
        $logger = new Logger($conf);
        $this->assertTrue($logger->warn('Warning Message'));
    }

    /**
     * Test logger returns false when called with line number and no file.
     *
     * @return void
     */
    public function testLoggerFailsWithLineAndNoFile()
    {
        $this->mockFailSetUp();

        $logger = new Logger();
        $this->assertFalse($logger->info('Failing test', '', 33));
        $this->assertFalse($logger->info('Failing test', null, 33));
    }

    /**
     * Test logger returns false when called without a message.
     *
     * @return void
     */
    public function testLoggerFailsWithoutMessage()
    {
        $this->mockFailSetUp();

        $logger = new Logger();
        $this->assertFalse($logger->info(''));
    }
}
