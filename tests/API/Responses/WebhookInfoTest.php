<?php

namespace madpilot78\bottg\tests\API\Responses;

use InvalidArgumentException;
use madpilot78\bottg\API\Responses\WebhookInfo;
use madpilot78\bottg\tests\TestCase;

class WebhookInfoTest extends TestCase
{
    /**
     * Test creating a WebhookInfo Response object.
     *
     * @return void
     */
    public function testCanCreateUserObject()
    {
        $s = json_decode('{"url":"https://www.test.net:8443/webhook","has_custom_certificate":true,"pending_update_count":0,"max_connections":40}');
        $t = new WebhookInfo($s);
        $this->assertInstanceOf(WebhookInfo::class, $t);
        $this->assertEquals('https://www.test.net:8443/webhook', $t->url);
        $this->assertTrue($t->has_custom_certificate);
    }

    /**
     * Test creating User with missing mandatory parts throws exception.
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Required value missing: pending_update_count
     *
     * @return void
     */
    public function testCreateUserWithMissingPartsFails()
    {
        $s = json_decode('{"url":"https://www.test.net:8443/webhook","has_custom_certificate":true}');
        $t = new WebhookInfo($s);
    }

    /**
     * Test creating User with invalid mandatory parts throws exception.
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Invalid value: has_custom_certificate
     *
     * @return void
     */
    public function testCreateUserWithInvalidPartsFails()
    {
        $s = json_decode('{"url":"https://www.test.net:8443/webhook","has_custom_certificate":1,"pending_update_count":0,"max_connections":40}');
        $t = new WebhookInfo($s);
    }
}
