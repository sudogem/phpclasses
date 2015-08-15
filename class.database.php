<?php
/**
 * @author		Arman Ortega <arman.ortega@yahoo.com> 
 * @version   v1.0
 * @created		Dec 30 2006
 */

class database
{
	var $conn; 
	var $host = '' ;
	var $username = '' ;
	var $password = '' ;
	var $databasename = '' ;
	
	/**
	 * connect to the database
	 */
	function database( $host = '' , $username = '' , $password = '' , $databasename = '' )
	{
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->databasename = $databasename;
		$this->conn = mysql_connect( $host , $username , $password );
		if ( !$this->conn ) die('Cannot connect to database. ');
		$db = mysql_select_db( $databasename , $this->conn );
		if ( !$db ) die( $this->error() );
		return $this->conn;
	}
	
	function &getInstance( ) {
		static $instance;
		if ( !isset( $instance ) ) {
			$instance = new database ( $host , $username , $password , $databasename ) ;
		}
		return $instance;
	}
	
	/**
	 * performs a sql query
	 */ 
	function query( $sql )
	{
		if ( $sql != '' )
		{
			$this->query_result = mysql_query($sql) or die(mysql_error());
			if ( !$this->query_result ) 
			{
				return false;
			}
			else 
			{
				return true;	
			}	
		} 
	}
	
	function buffered_query( $sql , $type = 'OBJECT' )
	{
		if ( $sql != '' )
		{
			$buffered_data = array( );
			if ( $type == 'OBJECT' ) 
			{
				$result = $this->query( $sql );
				while( $row = $this->fetchobject( ) ) 
				{
					$buffered_data[] = $row ;
				}
			}
			elseif ( $type == 'ARRAY' )
			{
				$result = $this->query( $sql );
				while( $row = $this->fetcharray( ) )
				{
					$buffered_data[] = $row ;
				}
			}
			return $buffered_data ;
		}
	}
	
	/**
	 * get query results in objects
	 */
	function fetchobject( $resourceid = 0 )
	{
		if ( !$resourceid ) $resourceid = $this->query_result;
		$this->row = mysql_fetch_object( $resourceid ); 
		return $this->row;
	}
	

	/**
	 * get query results in array
	 */
	function fetcharray( $resourceid = 0 )
	{
		if ( !$resourceid ) $resourceid = $this->query_result;
		$this['row'] = mysql_fetch_array( $resourceid ); 
		return $this['row'];
	}

	/**
	 * will free all memory associated with the result identifier result.
	 */
	function freeresult( $result = 0 )
	{
		if ( !$result ) $result = $this->query_result;
		mysql_free_result( $result );
	}
	
	/**
	 * get the number of recordset rows
	 */
	function getnumrows( $result = 0 ) 
	{
		if ( !$result ) $result = $this->query_result;		
		return mysql_num_rows( $result );
	}
	
	function close() {
		if ( $this->conn ) {
			@mysql_close( $this->conn );
			unset( $this->conn );
		}	
	}
	
	/**
	* Get the ID generated from the previous INSERT operation 
	*/
	function getInsertID()
	{
		return mysql_insert_id($this->conn);
	}	
	
	/**
	 * return previous mysql errors
	 */
	function error()
	{
		return mysql_error();
	}
	
	/**
	 * attach quote chars to strings
	 */
	function quote( $str )
	{	
		return "'" . $str . "'";
	}
	
	/**
	 * Escapes special characters in a string for use in a SQL statement
	 */
	function real_escape_str( $str )
	{
		if ( get_magic_quotes_gpc( ) )  $str = stripslashes( $str );
		return mysql_real_escape_string( $str ) ;
	}
	
}
?>