<?php

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
     * @return ArrayList[CMClient]
     * @throws CMError 
     */
    public function Clients()
    {
        $interface = new CS_REST_General($this->apiKey);
        $result = $interface->get_clients();
        $response = $this->parseResult($result);
        
        // Save each client
        $clients = new ArrayList();
        foreach ($response as $clientData) {
            $clients->push(new CMClient($this->apiKey, $clientData));
        }
        return $clients;
    }
    
    /**
     * Retrieves the details of a client by ID
     * 
     * @param string $clientID The client identifier
     * @return CMClient
     */
    public function getClient($clientID)
    {
        $client = new CMClient($this->apiKey);
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
        $list = new CMList($this->apiKey);
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
        $campaign = new CMCampaign($this->apiKey);
        $campaign->LoadByID($campaignID);
        return $campaign;
    }
}
