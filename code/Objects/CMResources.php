<?php

/**
 * Description of CMResources
 *
 * @author Damo
 */
class CMResources extends CMBase {
	
	/**
	 * @return ArrayList[CMClient]
	 * @throws CMError 
	 */
	function Clients() {
		$interface = new CS_REST_General($this->apiKey);
		$result = $interface->get_clients();
		$response = $this->parseResult($result);
		
		// Save each client
		$clients = new ArrayList();
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
		$client = new CMClient($this->apiKey);
		$client->LoadByID($clientID);
		return $client;
	}
	
	function getList($listID) {
		$list = new CMList($this->apiKey);
		$list->LoadByID($listID);
		return $list;
	}

}