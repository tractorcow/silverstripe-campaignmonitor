<?php

/**
 * Error to throw when Campaign Monitor has issues
 *
 * @author Damian Mooyman
 */
class CMError extends Exception {
	
	/**
	 * The Campaign Monitor error code
	 * 
	 * @var integer
	 */
	protected $errorCode = null;
	
	/**
	 * The message to show
	 * 
	 * @var string
	 */
	protected $errorMessage = null;
	
	/**
	 * Retrieves the Campaign Monitor error code
	 * 
	 * @return 
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}
	
	/**
	 * Campaign Monitor error message
	 * 
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	/**
	 * @param string $message Error message
	 * @param integer $errorCode Error code
	 * @param Exception $previous related exception
	 */
	function __construct($message, $errorCode, $previous = null) {
		$this->errorMessage = $message;
		$this->errorCode = $errorCode;
		
		$exCode = $errorCode == 1 ? 200 : 400;
		$exMessage = "Error $errorCode: $message";
		parent::__construct($exMessage, $exCode, $previous);
	}
	
}