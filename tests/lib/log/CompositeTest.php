<?php

namespace marvin255\bxloger\tests\log;

use marvin255\bxloger\log\Composite;
use marvin255\bxloger\log\QueuedLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class CompositeTest extends \marvin255\bxloger\tests\BaseCase
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

        $loger1 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $loger1->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $loger2 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $loger2->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $composite = new Composite;
        $composite->addLoger($loger1);
        $composite->addLoger($loger2);
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

        $loger1 = $this->getMockBuilder(QueuedLoggerInterface::class)->getMock();
        $loger1->expects($this->atLeastOnce())->method('flush');

        $loger2 = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $composite = new Composite;
        $composite->addLoger($loger1);
        $composite->addLoger($loger2);
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

        $loger1 = $this->getMockBuilder(QueuedLoggerInterface::class)->getMock();
        $loger1->expects($this->atLeastOnce())
            ->method('changeQueueUsageStatus')
            ->with($this->equalTo(false));

        $loger2 = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $composite = new Composite;
        $composite->addLoger($loger1);
        $composite->addLoger($loger2);
        $composite->changeQueueUsageStatus(false);
    }

    public function testSetLogers()
    {
        $level = LogLevel::ERROR;
        $message = 'message_' . mt_rand();
        $context = [
            'context_key_1_' . mt_rand() => 'context_value_1_' . mt_rand(),
            'context_key_2_' . mt_rand() => 'context_value_2_' . mt_rand(),
            'context_key_3_' . mt_rand() => 'context_value_3_' . mt_rand(),
        ];

        $loger1 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $loger1->expects($this->never())->method('log');

        $loger2 = $this->getMockBuilder(QueuedLoggerInterface::class)->getMock();
        $loger2->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $loger3 = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $loger3->expects($this->once())
            ->method('log')
            ->with($this->equalTo($level), $this->equalTo($message), $this->equalTo($context))
        ;

        $composite = new Composite;
        $composite->addLoger($loger1);
        $composite->setLogers([$loger2, $loger3]);
        $composite->log($level, $message, $context);
    }
}
