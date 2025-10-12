<?php

namespace Tractorcow\CampaignMonitor;

use CS_REST_Clients;
use SilverStripe\ORM\ArrayList;

/**
 * Represents a client within the Campaign Monitor database
 *
 * @author Damian Mooyman
 * @property array $BasicDetails
 * @proerty array $BillingDetails
 */
class CMClient extends LazyLoadedCMObject
{

    /**
     * Provided billing details for this client
     *
     * @var array
     */
    protected $billingDetails = array();

    protected function populateFrom($data)
    {
        // Check billing data
        if (isset($data['BillingDetails'])) {
            $this->setBillingFields($data['BillingDetails']);
            unset($data['BillingDetails']);
        }

        // Extract only basic details
        if (isset($data['BasicDetails'])) {
            $data = $data['BasicDetails'];
        }

        // check format of client name
        if (isset($data['Name'])) {
            $data['CompanyName'] = $data['Name'];
            unset($data['Name']);
        }

        parent::populateFrom($data);
    }

    public function getTitle()
    {
        return $this->CompanyName;
    }

    public function setTitle($value)
    {
        $this->CompanyName = $value;
    }

    public function getID()
    {
        return $this->ClientID;
    }

    public function setID($value)
    {
        $this->ClientID = $value;
    }

    /**
     * Retrieves the billing details for this record
     *
     * @return array
     */
    public function getBillingFields()
    {
        return $this->billingDetails;
    }

    /**
     * Assigns the billing details for this record
     *
     * @param mixed $value Input data, either a stdObject or array
     */
    public function setBillingFields($value)
    {
        $this->billingDetails = $this->convertToArray($value);
    }

    /**
     * Gets a single field from the billing details
     *
     * @param string $field The field name to get
     * @return mixed The value
     */
    public function getBillingField($field)
    {
        return $this->billingDetails[$field];
    }

    /**
     * Assigns a billing details attribute
     *
     * @param string $field The field name to set
     * @param mixed $value The field value
     */
    public function setBillingField($field, $value)
    {
        $this->billingDetails[$field] = $value;
    }

    protected function loadFullDetails()
    {
        $this->logger->setContext(__CLASS__ . '::' . __FUNCTION__);
        $serialiser = new JsonAssocDeserialiser($this->logger);
        $interface = new CS_REST_Clients($this->ID, $this->apiKey, log: $this->logger, serialiser: $serialiser);
        $response = $this->parseResult($interface->get());
        $this->populateFrom($response);
        $this->logger->setContext('');
    }

    /**
     * Retrieves all lists for this client
     *
     * @return ArrayList<CMList>
     */
    public function Lists()
    {
        $this->logger->setContext(__CLASS__ . '::' . __FUNCTION__);
        $serialiser = new JsonAssocDeserialiser($this->logger);
        $interface = new CS_REST_Clients($this->ID, $this->apiKey, log: $this->logger, serialiser: $serialiser);
        $response = $this->parseResult($interface->get_lists());
        $this->logger->setContext('');

        return ArrayList::create(array_map(fn($list) => CMList::create($this->apiKey, $list), $response));
    }

    public function Save()
    {
        user_error("Not implemented", E_USER_ERROR);
    }

    /**
     * Retrieves all campaigns for this client
     *
     * @return ArrayList<CMCampaign>
     */
    public function Campaigns()
    {
        $this->logger->setContext(__CLASS__ . '::' . __FUNCTION__);
        $serialiser = new JsonAssocDeserialiser($this->logger);
        $interface = new CS_REST_Clients($this->ID, $this->apiKey, log: $this->logger, serialiser: $serialiser);
        $response = $this->parseResult($interface->get_campaigns());
        $this->logger->setContext('');

        return ArrayList::create(array_map(
            fn($campaign) => CMCampaign::create($this->apiKey, $campaign),
            $response['Results']
        ));
    }
}
