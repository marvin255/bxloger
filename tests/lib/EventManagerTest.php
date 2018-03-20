<?php

namespace marvin255\bxlogger\tests;

use marvin255\bxlogger\EventManager;
use marvin255\bxlogger\Log;
use marvin255\bxlogger\log\QueuedLoggerInterface;

class EventManagerTest extends \marvin255\bxlogger\tests\BaseCase
{
    public function testOnAfterEpilog()
    {
        $logger = $this->getMockBuilder(QueuedLoggerInterface::class)->getMock();
        $logger->expects($this->once())->method('flush');

        Log::set($logger);
        EventManager::onAfterEpilog();
    }
}
