<?php

/**
 * Represents a list assigned to a client within the Campaign Monitor database
 *
 * @author Damian Mooyman
 * @property boolean $ConfirmedOptIn
 * @property string $UnsubscribePage
 * @property string $UnsubscribeSetting
 * @property string $ConfirmationSuccessPage
 */
class CMList extends LazyLoadedCMObject {
	
	protected function populateFrom($data) {
		
		$data = $this->convertToArray($data);
		
		// Convert from "summary" format to normal format
		if(isset($data['Name'])) {
			$data['Title'] = $data['Name'];
			unset($data['Name']);
		}
		
		parent::populateFrom($data);
	}
	
	public function getID() {
		return $this->ListID;
	}
	
	public function setID($value) {
		$this->ListID = $value;
	}

	public function Save() {
		user_error("Not implemented", E_USER_ERROR);
	}

	protected function loadFullDetails() {
		$interface = new CS_REST_Lists($this->ID, $this->apiKey);
		$result = $interface->get();
		$response = $this->parseResult($result);
		$this->populateFrom($response);
	}
}