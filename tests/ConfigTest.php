<?php

namespace madpilot78\bottg\tests;

use madpilot78\bottg\Config;

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
        $config = new Config(90, 90, 90, 90);
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * Data provider for connection getter/setters tests.
     *
     * @return array
     */
    public function optionsGetterSetterProvider()
    {
        return [
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
        $config->$setter($bad);
        $this->assertEquals(constant('madpilot78\bottg\Config::' . $const), $config->$getter());
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

        $config->$setter($zero);
        $this->assertEquals($zero, $config->$getter());

        $config->$setter($good);
        $this->assertEquals($good, $config->$getter());

        // no argument forces default
        $config->$setter();
        $this->assertEquals($default, $config->$getter());
    }
}
