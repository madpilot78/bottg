<?php

namespace madpilot78\bottg\tests;

use madpilot78\bottg\Bot;

class BotTest extends \PHPUnit\Framework\TestCase
{
    use \phpmock\phpunit\PHPMock;

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
}
