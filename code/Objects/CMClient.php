<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CMClient
 *
 * @author Damo
 * @property mixed $BasicDetails
 * @proerty mixed $BillingDetails
 */
class CMClient extends LazyLoadedCMObject {
	
	/**
	 * @var CS_REST_Clients
	 */
	protected $clientInterface = null;

	/**
	 * Determine if full details for this client have been loaded
	 */
	protected function hasLoadedFullDetails() {
		return $this->record && isset($this->record->BasicDetails);
	}

	public function getTitle() {
		if ($this->hasLoadedFullDetails()) {
			return $this->record->BasicDetails->CompanyName;
		} elseif (isset($this->record->Name)) {
			return $this->record->Name;
		}
	}
	
	public function setTitle($value) {
		if ($this->hasLoadedFullDetails()) {
			$this->record->BasicDetails->CompanyName = $value;
		} elseif ($this->record) {
			$this->record->Name = $value;
		}
	}
	
	
	public function getID() {
		if ($this->hasLoadedFullDetails()) {
			return $this->record->BasicDetails->ClientID;
		} elseif (isset($this->record->ClientID)) {
			return $this->record->ClientID;
		}
	}
	
	
	public function setID($value) {
		if ($this->hasLoadedFullDetails()) {
			$this->record->BasicDetails->ClientID = $value;
		} elseif ($this->record) {
			$this->record->ClientID = $value;
		}
	}

	/**
	 * 
	 * @param string $apiKey
	 * @param mixed $data
	 * @param CS_REST_Clients $clientInterface 
	 */
	function __construct($apiKey, $data, $clientInterface = null) {
		parent::__construct($apiKey, $data);

		$this->clientInterface = $clientInterface;
	}
	
	function buildRestInterface() {
		return new CS_REST_Clients($this->ID, $this->apiKey);
	}
	
	/**
	 * Retrieves all lists for this client
	 * @return DataObjectSet[CMList]
	 */
	public function Lists() {
		$interface = $this->loadRestInterface();
		$result = $interface->get_lists();
		$response = $this->parseResult($result);
		
		$lists = new DataObjectSet();
		foreach($response as $listData) {
			$lists->push(new CMList($this->apiKey, $listData));
		}
		return $lists;
	}
}