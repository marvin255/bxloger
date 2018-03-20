<?php

namespace marvin255\bxlogger\tests\log;

use marvin255\bxlogger\log\Composite;
use marvin255\bxlogger\log\QueuedLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class CompositeTest extends \marvin255\bxlogger\tests\BaseCase
{
    public function testLog()
    {
        $level = LogLevel::ERROR;
        $message = 'message_' . mt_rand();
        $context = [
            'context_key_1_' . mt_rand() => 'context_value_1_' . mt_rand(),
            'context_key_2_' . mt_rand() => 'context_value_2_' . mt_rand(),
            'context_key_3_' . mt_rand() => 'context_value_3_' . mt_rand(),
        ];

        $logger1 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger1->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $logger2 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger2->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $composite = new Composite;
        $composite->addLogger($logger1);
        $composite->addLogger($logger2);
        $composite->log($level, $message, $context);
    }

    public function testFlush()
    {
        $level = LogLevel::ERROR;
        $message = 'message_' . mt_rand();
        $context = [
            'context_key_1_' . mt_rand() => 'context_value_1_' . mt_rand(),
            'context_key_2_' . mt_rand() => 'context_value_2_' . mt_rand(),
            'context_key_3_' . mt_rand() => 'context_value_3_' . mt_rand(),
        ];

        $logger1 = $this->getMockBuilder(QueuedLoggerInterface::class)->getMock();
        $logger1->expects($this->atLeastOnce())->method('flush');

        $logger2 = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $composite = new Composite;
        $composite->addLogger($logger1);
        $composite->addLogger($logger2);
        $composite->flush();
    }

    public function testChangeQueueUsageStatus()
    {
        $level = LogLevel::ERROR;
        $message = 'message_' . mt_rand();
        $context = [
            'context_key_1_' . mt_rand() => 'context_value_1_' . mt_rand(),
            'context_key_2_' . mt_rand() => 'context_value_2_' . mt_rand(),
            'context_key_3_' . mt_rand() => 'context_value_3_' . mt_rand(),
        ];

        $logger1 = $this->getMockBuilder(QueuedLoggerInterface::class)->getMock();
        $logger1->expects($this->atLeastOnce())
            ->method('changeQueueUsageStatus')
            ->with($this->equalTo(false));

        $logger2 = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $composite = new Composite;
        $composite->addLogger($logger1);
        $composite->addLogger($logger2);
        $composite->changeQueueUsageStatus(false);
    }

    public function testSetLoggers()
    {
        $level = LogLevel::ERROR;
        $message = 'message_' . mt_rand();
        $context = [
            'context_key_1_' . mt_rand() => 'context_value_1_' . mt_rand(),
            'context_key_2_' . mt_rand() => 'context_value_2_' . mt_rand(),
            'context_key_3_' . mt_rand() => 'context_value_3_' . mt_rand(),
        ];

        $logger1 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger1->expects($this->never())->method('log');

        $logger2 = $this->getMockBuilder(QueuedLoggerInterface::class)->getMock();
        $logger2->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $logger3 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger3->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $composite = new Composite;
        $composite->addLogger($logger1);
        $composite->setLoggers([$logger2, $logger3]);
        $composite->log($level, $message, $context);
    }
}
