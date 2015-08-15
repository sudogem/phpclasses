<?php
/**
 * @author		Arman Ortega <brainwired@gmail.com> 
 * @copyright	Primary offshore solutions
 * @version   v1.0
 * @date		  Oct 26 2006
 */
class Validator  {
	var $errors = array();
	var $allowBlanks = false;
	
	// Validate email address  
	function validateEmail($email, $description =''){
	    if ($this->allowBlanks && $email == ''){
			return true;
		}
		$result = eregi("^[a-z0-9]+[a-z0-9_-]*(\.[a-z0-9_-]+)*@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.([a-z]+){2,}$", $email); 
		if ($result) {
		   return true;	
		}  								
	    else {
		   return false;
		}
	}
	// Validate text only, with spaces
	function validateTextOnlyWithSpaces($str){
		if ($this->allowBlanks && $str == ''){ 
			return true; 
		}
		$result = ereg("^[A-Za-z0-9 ]+$",$str);
		if ($result) {
		   return true;	
		}  								
	    else {
		   return false;
		}
	}

	function validateAlphaNumeric($str){
		if ($this->allowBlanks && $str == ''){ 
			return false; 
		}
		$result = ereg("^[A-Za-z0-9_]+$",$str);
		if ($result) {
		   return true;	
		}  								
	    else {
		   return false;
		}
	}

	function is_unsign_number($number)
	{
		if(!preg_match("/^\+?[0-9]+$/",$number))
			return false;
		return true;
	}
	
	function is_alpha_numeric($str)
	{
		if(!preg_match("/^[A-Za-z0-9_]+$/",$str))
			return false;
		return true;
	}
	
	function validateFieldIsEmpty( $fields , $thisfields = array() ){
		if ( count($thisfields) > 0 ) {
			foreach( $fields as $k => $data ) {
				if ( in_array( $k , array_keys($thisfields) ) ) {
					if ( empty( $data ) ) 
					{
						$this->setErrors( $thisfields[$k] );
					}	
				}
			}
		}
		else {
			while( list( $field , $value ) = each( $fields ) ) {
				if ( empty( $value ) ) 
				{
					$this->setErrors( "Field $field is empty." );
				}	
			}
		
		}
		if ( count( $this->errors ) > 0 )  return false;
		else return true;
	}
	
	function validateFieldStrcmp( $field1 , $field2 ) {
		if ( strcmp( $field1 , $field2 ) ) return false;
		return true;
	}
	
	function setFieldToValidate( $fields ) {
		$this->fieldsToValidate = $fields;
	}
	
	function setErrors( $error ) {
		$this->errors[] = $error;
	}
	
	function getErrors() {
		return $this->errors;
	}
	
}
?>