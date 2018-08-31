<?php

namespace madpilot78\bottg\tests;

use madpilot78\bottg\Bot;
use Faker;

class BotTest extends \PHPUnit\Framework\TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Faker object.
     *
     * @var
     */
    private $faker;

    /**
     * The object being tested lives here.
     *
     * @var
     */
    private $bot;

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
        $this->bot = new Bot('token');
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
        $this->bot = null;
    }

    /**
     * Test object is correctly insstantiated.
     *
     * @return void
     */
    public function testCreatedObject()
    {
        $this->assertInstanceOf(Bot::class, $this->bot);
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

        $this->assertFalse($this->bot->$setter(-10));
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
        $default = constant('madpilot78\bottg\Bot::' . $const);
        $to = $this->faker->unique()->numberBetween($min = 1, $max = 120);

        $this->assertEquals($default, $this->bot->$getter());

        $this->assertTrue($this->bot->$setter(0));
        $this->assertEquals(0, $this->bot->$getter());

        $this->assertTrue($this->bot->$setter($to));
        $this->assertEquals($to, $this->bot->$getter());

        // no argument forces default
        $this->assertTrue($this->bot->$setter());
        $this->assertEquals($default, $this->bot->$getter());
    }
}
