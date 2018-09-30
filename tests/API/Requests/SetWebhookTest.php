<?php

namespace madpilot78\bottg\tests\API\Requests;

use CURLFile;
use InvalidArgumentException;
use madpilot78\bottg\API\Response;
use madpilot78\bottg\API\Requests\SetWebhook;
use madpilot78\bottg\Http\HttpInterface;
use madpilot78\bottg\tests\TestCase;
use TypeError;

class SetWebhookTest extends TestCase
{
    /**
     * Test SetWebhook requires arguments.
     *
     * @return void
     */
    public function testSetWebhookRequiresArguments()
    {
        $this->expectException(TypeError::class);
        $c = new SetWebhook();
    }

    /**
     * Test creating a SetWebhook object.
     *
     * @return void
     */
    public function testCanCreateSetWebhookObject()
    {
        $c = new SetWebhook(['https://www.test.org/123']);
        $this->assertInstanceOf(SetWebhook::class, $c);
        $this->assertEquals(['url' => 'https://www.test.org/123'], $c->getFields());
        $c = new SetWebhook(['https://www.test.org/123', 'tests/API/Requests/SetWebhookTest.php']);
        $this->assertInstanceOf(SetWebhook::class, $c);
        $f = $c->getFields();
        $this->assertInstanceOf(CURLFile::class, $f['certificate']);
    }

    /**
     * Test SetWebhook with URL not starting with https:// fails.
     *
     * @return void
     */
    public function testSetWebhookThrowsExceptionOnWrongURL()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('URL must start with "https://"');

        $c = new SetWebhook(['foobar']);
    }

    /**
     * Test SetWebhook with empty URL throws exception.
     *
     * @return void
     */
    public function testSetWebhookThrowsExceptionOnEmptyURL()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('URL cannot be empty');

        $c = new SetWebhook(['']);
    }

    /**
     * Test SetWebhook with empty args throws exception.
     *
     * @return void
     */
    public function testSetWebhookThrowsExceptionOnEmptyArgs()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong argument count');

        $c = new SetWebhook([]);
    }

    /**
     * Test SetWebhook with too many args throws exception.
     *
     * @return void
     */
    public function testSetWebhookThrowsExceptionOnTooManyArgs()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong argument count');

        $c = new SetWebhook(['https://www.test.org/123', 'cert', 'foo']);
    }

    /**
     * Test SetWebhook with non readable file throws exception.
     *
     * @return void
     */
    public function testSetWebhookThrowsExceptionOnUnreadableFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cert file must exist and be readable');

        $c = new SetWebhook(['https://www.test.org/123', '']);
    }

    /**
     * Test exec method returns a success response.
     *
     * @return void
     */
    public function testExecReturnsReponseOnSuccess()
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
            ->willReturn('{"ok":true,"description":"Mock Success"}');

        $http->expects($this->once())
            ->method('getInfo')
            ->willReturn(['http_code' => 200]);

        $http->expects($this->never())
            ->method('getError');

        $this->errorLogStub();

        $c = new SetWebhook(['https://www.test.org/123'], null, null, $http);
        $res = $c->exec();
        $this->assertInstanceOf(Response::class, $res);
        $this->assertTrue($res->content['ok']);
    }
}
