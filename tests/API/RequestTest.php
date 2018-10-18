<?php

namespace madpilot78\bottg\tests\API;

use InvalidArgumentException;
use madpilot78\bottg\API\Request;
use madpilot78\bottg\API\RequestInterface;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\API\Responses\User;
use madpilot78\bottg\Exceptions\HttpException;
use madpilot78\bottg\Exceptions\InvalidJSONException;
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
            [
                RequestInterface::GET,
                'getMe',
                null,
                '{"ok":true,"result":{"id":12345,"is_bot":true,"first_name":"testbot","username":"testbot"}}',
                'User'
            ],
            [
                RequestInterface::MPART,
                'sendChatAction',
                [
                    'chat_id' => '123',
                    'action'  => 'typing'
                ],
                '{"ok":true,"result":true}',
                'bool'
            ],
            [
                RequestInterface::JSON,
                'sendChatAction',
                [
                    'chat_id' => '123',
                    'action'  => 'typing'
                ],
                '{"ok":true,"result":true}',
                'bool'
            ]
        ];
    }

    /**
     * Test creating object without providing fields.
     *
     * @dataProvider requestTypeProvider
     *
     * @param int    $type
     * @param string $api
     * @param array  $fields
     * @param string $reply
     * @param string $ret
     *
     * @return void
     */
    public function testCanCreateRequestWithoutFields(int $type, string $api, array $fields = null, string $reply, string $ret)
    {
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
     * @param int    $type
     * @param string $api
     * @param array  $fields
     * @param string $reply
     * @param string $ret
     *
     * @return void
     */
    public function testCanCreateRequestWithFields(int $type, string $api, array $fields = null, string $reply, string $ret)
    {
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
        $this->assertEmpty($req->getFields());
    }

    /**
     * Test Request exec method returns a success response.
     *
     * @dataProvider requestTypeProvider
     *
     * @param int    $type
     * @param string $api
     * @param array  $fields
     * @param string $reply
     * @param string $ret
     *
     * @return void
     */
    public function testRequestExecReturnsReponseOnSuccess(int $type, string $api, array $fields = null, string $reply, string $ret)
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
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $req = new Request($type, $api, $fields, null, null, $http);
        $res = $req->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals(200, $res->code);
        $this->assertTrue($res->ok);
        if (ctype_upper(substr($ret, 0, 1))) {
            $this->assertInstanceOf('\\madpilot78\\bottg\\API\\Responses\\' . $ret, $res->result);
        } else {
            // assume bool if not a class
            $this->assertTrue(is_bool($res->result));
            $this->assertTrue($res->result);
        }
    }

    /**
     * Test Request exec method returns a success response, testing with parameters.
     *
     * @dataProvider requestTypeProvider
     *
     * @param int    $type
     * @param string $api
     * @param array  $fields
     * @param string $reply
     * @param string $ret
     *
     * @return void
     */
    public function testRequestExecWithParametersReturnsReponseOnSuccess(int $type, string $api, array $fields = null, string $reply, string $ret)
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
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $req = new Request($type, $api, $fields, null, null, $http);
        $res = $req->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals(200, $res->code);
        $this->assertTrue($res->ok);
        if (ctype_upper(substr($ret, 0, 1))) {
            $this->assertInstanceOf('\\madpilot78\\bottg\\API\\Responses\\' . $ret, $res->result);
        } else {
            // assume bool if not a class
            $this->assertTrue(is_bool($res->result));
            $this->assertTrue($res->result);
        }
    }

    /**
     * Provider for erro codes tests.
     *
     * @return array
     */
    public function errorTestProvider()
    {
        return [
            [
                RequestInterface::GET,
                '',
                500,
                'Server error'
            ],
            [
                RequestInterface::JSON,
                '{"ok":false,"error_code":401,"description":"Unauthorized"}',
                401,
                'Invalid telegram access token provided'
            ]
        ];
    }

    /**
     * Test Request returning server error.
     *
     * @return void
     */
    public function testRequestWithError()
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
            ->willReturn('');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 500]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Server error');

        $req = new Request(RequestInterface::GET, 'getMe', ['arg' => 'foo', 'oarg' => 42], null, null, $http);
        $res = $req->exec();
        $this->assertFalse($res);
    }

    /**
     * Test Request returning Telegram error.
     *
     * @return void
     */
    public function testRequestWithTelegramError()
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
            ->willReturn('{"ok":false,"error_code":401,"description":"Unauthorized"}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

//        $this->expectException(HttpException::class);
//        $this->expectExceptionMessage();

        $req = new Request(RequestInterface::JSON, 'sendChatAction', ['chat_id' => '123', 'action' => 'typing'], null, null, $http);
        $res = $req->exec();
        $this->assertFalse($res->ok);
        $this->assertEquals(401, $res->error_code);
        $this->assertEquals('Unauthorized', $res->description);
    }

    /**
     * Test Request Throws InvalidJSONException on invalid JSON code.
     *
     * @return void
     */
    public function testRequestWithInvalidJSON()
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
            ->willReturn("{'test': 'foo'}");

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $this->expectException(InvalidJSONException::class);
        $this->expectExceptionMessage('Syntax error');

        $req = new Request(RequestInterface::GET, 'getMe', null, null, null, $http);
        $res = $req->exec();
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

        $req = new Request(RequestInterface::GET, 'getMe', null, null, null, $http);
        $res = $req->exec();
        $this->assertFalse($res);
    }
}
