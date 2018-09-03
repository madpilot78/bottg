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
            ['ConnectTimeout', 'DEF_CONNECT_TIMEOUT'],
            ['Timeout', 'DEF_TIMEOUT'],
            ['PollTimeout', 'DEF_POLL_TIMEOUT'],
            ['PollLimit', 'DEF_POLL_LIMIT']
        ];
    }

    /**
     * Test setting invalid options fail.
     *
     * @dataProvider optionsGetterSetterProvider
     *
     * @param string $method
     * @param string $const
     *
     * @return void
     */
    public function testInvalidOptionsSetFail(string $method, string $const)
    {
        $setter = 'set' . $method;
        $getter = 'get' . $method;

        $config = new Config();
        $config->$setter(-10);
        $this->assertEquals(constant('madpilot78\bottg\Config::' . $const), $config->$getter());
    }

    /**
     * Test setting/getting option properties.
     *
     * @dataProvider optionsGetterSetterProvider
     *
     * @param string $method
     * @param string $const
     *
     * @return void
     */
    public function testSetGetOptions(string $method, string $const)
    {
        $setter = 'set' . $method;
        $getter = 'get' . $method;
        $default = constant('madpilot78\bottg\Config::' . $const);
        $to = 42;

        $config = new Config();
        $this->assertEquals($default, $config->$getter());

        $config->$setter(0);
        $this->assertEquals(0, $config->$getter());

        $config->$setter($to);
        $this->assertEquals($to, $config->$getter());

        // no argument forces default
        $config->$setter();
        $this->assertEquals($default, $config->$getter());
    }
}
