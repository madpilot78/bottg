<?php

namespace madpilot78\bottg\tests;

use Faker;
use madpilot78\bottg\Config;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Faker
     */
    private $faker;

    /**
     * @var Bot
     */
    private $config;

    /**
     * Setup code.
     *
     * Instantiates class to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->faker = Faker\Factory::create();
        $this->config = new Config();
    }

    /**
     * Teardown code.
     *
     * Frees tested object.
     *
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->faker = null;
        $this->config = null;
    }
    /**
     * Test creating a Configuration object.
     *
     * @return void
     */
    public function testCanCreateConfigObject()
    {
        $this->assertInstanceOf(Config::class, $this->config);
        $c = new Config(
            $this->faker->numberBetween($min = 0, $max = 120),
            $this->faker->numberBetween($min = 0, $max = 120),
            $this->faker->numberBetween($min = 0, $max = 120),
            $this->faker->numberBetween($min = 0, $max = 120)
        );
        $this->assertInstanceOf(Config::class, $c);
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

        $this->config->$setter(-10);
        $this->assertEquals(constant('madpilot78\bottg\Config::' . $const), $this->config->$getter());
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
        $to = $this->faker->unique()->numberBetween($min = 1, $max = 120);

        $this->assertEquals($default, $this->config->$getter());

        $this->config->$setter(0);
        $this->assertEquals(0, $this->config->$getter());

        $this->config->$setter($to);
        $this->assertEquals($to, $this->config->$getter());

        // no argument forces default
        $this->config->$setter();
        $this->assertEquals($default, $this->config->$getter());
    }
}
