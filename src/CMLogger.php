<?php

namespace Tractorcow\CampaignMonitor;

use InvalidArgumentException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SilverStripe\Core\Injector\Injectable;

if (is_dir(__DIR__ . '/../vendor')) {
    require_once(__DIR__ . '/../vendor/campaignmonitor/createsend-php/class/log.php');
} else {
    require_once(__DIR__ . '/../../../campaignmonitor/createsend-php/class/log.php');
}

/**
 * Implements the "invisible" interface of CS_REST_Log
 * translating from Campaign Monitor's API log calls to the PSR interface
 */
class CMLogger
{
    use LoggerAwareTrait;
    use Injectable;

    private string $context = '';

    public const CONVERT = [
        \CS_REST_LOG_ERROR => LogLevel::ERROR,
        \CS_REST_LOG_WARNING => LogLevel::WARNING,
        \CS_REST_LOG_VERBOSE => LogLevel::DEBUG,
    ];

    public function __construct(?LoggerInterface $logger = null)
    {
        if ($logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * @param string $message
     * @param string $module
     * @param int|string $level One of the Campaign Monitor defined constants, or a PSR-3 LogLevel.
     * @throws InvalidArgumentException
     * @see CM_REST_Log
     * @link vendor/campaignmonitor/createsend-php/class/log.php
     */
    public function log_message($message, $module, $level) {
        if (CS_REST_LOG_NONE === $level || !$this->logger) {
            return;
        }
        $level = self::CONVERT[$level] ?? $level;
        switch ($level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::ERROR:
            case LogLevel::WARNING:
            case LogLevel::NOTICE:
            case LogLevel::INFO:
            case LogLevel::DEBUG:
                break;
            default:
                throw new InvalidArgumentException('Bad log level: ' . $level);
        }
        $fullMessage = trim($this->context . ' ' . $message, ' ');
        $this->logger->log($level, $fullMessage, ['module' => $module]);
    }

    public function setContext(string $context)
    {
        $this->context = $context;
        return $this;
    }
}
