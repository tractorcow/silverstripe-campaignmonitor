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

	public function getBillingFields() {
		return $this->billingDetails;
	}
	
	public function setBillingFields($value) {
		$this->billingDetails = $this->convertToArray($value);
	}
	
	public function getBillingField($field) {
		return $this->billingDetails[$field];
	}
	
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
	 * @return DataObjectSet[CMList]
	 */
	public function Lists() {
		$interface = new CS_REST_Clients($this->ID, $this->apiKey);
		$result = $interface->get_lists();
		$response = $this->parseResult($result);

		$lists = new DataObjectSet();
		foreach ($response as $listData) {
			$lists->push(new CMList($this->apiKey, $listData));
		}
		return $lists;
	}

	public function Save() {
		user_error("Not implemented", E_USER_ERROR);
	}
}