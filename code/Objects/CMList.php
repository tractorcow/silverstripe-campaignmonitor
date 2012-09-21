<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CMClient
 *
 * @author Damo
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