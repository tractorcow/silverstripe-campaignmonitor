<?php

/**
 * Represents a campaign assigned to a client within the Campaign Monitor database
 *
 * @author Michael Parkhill
 */
class CMCampaign extends LazyLoadedCMObject {

	protected function populateFrom($data) {
		$data = $this->convertToArray($data);
		parent::populateFrom($data);
	}

	public function getClientID() {
		return $this->ClientID;
	}

	public function setClientID($value) {
		$this->ClientID = $value;
	}

	public function Save() {
		user_error("Not implemented", E_USER_ERROR);
	}

	protected function loadFullDetails() {
		$interface = new CS_REST_Clients($this->ClientID, $this->apiKey);
		$result = $interface->get_campaign();
		$response = $this->parseResult($result);
		$this->populateFrom($response);
	}
}