<?php

/**
 * Description of CMObject
 *
 * @property string $ID
 * @property string $Title
 * @author Damo
 */
abstract class CMObject extends CMBase {
	
	/**
	 * @var array
	 */
	protected $record = array();
	
	/**
	 * Serialises the data into a format suitable to be sent via the CM api. 
	 */
	public function serializeData() {
		return $this->record;
	}

	/**
	 * @param string $apiKey
	 * @param mixed $data 
	 */
	function __construct($apiKey = null, $data = null) {
		parent::__construct($apiKey);

		$this->populateFrom($data);
	}

	/**
	 * Parses a stdObject into a nested array
	 * @param type $data
	 * @return null 
	 */
	protected function convertToArray($data) {
		// Base case
		if (empty($data)) return null;
		
		// Prepare object for conversion
		if(is_object($data)) {
			$data = get_object_vars ($data);
		}
		
		// Recursively convert array
		if (is_array($data)) {
			return array_map(array($this, 'convertToArray'), $data);
		}
		
		return $data;
	}

	/**
	 * Populates the object from the given data
	 * @param mixed $data Either an object or array with field values
	 */
	protected function populateFrom($data) {
		$this->record = $this->convertToArray($data);
		if(empty($this->record)) $this->record = array();
	}

	/**
	 * Determine if this is a new object, or one that exists in the database
	 * @return type 
	 */
	public function isNew() {
		return empty($this->ID);
	}

	public function hasField($field) {
		return	array_key_exists($field, $this->record) ||
				$this->hasMethod("get{$field}");
	}

	/**
	 * Gets the value of a field.
	 * Called by {@link __get()} and any getFieldName() methods you might create.
	 *
	 * @param string $field The name of the field
	 *
	 * @return mixed The field value
	 */
	public function getField($field) {
		if (isset($this->record[$field]))
			return $this->record[$field];

		return parent::getField($field);
	}

	public function setField($field, $value) {
		$this->record[$field] = $value;
	}

	/**
	 * Saves the object to the database 
	 */
	abstract public function Save();
}