<?php

namespace marvin255\bxlogger\tests;

use marvin255\bxlogger\Log;
use Psr\Log\LoggerInterface;

class LogTest extends \marvin255\bxlogger\tests\BaseCase
{
    public function testSetLogger()
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        Log::set($logger);

        $this->assertSame($logger, Log::get());
    }
}
