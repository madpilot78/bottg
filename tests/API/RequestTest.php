<?php

namespace madpilot78\bottg\tests\API;

use InvalidArgumentException;
use madpilot78\bottg\API\Request;
use madpilot78\bottg\API\RequestInterface;

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
            [RequestInterface::GET],
            [RequestInterface::SUBMIT],
            [RequestInterface::MPART],
            [RequestInterface::JSON]
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
     * Check constructor throws exeception when passing invalid type.
     *
     * @return void
     */
    public function testRequestConstructRaisesExceptionOnInvalidType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown Request Type');
        $req = new Request(42, 'Thanks for all the fish');
    }

    /**
     * Check constructor throws exeception when passing empty API string.
     *
     * @return void
     */
    public function testRequestConstructRaisesExceptionOnEmptyAPI()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API string cannot be empty');
        $req = new Request(RequestInterface::GET, '');
    }

    /**
     * Test type getter/setter.
     *
     * @return void
     */
    public function testRequestTypeGetterSetter()
    {
        $req = new Request(RequestInterface::GET, 'test');
        $this->assertTrue($req->setType(RequestInterface::JSON));
        $this->assertEquals(RequestInterface::JSON, $req->getType());
    }

    /**
     * Test api getter/setter.
     *
     * @return void
     */
    public function testRequestAPIGetterSetter()
    {
        $req = new Request(RequestInterface::GET, 'test');
        $this->assertTrue($req->setAPI('tset'));
        $this->assertEquals('tset', $req->getAPI());
    }

    /**
     * Test type getter/setter.
     *
     * @return void
     */
    public function testRequestFieldsGetterSetter()
    {
        $req = new Request(RequestInterface::GET, 'test');
        $this->assertTrue($req->setFields(['light' => 'dark']));
        $this->assertEquals(['light' => 'dark'], $req->getFields());
    }
}
