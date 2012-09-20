<?php
require_once realpath(dirname(__FILE__) . '/../thirdparty/createsend/csrest_general.php');

/**
 * Description of CMBase
 *
 * @author Damo
 */
abstract class CMBase extends ViewableData {
	
	/**
	 * @var string
	 */
	protected $apiKey = null;
	
	function __construct($apiKey) {
		$this->apiKey = $apiKey;
	}
	
	/**
	 * Checks that a result is successful
	 * @param type $result
	 * @throws CMError 
	 */
	protected function checkResult($result) {
		if (!$result->was_successful()) {
			throw new CMError($result->response->Message, $result->http_status_code);
		}
		return true;
	}
	
	/**
	 * Safely extracts results from a CM API call
	 * @param type $result
	 * @return type 
	 */
	protected function parseResult($result) { 
		$this->checkResult($result);
		return $result->response;
	}

}