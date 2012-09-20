<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LazyLoadedCMObject
 *
 * @author Damo
 */
abstract class LazyLoadedCMObject extends CMObject {
	
	/**
	 * @var CS_REST_Wrapper_Base
	 */
	protected $restInterface = null;

	/**
	 * 
	 * @param string $apiKey
	 * @param mixed $data
	 * @param CS_REST_Wrapper_Base $restInterface 
	 */
	function __construct($apiKey, $data, $restInterface = null) {
		parent::__construct($apiKey, $data);

		$this->restInterface = $restInterface;
	}
	/**
	 * Determine if full details for this object have been loaded
	 */
	abstract protected function hasLoadedFullDetails();
	
	/**
	 * Build a new interface for the current object 
	 * @return CS_REST_Wrapper_Base
	 */
	abstract protected function buildRestInterface();
	
	/**
	 * @return CS_REST_Wrapper_Base
	 */
	protected function loadRestInterface() {
		if(!$this->restInterface) {
			$this->restInterface = $this->buildRestInterface();
		}
		return $this->restInterface;
	}
	
	/**
	 * Lazy load full details for this client 
	 */
	protected function loadFullDetails() {
		$interface = $this->loadRestInterface();
		$result = $interface->get();
		$this->record = $this->parseResult($result);
		
		// Ensure result succeeded in loading the details for this client
		if(!$this->hasLoadedFullDetails()) {
			throw new CMError('Could not load full details for object ' . $this->ID, 500);
		}
	}

	public function hasField($field) {
		// Check if any other fields exist
		if(parent::hasField($field)) return true;
		
		// If not available, check if fully loaded
		if($this->hasLoadedFullDetails()) return false;
		
		$this->loadFullDetails();
		
		return $this->hasField($field);
	}
	
}