<?php

/**
 * Represents a Campaign Monitor data record that may have fields loaded on 
 * an as needed basis.
 *
 * @author Damian Mooyman
 */
abstract class LazyLoadedCMObject extends CMObject
{
    
    /**
     * Flag indicating whether all lazy loaded fields have been loaded
     * 
     * @var boolean
     */
    protected $hasLoadedFullDetails = false;
    
    /**
     * Lazy load full details for this client 
     * warning: Will overwrite any changed data in $record
     * 
     * @todo : Merge new data with changed data
     */
    abstract protected function loadFullDetails();
    
    /**
     * Loads full details into this object from a record with the given id
     * 
     * @param string $id The record ID
     */
    public function LoadByID($id)
    {
        $this->ID = $id;
        $this->loadFullDetails();
        $this->hasLoadedFullDetails = true;
    }

    public function hasField($field)
    {
        
        // Check if any other fields exist
        if (parent::hasField($field)) {
            return true;
        }
        
        // New records can't lazy-load
        if ($this->isNew()) {
            return false;
        }
        
        // prevent additional loading
        if ($this->hasLoadedFullDetails) {
            return false;
        }
        
        // Load details
        $this->loadFullDetails();
        $this->hasLoadedFullDetails = true;
        
        return parent::hasField($field);
    }
}
