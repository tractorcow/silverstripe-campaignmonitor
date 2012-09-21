<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CMError
 *
 * @author Damo
 */
class CMError extends HttpException {
	
	protected $errorCode = null;
	
	protected $errorMessage = null;
	
	public function getErrorCode() {
		return $this->errorCode;
	}
	
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	/**
	 * 
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