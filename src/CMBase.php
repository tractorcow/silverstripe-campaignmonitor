<?php

namespace Tractorcow\CampaignMonitor;

use Psr\Log\LogLevel;
use SilverStripe\View\ViewableData;

/**
 * Base class for Campaign Monitor objects
 *
 * @author Damian Mooyman
 */
abstract class CMBase extends ViewableData
{
    protected $logger;

    /**
     * The API key used for future requests
     *
     * @var string
     */
    protected $apiKey = null;

    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey;
        $this->logger = CMLogger::create();
    }

    /**
     * Checks that a result is successful
     *
     * @param CS_REST_Wrapper_Result $result
     * @throws CMError
     */
    protected function checkResult($result)
    {
        if (!$result->was_successful()) {
            $this->logger->log_message($result->response['Message'], static::class, LogLevel::ERROR);
            throw new CMError($result->response['Message'], $result->http_status_code);
        }

        return true;
    }

    /**
     * Safely extracts results from a CM API call
     *
     * @param CS_REST_Wrapper_Result $result
     * @return mixed
     */
    protected function parseResult($result)
    {
        $this->checkResult($result);

        return $result->response;
    }
}
