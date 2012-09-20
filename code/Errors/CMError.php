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
	
	/**
	 * 
	 * @param string $message Error message
	 * @param integer $code Error code
	 * @param Exception $previous related exception
	 */
	function __construct($message, $code, $previous = null) {
		parent::__construct($message, $code, $previous);
	}
	
}