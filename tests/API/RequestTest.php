<?php

namespace madpilot78\bottg\tests\API;

use InvalidArgumentException;
use madpilot78\bottg\API\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Provides the available types of requests.
     *
     * @return array
     */
    public function requestTypeProvider()
    {
        return [
            [Request::GET],
            [Request::SUBMIT],
            [Request::MPART],
            [Request::JSON]
        ];
    }

    /**
     * Test creating object without providing fields.
     *
     * @dataProvider requestTypeProvider
     *
     * @param int $type
     *
     * @return void
     */
    public function testCanCreateRequestWithoutFields(int $type)
    {
        $api = 'test';

        $req = new Request($type, $api);
        $this->assertInstanceOf(Request::class, $req);
        $this->assertEquals($type, $req->getType());
        $this->assertEquals($api, $req->getAPI());
        $this->assertNull($req->getFields());
    }

    /**
     * Test creating object providing fields.
     *
     * @dataProvider requestTypeProvider
     *
     * @param int $type
     *
     * @return void
     */
    public function testCanCreateRequestWithFields(int $type)
    {
        $api = 'test';
        $fields = ['foo' => 'bar'];

        $req = new Request($type, $api, $fields);
        $this->assertInstanceOf(Request::class, $req);
        $this->assertEquals($type, $req->getType());
        $this->assertEquals($api, $req->getAPI());
        $this->assertEquals($fialds, $req->getFields());
    }

    /**
     * Check constructor throws exeception when passing invalid type
     *
     * @return void
     */
    public function testRequestConstructRaisesExceptionOnInvalidType()
    {
        $this->expectException(InvalidArgumentException::class);
        $req = new Request(42, 'Thanks for all the fish');
    }

    /**
     * Check constructor throws exeception when passing empty API string
     *
     * @return void
     */
    public function testRequestConstructRaisesExceptionOnEmptyAPI()
    {
        $this->expectException(InvalidArgumentException::class);
        $req = new Request(Request::GET, '');
    }
}
