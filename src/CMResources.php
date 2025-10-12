<?php

namespace Tractorcow\CampaignMonitor;

use CS_REST_General;
use SilverStripe\ORM\ArrayList;

/**
 * Represents a list of all base resources associated with a single api key
 * within Campaign Monitor
 *
 * @author Damian Mooyman
 */
class CMResources extends CMBase
{

    /**
     * Returns all clients accessible with the current api key
     *
     * @return ArrayList<CMClient>
     */
    public function Clients()
    {
        $this->logger->setContext(__CLASS__ . '::' . __FUNCTION__);
        $serialiser = new JsonAssocDeserialiser($this->logger);
        $interface = new CS_REST_General($this->apiKey, log: $this->logger, serialiser: $serialiser);
        $response = $this->parseResult($interface->get_clients());
        $this->logger->setContext('');

        return ArrayList::create(array_map(fn($client) => CMClient::create($this->apiKey, $client), $response));
    }

    /**
     * Retrieves the details of a client by ID
     *
     * @param string $clientID The client identifier
     * @return CMClient
     */
    public function getClient($clientID)
    {
        $client = CMClient::create($this->apiKey);
        $client->LoadByID($clientID);

        return $client;
    }

    /**
     * Retrieves a single list by ID
     *
     * @param string $listID The list identifier
     * @return CMList
     */
    public function getList($listID)
    {
        $list = CMList::create($this->apiKey);
        $list->LoadByID($listID);

        return $list;
    }

    /**
     * Retrieves a single campaign by ID
     *
     * @param string $campaignID The campaign identifier
     * @return CMCampaign
     */
    public function getCampaign($campaignID)
    {
        $campaign = CMCampaign::create($this->apiKey);
        $campaign->LoadByID($campaignID);

        return $campaign;
    }
}
