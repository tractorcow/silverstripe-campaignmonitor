<?php

/**
 * Represents a subscriber within the Campaign Monitor database
 *
 * @property array $CustomFields Custom fields for this subscriber
 * @property CMList $List The list this subscriber is assigned to
 * @author Damian Mooyman
 */
class CMSubscriber extends LazyLoadedCMObject {
	
	/**
	 * The list this subscriber belongs to
	 * 
	 * @var CMList
	 */
	protected $list = null;
	
	/**
	 * Original email address for the subscriber, only to be changed each update
	 * 
	 * @var string
	 */
	protected $originalEmail = null;
	
	/**
	 * Custom fields for this subscriber
	 *
	 * @var array
	 */
	protected $customFields = array();

	/**
	 * Gets the list of all custom fields
	 * 
	 * @return array
	 */
	public function getCustomFields() {
		return $this->customFields;
	}
	
	/**
	 * Replaces the list of custom fields with another
	 * 
	 * @param mixed $value Either an array or a stdObject
	 */
	public function setCustomFields($value) {
		$this->customFields = $this->convertToArray($value);
	}
	
	/**
	 * Retrieves the value for a specified custom field
	 * 
	 * @param string $field The field name
	 * @return mixed The field value
	 */
	public function getCustomField($field) {
		if(isset($this->customFields['field'])) {
			return $this->customFields['field'];
		}
	}
	
	/**
	 * Sets the value for a specified custom field
	 * 
	 * @param string $field The field name
	 * @param mixed $value The field value
	 */
	public function setCustomField($field, $value) {
		$this->customFields['field'] = $value;
	}
	
	/**
	 * Create a new subscriber record
	 * 
	 * @param string $apiKey
	 * @param mixed $data
	 * @param CMList $list 
	 */
	function __construct($apiKey = null, $data = null, $list = null) {
		parent::__construct($apiKey, $data);
		
		$this->setList($list);
	}
	
	function populateFrom($data) {
		
		$data = $this->convertToArray($data);
		
		if(isset($data['CustomFields']))
		{
			$this->setCustomFields($data['CustomFields']);
			unset($data['CustomFields']);
		}
		
		parent::populateFrom($data);
	}
	
	function serializeData() {
		$data = parent::serializeData();
		$customFields = array();
		foreach($this->customFields as $key => $value) {
			// Treat null or empty options as clearing the field
			if($value === null || $value === array()) {
				$customFields[] = array(
					'Key' => $key,
					'Value' => '',
					'Clear' => true
				);
			} elseif(is_array($value)) {
				// for multi-select values duplicate each key for set values
				foreach($value as $nextValue) {
					$customFields[] = array(
						'Key' => $key,
						'Value' => $nextValue
					);
				}
			} else {
				// Simple field assignment
				$customFields[] = array(
					'Key' => $key,
					'Value' => $value
				);
			}
		}
		$data['CustomFields'] = $customFields;
		return $data;
	}

	/**
	 * Retrieves the list this subscriber belongs to
	 * 
	 * @return CMList
	 */
	function getList() {
		return $this->list;
	}
	
	/**
	 * Sets the list this subscriber belongs to without saving it
	 * 
	 * @param CMList $list Thelist to assign
	 */
	function setList($list) {
		$this->list = $list;
		
		// For new records the api key can be inherited from the list
		if(empty($this->apiKey)) $this->apiKey = $list->apiKey;
	}

	/**
	 * Prepares a CM REST interface object for loading and saving data for this record
	 * 
	 * @return CS_REST_Subscribers
	 * @throws CMError 
	 */
	protected function buildRestInterface() {
		$list = $this->getList();
		if(empty($list) || empty($list->ID) || empty($this->apiKey)) {
			throw new CMError("Could not build interface for CMSubscriber without a list ID and apiKey");
		}
		return new CS_REST_Subscribers($list->ID, $this->apiKey);
	}
	
	function isNew() {
		return empty($this->originalEmail);
	}

	// Although the email address can be changed, the original email address is
	// considered the identifier of this record
	public function getID() {
		if(!empty($this->originalEmail)) {
			return $this->originalEmail;
		}
		return $this->EmailAddress;
	}

	public function setID($value) {
		if(!empty($this->originalEmail)) {
			$this->originalEmail = $value;
		} else {
			$this->EmailAddress = $value;
		}
	}

	public function getTitle() {
		return $this->Name;
	}

	public function setTitle($value) {
		$this->Name = $value;
	}

	protected function loadFullDetails() {
		$interface = $this->buildRestInterface();
		
		// Determine identifier by which we retrieve this record
		$result = $interface->get($this->ID);
		$response = $this->parseResult($result);
		
		
		Debug::dump($response);
		user_error("Not implemented", E_USER_ERROR);
		
		$this->originalEmail = $response->EmailAddress;
	}

	public function Save() {
		$interface = $this->buildRestInterface();
		$data = $this->serializeData();
		if($this->isNew()) {
			$result = $interface->add($data);
		} else {
			$result = $interface->update($this->originalEmail, $data);
		}
		// Check result, and reset ID after successful save
		$this->parseResult($result);
		$this->originalEmail = $this->EmailAddress;
	}
	
	/**
	 * Loads a subscriber details from a list by email address
	 * 
	 * @param string $email The email address to identify this subscriber by
	 * @param CMList $list The list to search within
	 */
	public function LoadByEmailAndList($email, $list) {
		$this->setList($list);
		$this->LoadByID($email);
	}
}