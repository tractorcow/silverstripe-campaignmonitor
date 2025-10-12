<?php

namespace Tractorcow\CampaignMonitor;

/**
 * Base class for Campaign Monitor data objects
 *
 * @property string $ID The Identifier for this object within Campaign monitor
 * @property string $Title The descriptive title for this object
 * @author Damian Mooyman
 */
abstract class CMObject extends CMBase
{

    /**
     * Stored data for this object. May contain nested data
     *
     * @var array
     */
    protected $record = [];

    /**
     * Serialises the data into a format suitable to be sent via the CM api.
     *
     * @return array Data
     */
    public function serializeData()
    {
        return $this->record;
    }

    /**
     * @param string $apiKey
     * @param mixed $data
     */
    public function __construct($apiKey = null, $data = null)
    {
        parent::__construct($apiKey);

        $this->populateFrom($data);
    }

    /**
     * Parses a stdObject into a nested associative array recursively
     *
     * @param object|array $data Either an object or array with field values
     * @return mixed The parsed data
     */
    protected function convertToArray($data)
    {
        // Base case
        if (empty($data)) {
            return null;
        }

        // Prepare object for conversion
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        // Recursively convert array
        if (is_array($data)) {
            return array_map([$this, 'convertToArray'], $data);
        }

        return $data;
    }

    /**
     * Populates the object from the given data
     *
     * @param array $data Either an object or array with field values
     */
    protected function populateFrom($data)
    {
        if (!is_array($this->record)) {
            $error = 'Bad data received to set record info.';
            $this->logger->error($error . 'Expected an associative array, found: ' . get_type($data));
            throw new CMError($error, 500);
        }
        $this->record = $data;
    }

    /**
     * Determine if this is a new object, or one that exists in the database
     *
     * @return bool
     */
    public function isNew()
    {
        return empty($this->ID);
    }

    public function hasField($field)
    {
        return ($this->record && array_key_exists($field, $this->record))
            || $this->hasMethod("get{$field}");
    }

    public function getField($field)
    {
        return $this->record[$field] ?? parent::getField($field);
    }

    public function setField($field, $value)
    {
        $this->record[$field] = $value;
    }

    /**
     * Saves the object to the database
     */
    abstract public function Save();
}
