<?php

namespace madpilot78\bottg\tests\API;

use InvalidArgumentException;
use madpilot78\bottg\API\Request;
use madpilot78\bottg\API\RequestInterface;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\Exceptions\HttpException;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;

class RequestTest extends TestCase
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
        $this->assertEmpty($req->getFields());
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
        $this->assertEquals($fields, $req->getFields());
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
     * Test empty array for fields.
     *
     * @return void
     */
    public function testRequestWithEmptyArrayFields()
    {
        $req = new Request(RequestInterface::MPART, 'test', []);
        $this->assertEmpty($req->getFields());
    }

    /**
     * Test type getter/setter.
     *
     * @return void
     */
    public function testRequestTypeGetterSetter()
    {
        $req = new Request(RequestInterface::GET, 'test');
        $req->setType(RequestInterface::JSON);
        $this->assertEquals(RequestInterface::JSON, $req->getType());
    }

    /**
     * Test setting invalid type throws exception.
     *
     * @return void
     */
    public function testRequestTypeSetterFailsOnInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown Request Type');
        $req = new Request(42, 'Mostly harmless');
    }

    /**
     * Test api getter/setter.
     *
     * @return void
     */
    public function testRequestAPIGetterSetter()
    {
        $req = new Request(RequestInterface::GET, 'test');
        $req->setAPI('tset');
        $this->assertEquals('tset', $req->getAPI());
    }

    /**
     * Test fields getter/setter.
     *
     * @return void
     */
    public function testRequestFieldsGetterSetter()
    {
        $req = new Request(RequestInterface::GET, 'test');
        $req->setFields(['light' => 'dark']);
        $this->assertEquals(['light' => 'dark'], $req->getFields());
    }

    /**
     * Test and empty array is converted to null by the setter.
     *
     * @return void
     */
    public function testRequestFieldsSetterConvertsEmptyArrayToNull()
    {
        $req = new Request(RequestInterface::GET, 'test');
        $req->setFields([]);
        $this->assertNull($req->getFields());
    }

    /**
     * Test Request exec method returns a success response.
     *
     * @dataProvider requestTypeProvider
     *
     * @param int $type
     *
     * @return void
     */
    public function testRequestExecReturnsReponseOnSuccess(int $type)
    {
        $http = $this->getMockBuilder(HttpInterface::class)
            ->setMethods(['setOpts', 'exec', 'getInfo', 'getError'])
            ->getMock();

        $http->expects($this->atLeastOnce())
            ->method('setOpts')
            ->with($this->callback(function ($s) {
                return is_array($s);
            }))
            ->willReturn(true);

        $http->expects($this->once())
            ->method('exec')
            ->willReturn("{ 'ok': true, 'description': 'Mock Reply' }");

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $req = new Request($type, 'test', null, null, null, $http);
        $res = $req->exec();
        $this->assertInstanceOf(Response::class, $res);
    }

    /**
     * Test Request exec method returns a success response, testing with parameters.
     *
     * @dataProvider requestTypeProvider
     *
     * @param int $type
     *
     * @return void
     */
    public function testRequestExecWithParametersReturnsReponseOnSuccess(int $type)
    {
        $http = $this->getMockBuilder(HttpInterface::class)
            ->setMethods(['setOpts', 'exec', 'getInfo', 'getError'])
            ->getMock();

        $http->expects($this->atLeastOnce())
            ->method('setOpts')
            ->with($this->callback(function ($s) {
                return is_array($s);
            }))
            ->willReturn(true);

        $http->expects($this->once())
            ->method('exec')
            ->willReturn("{ 'ok': true, 'description': 'Mock Reply' }");

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $req = new Request($type, 'test', ['arg' => 'val', 'oarg' => 42], null, null, $http);
        $res = $req->exec();
        $this->assertInstanceOf(Response::class, $res);
    }

    /**
     * Provider for erro codes tests.
     *
     * @return array
     */
    public function errorTestProvider()
    {
        return [
            [RequestInterface::GET, '', 500, 'Server error'],
            [
                RequestInterface::JSON,
                "{ 'error_code': 33, 'description': 'Mock error' }", 401, 'Invalid telegram access token provided'
            ]
        ];
    }

    /**
     * Test Request returning server errors.
     *
     * @dataProvider errorTestProvider
     *
     * @param int    $type
     * @param string $reply
     * @param int    $error
     * @param string $expect
     *
     * @return void
     */
    public function testRequestWithError(int $type, string $reply, int $error, string $expect)
    {
        $http = $this->getMockBuilder(HttpInterface::class)
            ->setMethods(['setOpts', 'exec', 'getInfo', 'getError'])
            ->getMock();

        $http->expects($this->atLeastOnce())
            ->method('setOpts')
            ->with($this->callback(function ($s) {
                return is_array($s);
            }))
            ->willReturn(true);

        $http->expects($this->once())
            ->method('exec')
            ->willReturn($reply);

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => $error]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage($expect);

        $req = new Request($type, 'test', ['arg' => 'val', 'oarg' => 42], null, null, $http);
        $res = $req->exec();
        $this->assertFalse($res);
    }

    /**
     * Test Request failing to connect to server.
     *
     * @return void
     */
    public function testRequestFailsToConnect()
    {
        $http = $this->getMockBuilder(HttpInterface::class)
            ->setMethods(['setOpts', 'exec', 'getInfo', 'getError'])
            ->getMock();

        $http->expects($this->atLeastOnce())
            ->method('setOpts')
            ->with($this->callback(function ($s) {
                return is_array($s);
            }))
            ->willReturn(true);

        $http->expects($this->once())
            ->method('exec')
            ->willReturn(false);

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(false);

        $http->expects($this->once())
            ->method('getError')
            ->willReturn([
                'errno' => 33,
                'error' => 'error'
            ]);

        $this->errorLogStub();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Error contacting server: (33) error');

        $req = new Request(RequestInterface::GET, 'test', ['arg' => 'val', 'oarg' => 42], null, null, $http);
        $res = $req->exec();
        $this->assertFalse($res);
    }
}
