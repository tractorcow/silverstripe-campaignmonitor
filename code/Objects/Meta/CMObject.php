<?php

/**
 * Description of CMObject
 *
 * @property string $ID
 * @property string $Title
 * @author Damo
 */
abstract class CMObject extends CMBase {
	
	protected $record = null;
	
	abstract function getID();
	abstract function setID($value);
	abstract function getTitle();
	abstract function setTitle($value);
	
	/**
	 * @param string $apiKey
	 * @param mixed $data 
	 */
	function __construct($apiKey, $data) {
		parent::__construct($apiKey);
		
		$this->record = $data;
	}
	
	public function hasField($field)
	{	
		if($field == 'ID' || $field == 'Title') return true;
		
		if(parent::hasField($field)) return true;
		
		return isset($this->record->$field);
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
		return isset($this->record->$field) 
			? $this->record->$field 
			: parent::getField($field);
	}
	
	public function setField($field, $value) {
		$this->record->$field = $value;
	}
	
}