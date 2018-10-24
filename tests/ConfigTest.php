<?php

namespace madpilot78\bottg\tests;

use InvalidArgumentException;
use madpilot78\bottg\Config;
use madpilot78\bottg\Logger;

class ConfigTest extends TestCase
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
        $config = new Config('token', 'foo', Logger::ERR, 90, 90, 90, 90, 'user:pwd@proxy');
        $this->assertInstanceOf(Config::class, $config);
        $config = new Config('token', 'foo', Logger::ERR, 90, 90, 90, 90, 'user:pwd@proxy:8080');
        $this->assertInstanceOf(Config::class, $config);
        $config = new Config('token', 'foo', Logger::ERR, 90, 90, 90, 90, 'proxy:8080');
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * Provider for constructor failure test.
     *
     * @return array
     */
    public function constructorFailureProvider()
    {
        return [
            ['', null, null, null, null, null, null, null],
            [null, '', null, null, null, null, null, null],
            [null, null, 42, null, null, null, null, null],
            [null, null, null, -10, null, null, null, null],
            [null, null, null, null, -10, null, null, null],
            [null, null, null, null, null, -10, null, null],
            [null, null, null, null, null, null, -10, null],
            [null, null, null, null, null, null, null, '@']
        ];
    }

    /**
     * Test constructor throws exception for invalid values.
     *
     * @dataProvider constructorFailureProvider
     *
     * @param string $token
     * @param string $id
     * @param int    $lvl
     * @param int    $cto
     * @param int    $to
     * @param int    $pto
     * @param int    $plmt
     * @param string $proxy
     *
     * @return void
     */
    public function testConstructorThrowsErrorOnInvalidValues(
        string $token = null,
        string $id = null,
        int $lvl = null,
        int $cto = null,
        int $to = null,
        int $pto = null,
        int $plmt = null,
        string $proxy = null
    ) {
        $this->expectException(InvalidArgumentException::class);
        $config = new Config($token, $id, $lvl, $cto, $to, $pto, $plmt, $proxy);
    }

    /**
     * Data provider for connection getter/setters tests.
     *
     * @return array
     */
    public function optionsGetterSetterProvider()
    {
        return [
            ['Token', 'DEF_TOKEN', 'token', null, ''],
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

    /**
     * Test proxy getters/setter
     *
     * @return void
     */
    public function testProxyGettersSetter()
    {
        $config = new Config();

        $this->assertFalse($config->setProxy(':1234'));
        $this->assertNull($config->getProxyHost());
        $this->assertNull($config->getProxyPort());
        $this->assertNull($config->getProxyAuth());

        // Make sure sure malformed strings are correctly refused
        $this->assertFalse($config->setProxy('@'));
        $this->assertFalse($config->setProxy(':pwd@proxy'));
        $this->assertFalse($config->setProxy('foo@proxy'));

        $this->assertTrue($config->setProxy('user:@proxyhost'));
        $this->assertEquals('proxyhost', $config->getProxyHost());
        $this->assertEquals('user:', $config->getProxyAuth());
        $this->assertTrue($config->setProxy(null));

        $this->assertTrue($config->setProxy('proxyhost'));
        $this->assertEquals('proxyhost', $config->getProxyHost());
        $this->assertEquals(8080, $config->getProxyPort());
        $this->assertNull($config->getProxyAuth());

        $this->assertTrue($config->setProxy('user:pwd@proxy2:1234'));
        $this->assertEquals('proxy2', $config->getProxyHost());
        $this->assertEquals(1234, $config->getProxyPort());
        $this->assertEquals('user:pwd', $config->getProxyAuth());

        $this->assertTrue($config->setProxy(''));
        $this->assertNull($config->getProxyHost());
        $this->assertNull($config->getProxyPort());
        $this->assertNull($config->getProxyAuth());
    }
}
