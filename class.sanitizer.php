<?php
/**
 * @author		Arman Ortega <arman.ortega@yahoo.com> 
 * @version   v1.0
 * @created		Dec 30 2006
 */

class textSanitizer {
	var $instance;
	var $text ;
	
	function textsanitizer ( ) 
	{
		// so far nothing here ..
	} 
	
	function &getInstance ( ) 
	{
		static $instance;
		if ( !isset( $instance ) ) {
			$instance = new textSanitizer();
		}
		return $instance;
	}
	
	function &addSlashesGPC( $text ) 
	{
		if ( !get_magic_quotes_gpc() ) {
			$text =& addslashes( $text );
		}
		return $text;
	}
	
	function &stripSlashesGPC( $text ) 
	{
		if ( get_magic_quotes_gpc() ) {
			$text =& stripslashes( $text );
		}
		return $text;
	}
	
	function &htmlspecialChars( $text ) 
	{
		$text =& htmlspecialchars( $text );
		return $text;
	}
	
	function make_friendly_str( $text )
	{
		if ( $text != '' || $text != NULL )
		{
			$text = ereg_replace( "[^A-Za-z0-9_\-\./,]" , "" , $text );
			$text = str_replace( array( "." , "-" , "/" , "," , "'" ) , " " , $text );
			$text = trim( $text );
			$text = ereg_replace( " {1,}" , "_" , $text ); 
			$text = ereg_replace( "_{2,}" , "_" , $text );
			return $text ;
		}
		return false;
	}
	
	function htmlentities( $text )
	{
		return htmlentities( $text );
	}
}

?>