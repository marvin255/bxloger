<?php

namespace marvin255\bxloger\tests\log;

use marvin255\bxloger\log\EventLog;
use Psr\Log\LogLevel;
use CEventLog;

class EventLogTest extends \marvin255\bxloger\tests\BaseCase
{
    public function testEmergency()
    {
        $this->runTestingFor('emergency', LogLevel::EMERGENCY);
    }

    public function testAlert()
    {
        $this->runTestingFor('alert', LogLevel::ALERT);
    }

    public function testCritical()
    {
        $this->runTestingFor('critical', LogLevel::CRITICAL);
    }

    public function testError()
    {
        $this->runTestingFor('error', LogLevel::ERROR);
    }

    public function testWarning()
    {
        $this->runTestingFor('warning', LogLevel::WARNING);
    }

    public function testNotice()
    {
        $this->runTestingFor('notice', LogLevel::NOTICE);
    }

    public function testInfo()
    {
        $this->runTestingFor('info', LogLevel::INFO);
    }

    public function testDebug()
    {
        $this->runTestingFor('debug', LogLevel::DEBUG);
    }

    public function testLog()
    {
        $message = 'message_' . mt_rand();
        $context = [
            'context_1' => 'context_val_' . mt_rand(),
            'context_2' => 'context_val_' . mt_rand(),
        ];
        $toTest = [
            'context_1' => $context['context_1'],
            'context_2' => $context['context_2'],
            'DESCRIPTION' => $message,
            'SEVERITY' => strtoupper(LogLevel::NOTICE),
        ];
        ksort($toTest);

        CEventLog::add([]);
        $log = new EventLog;
        $log->log(LogLevel::NOTICE, $message, $context);

        $this->assertSame($toTest, CEventLog::getAdded());
    }

    protected function runTestingFor($method, $severity)
    {
        $message = 'message_' . mt_rand();
        $context = [
            'context_1' => 'context_val_' . mt_rand(),
            'context_2' => 'context_val_' . mt_rand(),
        ];
        $toTest = [
            'context_1' => $context['context_1'],
            'context_2' => $context['context_2'],
            'DESCRIPTION' => $message,
            'SEVERITY' => strtoupper($severity),
        ];
        ksort($toTest);

        CEventLog::add([]);
        $log = new EventLog;
        $log->$method($message, $context);

        $this->assertSame($toTest, CEventLog::getAdded());
    }
}
