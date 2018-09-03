<?php

namespace madpilot78\bottg\tests;

use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Logger;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test creating a Configuration object.
     *
     * @return void
     */
    public function testCanCreateConfigObject()
    {
        $config = new Config();
        $this->assertInstanceOf(Config::class, $config);
        $config = new Config('foo', Logger::ERR, 90, 90, 90, 90);
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * Test constructor throws exception for invalid values.
     *
     * @return void
     */
    public function testConstructorThrowsErrorOnInvalidValues()
    {
        $this->expectException(InvalidArgumentException::class);
        $config = new Config(null, null, -10);
    }

    /**
     * Data provider for connection getter/setters tests.
     *
     * @return array
     */
    public function optionsGetterSetterProvider()
    {
        return [
            ['LogID', 'DEF_LOGID', 'test', 'foo', ''],
            ['LogMin', 'DEF_LOGMIN', Logger::ERR, Logger::DEBUG, 42],
            ['ConnectTimeout', 'DEF_CONNECT_TIMEOUT', 42, 0, -10],
            ['Timeout', 'DEF_TIMEOUT', 42, 0, -10],
            ['PollTimeout', 'DEF_POLL_TIMEOUT', 42, 0, -10],
            ['PollLimit', 'DEF_POLL_LIMIT', 42, 0, -10]
        ];
    }

    /**
     * Test setting invalid options fail.
     *
     * @dataProvider optionsGetterSetterProvider
     *
     * @param string $method
     * @param string $const
     * @param mixed  $good
     * @param mixed  $zero
     * @param mixed  $bad
     *
     * @return void
     */
    public function testInvalidOptionsSetFail(string $method, string $const, $good, $zero, $bad)
    {
        $setter = 'set' . $method;
        $getter = 'get' . $method;

        $config = new Config();
        $this->assertTrue($config->$setter($good));
        $this->assertFalse($config->$setter($bad));
        $this->assertEquals($good, $config->$getter());
    }

    /**
     * Test setting/getting option properties.
     *
     * @dataProvider optionsGetterSetterProvider
     *
     * @param string $method
     * @param string $const
     * @param mixed  $good
     * @param mixed  $zero
     * @param mixed  $bad
     *
     * @return void
     */
    public function testSetGetOptions(string $method, string $const, $good, $zero, $bad)
    {
        $setter = 'set' . $method;
        $getter = 'get' . $method;
        $default = constant('madpilot78\bottg\Config::' . $const);

        $config = new Config();
        $this->assertEquals($default, $config->$getter());

        $this->assertTrue($config->$setter($zero));
        $this->assertEquals($zero, $config->$getter());

        $this->assertTrue($config->$setter($good));
        $this->assertEquals($good, $config->$getter());

        // no argument forces default
        $this->assertTrue($config->$setter());
        $this->assertEquals($default, $config->$getter());
    }
}
