<?php

namespace marvin255\bxlogger\tests\log;

use marvin255\bxlogger\log\EventLogQueued;
use Psr\Log\LogLevel;

class EventLogQueuedTest extends \marvin255\bxlogger\tests\BaseCase
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

    public function testLogQueue()
    {
        define('SITE_ID', 'SITE_ID_' . mt_rand());
        $userId = mt_rand();
        $message1 = 'message_1_' . mt_rand();
        $message2 = 'message_2_' . mt_rand();
        $context = [
            'AUDIT_TYPE_ID' => 'AUDIT_TYPE_ID_' . mt_rand(),
            'MODULE_ID' => 'MODULE_ID_' . mt_rand(),
        ];
        $userAgent = 'USER_AGENT_' . mt_rand();
        $requestUri = 'REQUEST_URI_' . mt_rand();
        $remoteAddr = 'REMOTE_ADDR_' . mt_rand();

        $toTest = [
            [
                'AUDIT_TYPE_ID' => $context['AUDIT_TYPE_ID'],
                'MODULE_ID' => $context['MODULE_ID'],
                'SITE_ID' => SITE_ID,
                'DESCRIPTION' => $message1,
                'SEVERITY' => strtoupper(LogLevel::INFO),
                'USER_AGENT' => $userAgent,
                'REQUEST_URI' => $requestUri,
                'REMOTE_ADDR' => $remoteAddr,
                'USER_ID' => $userId,
            ],
            [
                'AUDIT_TYPE_ID' => $context['AUDIT_TYPE_ID'],
                'MODULE_ID' => $context['MODULE_ID'],
                'SITE_ID' => SITE_ID,
                'DESCRIPTION' => $message2,
                'SEVERITY' => strtoupper(LogLevel::NOTICE),
                'USER_AGENT' => $userAgent,
                'REQUEST_URI' => $requestUri,
                'REMOTE_ADDR' => $remoteAddr,
                'USER_ID' => $userId,
            ],
        ];

        global $USER;
        $USER = $this->getMockBuilder('\\CUser')
            ->setMethods(['getId'])
            ->getMock();
        $USER->method('getId')->will($this->returnValue($userId));

        $request = $this->getMockBuilder('\\Bitrix\\Main\\HttpRequest')
            ->setMethods(['getUserAgent', 'getRequestUri', 'getRemoteAddress'])
            ->getMock();
        $request->method('getUserAgent')->will($this->returnValue($userAgent));
        $request->method('getRequestUri')->will($this->returnValue($requestUri));
        $request->method('getRemoteAddress')->will($this->returnValue($remoteAddr));

        $sqlHelper = $this->getMockBuilder('\\Bitrix\\Main\\DB\\SqlHelper')
            ->setMethods(['prepareInsert'])
            ->getMock();
        $sqlHelper->method('prepareInsert')->will($this->returnCallback(function ($table, $fields) {
            $return = ['', ''];
            foreach ($fields as $key => $value) {
                $return[0] .= (empty($return[0]) ? '' : ', ') . "`{$key}`";
                $return[1] .= (empty($return[1]) ? '' : ', ') . "'{$value}'";
            }

            return $return;
        }));

        $connection = $this->getMockBuilder('\\Bitrix\\Main\\DB\\Connection')
            ->setMethods(['getSqlHelper', 'query'])
            ->getMock();
        $connection->method('getSqlHelper')->will($this->returnValue($sqlHelper));
        $connection->expects($this->once())
            ->method('query')
            ->with($this->callback(function ($sql) use ($toTest) {
                $return = true;
                foreach ($toTest as $testItem) {
                    foreach ($testItem as $key => $item) {
                        $return = mb_strpos($sql, $key) !== false
                            && mb_strpos($sql, $item) !== false;
                        if (!$return) {
                            break 2;
                        }
                    }
                }

                return $return;
            }));

        $log = new EventLogQueued($connection, $request);
        $log->changeQueueUsageStatus(true);
        $log->log(LogLevel::INFO, $message1, $context);
        $log->log(LogLevel::NOTICE, $message2, $context);
        $log->flush();
    }

    protected function runTestingFor($method, $severity)
    {
        $userId = mt_rand();
        $message = 'message_' . mt_rand();
        $context = [
            'AUDIT_TYPE_ID' => 'AUDIT_TYPE_ID_' . mt_rand(),
            'MODULE_ID' => 'MODULE_ID_' . mt_rand(),
            'SITE_ID' => 'SITE_ID_' . mt_rand(),
        ];
        $userAgent = 'USER_AGENT_' . mt_rand();
        $requestUri = 'REQUEST_URI_' . mt_rand();
        $remoteAddr = 'REMOTE_ADDR_' . mt_rand();

        $toTest = [
            'AUDIT_TYPE_ID' => $context['AUDIT_TYPE_ID'],
            'MODULE_ID' => $context['MODULE_ID'],
            'SITE_ID' => $context['SITE_ID'],
            'DESCRIPTION' => $message,
            'SEVERITY' => strtoupper($severity),
            'USER_AGENT' => $userAgent,
            'REQUEST_URI' => $requestUri,
            'REMOTE_ADDR' => $remoteAddr,
            'USER_ID' => $userId,
        ];

        global $USER;
        $USER = $this->getMockBuilder('\\CUser')
            ->setMethods(['getId'])
            ->getMock();
        $USER->method('getId')->will($this->returnValue($userId));

        $request = $this->getMockBuilder('\\Bitrix\\Main\\HttpRequest')
            ->setMethods(['getUserAgent', 'getRequestUri', 'getRemoteAddress'])
            ->getMock();
        $request->method('getUserAgent')->will($this->returnValue($userAgent));
        $request->method('getRequestUri')->will($this->returnValue($requestUri));
        $request->method('getRemoteAddress')->will($this->returnValue($remoteAddr));

        $sqlHelper = $this->getMockBuilder('\\Bitrix\\Main\\DB\\SqlHelper')
            ->setMethods(['prepareInsert'])
            ->getMock();
        $sqlHelper->method('prepareInsert')->will($this->returnCallback(function ($table, $fields) {
            $return = ['', ''];
            foreach ($fields as $key => $value) {
                $return[0] .= (empty($return[0]) ? '' : ', ') . "`{$key}`";
                $return[1] .= (empty($return[1]) ? '' : ', ') . "'{$value}'";
            }

            return $return;
        }));

        $connection = $this->getMockBuilder('\\Bitrix\\Main\\DB\\Connection')
            ->setMethods(['getSqlHelper', 'query'])
            ->getMock();
        $connection->method('getSqlHelper')->will($this->returnValue($sqlHelper));
        $connection->expects($this->once())
            ->method('query')
            ->with($this->callback(function ($sql) use ($toTest) {
                $return = true;
                foreach ($toTest as $key => $item) {
                    $return = mb_strpos($sql, $key) !== false
                        && mb_strpos($sql, $item) !== false;
                    if (!$return) {
                        break;
                    }
                }

                return $return;
            }));

        $log = new EventLogQueued($connection, $request);
        $log->changeQueueUsageStatus(false)->$method($message, $context);
    }
}
