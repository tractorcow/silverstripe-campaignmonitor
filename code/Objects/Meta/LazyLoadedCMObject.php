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
	 * @var boolean Flag indicating whether all lazy loaded fields have been loaded
	 */
	protected $hasLoadedFullDetails = false;
	
	/**
	 * Lazy load full details for this client 
	 * warning: Will overwrite any changed data in $record
	 * @todo : Merge new data with changed data
	 */
	abstract protected function loadFullDetails();
	
	public function LoadByID($id) {
		$this->ID = $id;
		$this->loadFullDetails();
		$this->hasLoadedFullDetails = true;
	}

	public function hasField($field) {
		
		// Check if any other fields exist
		if(parent::hasField($field)) return true;
		
		// New records can't lazy-load
		if($this->isNew()) return false;
		
		// prevent additional loading
		if($this->hasLoadedFullDetails) return false;
		
		// Load details
		$this->loadFullDetails();
		$this->hasLoadedFullDetails = true;
		
		return parent::hasField($field);
	}
}