<?php

/**
 * Represents a client within the Campaign Monitor database
 *
 * @author Damian Mooyman
 * @property array $BasicDetails
 * @proerty array $BillingDetails
 */
class CMClient extends LazyLoadedCMObject {
	
	/**
	 * Provided billing details for this client
	 *
	 * @var array
	 */
	protected $billingDetails = array();
	
	protected function populateFrom($data) {
		
		$data = $this->convertToArray($data);
		
		// Check billing data
		if(isset($data['BillingDetails'])) {
			$this->setBillingFields($data['BillingDetails']);
			unset($data['BillingDetails']);
		}
		
		// Extract only basic details
		if(isset($data['BasicDetails'])) {
			$data = $data['BasicDetails'];
		}
		
		// check format of client name
		if(isset($data['Name'])) {
			$data['CompanyName'] = $data['Name'];
			unset($data['Name']);
		}
		
		parent::populateFrom($data);
	}

	public function getTitle() {
		return $this->CompanyName;
	}

	public function setTitle($value) {
		$this->CompanyName = $value;
	}

	public function getID() {
		return $this->ClientID;
	}

	public function setID($value) {
		$this->ClientID = $value;
	}

	/**
	 * Retrieves the billing details for this record
	 * 
	 * @return array
	 */
	public function getBillingFields() {
		return $this->billingDetails;
	}
	
	/**
	 * Assigns the billing details for this record
	 * 
	 * @param mixed $value Input data, either a stdObject or array
	 */
	public function setBillingFields($value) {
		$this->billingDetails = $this->convertToArray($value);
	}
	
	/**
	 * Gets a single field from the billing details
	 * 
	 * @param string $field The field name to get
	 * @return mixed The value
	 */
	public function getBillingField($field) {
		return $this->billingDetails[$field];
	}
	
	/**
	 * Assigns a billing details attribute
	 * 
	 * @param string $field The field name to set
	 * @param mixed $value The field value
	 */
	public function setBillingField($field, $value) {
		$this->billingDetails[$field] = $value;
	}

	protected function loadFullDetails() {
		$interface = new CS_REST_Clients($this->ID, $this->apiKey);
		$result = $interface->get();
		$response = $this->parseResult($result);
		$this->populateFrom($response);
	}

	/**
	 * Retrieves all lists for this client
	 * 
	 * @return ArrayList[CMList]
	 */
	public function Lists() {
		$interface = new CS_REST_Clients($this->ID, $this->apiKey);
		$result = $interface->get_lists();
		$response = $this->parseResult($result);

		$lists = new ArrayList();
		foreach ($response as $listData) {
			$lists->push(new CMList($this->apiKey, $listData));
		}
		return $lists;
	}

	public function Save() {
		user_error("Not implemented", E_USER_ERROR);
	}
}