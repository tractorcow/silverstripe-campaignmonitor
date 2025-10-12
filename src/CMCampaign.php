<?php

namespace Tractorcow\CampaignMonitor;

use CS_REST_Clients;

/**
 * Represents a campaign assigned to a client within the Campaign Monitor database
 *
 * @author Michael Parkhill
 */
class CMCampaign extends LazyLoadedCMObject
{
    public function getClientID()
    {
        return $this->ClientID;
    }

    public function setClientID($value)
    {
        $this->ClientID = $value;
    }

    public function Save()
    {
        user_error("Not implemented", E_USER_ERROR);
    }

    protected function loadFullDetails()
    {
        $this->logger->setContext(__CLASS__ . '::' . __FUNCTION__);
        $serialiser = new JsonAssocDeserialiser($this->logger);
        $interface = new CS_REST_Clients($this->ClientID, $this->apiKey, log: $this->logger, serialiser: $serialiser);
        $result = $interface->get_campaign();
        $response = $this->parseResult($result);
        $this->populateFrom($response);
        $this->logger->setContext('');
    }
}
