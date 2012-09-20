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
	/**
	 * @var CS_REST_Lists
	 */
	protected $listInterface = null;

	/**
	 * Determine if full details for this client have been loaded
	 */
	protected function hasLoadedFullDetails() {
		return $this->record && isset($this->record->Title);
	}

	public function getTitle() {
		if ($this->hasLoadedFullDetails()) {
			return $this->record->Title;
		} elseif (isset($this->record->Name)) {
			return $this->record->Name;
		}
	}
	
	public function setTitle($value) {
		if ($this->hasLoadedFullDetails()) {
			$this->record->Title = $value;
		} elseif ($this->record) {
			$this->record->Name = $value;
		}
	}
	
	public function getID() {
		return $this->record->ListID;
	}
	
	public function setID($value) {
		$this->record->ListID = $value;
	}

	public function buildRestInterface() {
		return new CS_REST_Lists($this->ID, $this->apiKey);
	}
}