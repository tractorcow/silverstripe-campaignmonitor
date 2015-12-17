<?php

/**
 * Base class for Campaign Monitor objects
 *
 * @author Damian Mooyman
 */
abstract class CMBase extends ViewableData
{
    
    /**
     * The API key used for future requests
     * 
     * @var string
     */
    protected $apiKey = null;
    
    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey;
    }
    
    /**
     * Checks that a result is successful
     * 
     * @param type $result
     * @throws CMError 
     */
    protected function checkResult($result)
    {
        if (!$result->was_successful()) {
            throw new CMError($result->response->Message, $result->http_status_code);
        }
        return true;
    }
    
    /**
     * Safely extracts results from a CM API call
     * 
     * @param type $result
     * @return type 
     */
    protected function parseResult($result)
    {
        $this->checkResult($result);
        return $result->response;
    }
}
