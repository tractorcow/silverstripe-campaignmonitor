<?php

/**
 * Description of CMResources
 *
 * @author Damo
 */
class CMResources extends CMBase {
	
	/**
	 * @var CS_REST_General
	 */
	protected $restInterface = null;

	function __construct($apiKey) {
		parent::__construct($apiKey);
		$this->restInterface = new CS_REST_General($this->apiKey);
	}

	/**
	 * @return DataObjectSet[CMClient]
	 * @throws CMError 
	 */
	function Clients() {
		$result = $this->restInterface->get_clients();
		$response = $this->parseResult($result);
		
		// Save each client
		$clients = new DataObjectSet();
		foreach($response as $clientData) {
			$clients->push(new CMClient($this->apiKey, $clientData));
		}
		return $clients;
	}
	
	/**
	 * @return CMClient
	 * @param type $clientID 
	 */
	function getClient($clientID) {
		$interface = new CS_REST_Clients($clientID, $this->apiKey);
		$result = $interface->get();
		$response = $this->parseResult($result);
		
		// Save client
		return new CMClient($this->apiKey, $response, $interface);
	}
	
	function getList($listID) {
		$interface = new CS_REST_Lists($listID, $this->apiKey);
		$result = $interface->get();
		$response = $this->parseResult($result);
		
		// Save client
		return new CMList($this->apiKey, $response, $interface);
	}

}