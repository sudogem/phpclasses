<?php
/**

 * @author		Arman Ortega <arman.ortega@yahoo.com> 
 * @version   v1.0
 * @created		Dec 30 2006
 */

class Sessions
{
	function Sessions ()
	{
		// supress errors 
		@session_start();
		if ( function_exists( 'session_regeneration_id' ) )
		{
			@session_regeneration_id();		
		}
	}

	function getAttribute( $key )
	{
		if ( isset( $_SESSION[$key] ) == true )
		{
			return unserialize( $_SESSION[$key] );
		}
		else
		{
			return NULL;
		}
	}

	function isAttributeExist( $key )
	{
		if ( isset( $_SESSION[$key] ) == true )
		{
			return true ;
		}
		else
		{
			return false ;
		}
	}

	function setAttribute( $key , $val )
	{
		$_SESSION[$key] = serialize( $val );
	}

	function removeAttribute( $key )
	{
		unset( $_SESSION[$key] ) ;
	}

	function writeSession()
	{
		return session_write_close();
	}

	function destroySession()
	{
		return session_destroy() ;
	}
}

