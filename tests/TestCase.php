<?php

namespace madpilot78\bottg\tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * General PHP error_log() stub setup.
     *
     * @return void
     */
    protected function errorLogStub()
    {
        $elog = $this->getFunctionMock('madpilot78\bottg', 'error_log');
        $elog->expects($this->any())->willReturn(true);
    }
}
