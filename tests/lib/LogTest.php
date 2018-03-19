<?php

namespace marvin255\bxloger\tests;

use marvin255\bxloger\Log;
use Psr\Log\LoggerInterface;

class LogTest extends \marvin255\bxloger\tests\BaseCase
{
    public function testSetLogger()
    {
        $loger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        Log::set($loger);

        $this->assertSame($loger, Log::get());
    }
}
