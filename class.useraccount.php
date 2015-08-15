<?php
/**
 * @author		Arman Ortega <arman.ortega@yahoo.com> 
 * @version   v1.0
 * @created		Dec 30 2006
 */
 
class UserAccount extends database
{
	var $userID = 0;
	var $usertypeID ;
	var $username;
	var $password;
	var $fullname;
	var $gender;
	var $occupation;
	var $address;
	var $country;
	var $contactno;
	var $birthdate;
	var $registerdate;
	var $lastvisitdate;
	var $table_useraccount = "useraccount";
	var $table_usertype = "usertypes";
	
	function UserAccount() 
	{
		$this->userID = 0;
	}
	
	function &getInstance( ) 
	{
		static $instance ;
		if ( ! isset( $instance ) ) {
			$instance = new UserAccount;
		}
		return $instance ;
	}
	
	function setUserID( $uid )
	{
		$this->userID = $uid ;	
	}
	function getUserID ( ) {
		return $this->userID;	
	}
	
	function setUserName( $uname )
	{
		$this->username = $uname ;
	}
	
	function setUserPassword( $password )
	{
		$this->password = $password ; 
	}
	
	function setUserFullName( $fullname )
	{
		$this->fullname = $fullname ;
	}
	
	function getUserFullName()
	{
		return $this->fullname ;	
	}
	
	function checkUserAccount( $username , $password )
	{
		$sql = " select * from $this->table_useraccount where ";
		$sql .= " username = " . $this->quote( $this->real_escape_str( $username ) );
		$this->query( $sql ); 
		$account = array();
		while( $row = $this->fetchobject() ) $account[] = $row ;
		if ( $this->getnumrows() == 1 )
		{
			if ( $password == $account[0]->password )	return $account ;
			else return false ;
		}
		else
		{
			return false ;
		}
	}
	
	function getUserAccountById( $id )
	{
		$sql = " select * from $this->table_useraccount ";
		$sql .= " where userID = " . intval( $id );
		//echo "s=$sql";
		$this->query( $sql );
		$data = array();
		while( $data[] = $this->fetchobject() );
		$this->freeresult();
		return $data;
	}
	
	function getAllUserAccount ( $offset = 0 , $limit = 0 ) {
		$sql = " select * from $this->table_useraccount order by registerdate asc ";
		if ( $offset != 0 || $limit != 0 ) $sql .= " limit $offset , $limit ";
		$data = array();
		//echo "s=$sql";
		$this->query( $sql );
		while( $data[] = $this->fetchobject() );
		$this->freeresult();
		return $data;
	}
	
	function getUserAccountData( $userID = 0 )
	{
		$sql = " select * from $this->table_useraccount where 1=1 ";
		if ( $userID != 0 )	$sql .= " and userID = " . intval( $userID );
		$sql .= " order by registerdate desc ";
		$this->query( $sql );
		$data = array();
		while( $row = $this->fetchobject() ) $data[] = $row ;
		// return $this->formatAccountResultSet( $data );
		return  $data;
	}

	function getUserAccountSelectedData( $fields , $wheredata )
	{
		if ( count( $fields ) > 0 )
		{
			$thisfields = implode( ', ' , $fields );
		}		
		else
		{
			$thisfields = $fields ;
		}
		$sql = " select $thisfields from $this->table_useraccount ";
		$sql .= " where " . $wheredata ;
		//echo "s=$sql";
		$this->query( $sql );
		while( $data[] = $this->fetchobject() );
		$this->freeresult();
		return $data;
	}
	
	function formatAccountResultSet( $result )
	{
		$accountdata = array();
		if ( $result != null )
		{
			$n = count( $result );
			for( $i=0 ; $i < $n ; $i++ )
			{
				$account = new UserAccount();
				$account->setUserID( $result[$i]->userID );
				$account->setUserName( $result[$i]->username );
				$account->setUserFullName(  $result[$i]->fullname ); 
				$accountdata[$i] = $account ; 
			}
			return $accountdata ;
		}
		else
			return false ;
	}
	
	function saveData( $postdata = array() ) 
	{
		$fields = array(
			"username" => "string" ,
			"usertypeID" => "integer" ,
			"email" => "string" ,
			"password" => "string" ,
			"fullname" => "string" , 
			"gender" => "string" , 
			"position" => "string" , 
			"activationcode" => "string" ,
			"status" => "string" ,	
			"address" => "string" ,
			"country" => "string" ,
			"contactno" => "string" , 
			"birthdate" => "integer" ,
			"affiliation" => "string" ,
			"registerdate" => "integer",   
			"lastvisitdate" => "integer"
		);	

		$fieldlist = implode( " , " , array_keys( $fields ) );
		$valuelist = array();
		$updatelist = array();
		
		$myts =& textSanitizer::getInstance();		
		
		while( list( $fieldname , $fieldtype ) = each( $fields ) )
		{
			if ( !strcmp( $fieldtype , "string" ) ) 
			{
				if ( !strcmp( $fieldname , "password" ) && !empty( $postdata[$fieldname]) )
				{
					// perform double hashing ...
					$postdata[$fieldname] = ( ( $postdata[$fieldname] ) );	
				}
				
				if ( !strcmp( $fieldname , "activationcode" )  )			
				{
					$postdata[$fieldname] = md5( $postdata['password'] ) ;	// use password			
				}
				
				$valuelist[] = $this->quote(  $myts->htmlspecialchars( $postdata[$fieldname] )); 
			}
			else
			{
				$valuelist[] = ( int ) $postdata[$fieldname];
			}
		}
		
		$insertvalues = implode( " , " , $valuelist );
		
		if ( $this->userID == 0 )
		{
			$sql = " insert into $this->table_useraccount ( $fieldlist ) ";
			$sql .= " values ( $insertvalues ) ";
		}
                //  echo "s=$sql";
		$result = $this->query( $sql )  or die (mysql_error());
		if ( !$result ) return false;
		return true;
	}
	

	function updateUserAccountData( $postdata = array() , $whereparams ) {
		$fields = array(
			"username" => "string" ,
			"usertypeID" => "integer" ,
			"email" => "string" ,
			"password" => "string" ,
			"fullname" => "string" , 
			"gender" => "string" , 
			"position" => "string" , 
			"activationcode" => "string" ,
			"status" => "string" ,	
			"address" => "string" ,
			"country" => "string" ,
			"contactno" => "string" , 
			"birthdate" => "string" ,
			"registerdate" => "integer",   
			"lastvisitdate" => "integer"
		);	
		// print_r($postdata);
		$myts =& textSanitizer::getInstance();
		
		foreach( $postdata as $key => $value ) {
			if ( in_array( $key , array_keys( $fields ) ) ) 
			{
				$fieldtype = $fields[$key]; 
				if ( !strcmp( $fieldtype , "string" )  ) 
				{	
						if ( !strcmp( $key , "password" ) && $postdata[$key] != '' )
						{
							// perform double hashing later...
							$postdata[$key] = $this->quote ( $postdata[$key] );	
						}
						elseif( $postdata[$key] != '' )
						{
							$postdata[$key] = $this->quote( $myts->htmlspecialchars( $postdata[$key] ) );						
						}
				}
				// only update the necessary fields
				// fields that dnt have a value will be disregarded 
				// ,, so no change were made..
				if ( $postdata[$key] != '' )
					$updatelist[] = $key . '=' . $postdata[$key];
			}
		}
		$updatevalues = implode( " , " , $updatelist );		
		
		$sql = " update $this->table_useraccount " ;
		$sql .= " set $updatevalues " ;
		$sql .= " where $whereparams " ;
		// echo "s=$sql";
		$result = $this->query( $sql );
		
		if ( !$result ) return false;
		return true;
	}
	
	function updateData( &$db , $postdata = array() )
	{
		$fields = array(
			"username" => "string" ,
			"usertypeID" => "integer" ,
			"password" => "string" ,
			"fullname" => "string" , 
			"gender" => "integer" , 
			"occupation" => "string" , 
			"timestamp" => "integer" ,
			"status" => "string" ,	
			"address" => "string" ,
			"country" => "string" ,
			"contactno" => "string" , 
			"birthdate" => "integer" ,
			"lastvisitdate" => "integer"
		);	
	
		$fieldlist = implode( " , " , array_keys( $fields ) );
		$valuelist = array();
		
		while( list( $fieldname , $fieldtype ) = each( $fields ) )
		{
			if ( !strcmp( $fieldtype , "string" ) ) 
			{
				if ( !strcmp( $fieldname , "password" ) && !empty( $postdata[$fieldname]) )
				{
					// perform double hashing ...
					$postdata[$fieldname] = ( ( $postdata[$fieldname] ) );	
				}
                                elseif ( $postdata[$fieldname] != ''  )
                                { 
				$postdata[$fieldname] = $this->quote(addslashes( $postdata[$fieldname] ));
                                 } 
			}
			$updatelist[] = $fieldname . '=' . $postdata[$fieldname];
		}
		
		$updatevalues = implode( " , " , $updatelist );		
		
		$sql = " update $this->tablename ";
		$sql .= " set $updatevalues ";

		$result = $this->query( $sql );
		if ( !$result ) return false;
		return true;
	}
	
	function getGroupname( $guid ) {
		$sql = " select * from $this->table_usertype ";	
		$sql .= " where usertypeID = $guid " ;
		$result = $this->query( $sql );
		$data = array();
		while( $row = $this->fetchobject() ) $data[] = $row ;
		$this->freeresult();
		return $data;
	}
	
	function isUsernameExist( $uname ) {
		$sql = " select username from $this->table_useraccount ";
		$sql .= " where username = '$uname' ";
		$this->query( $sql );
		if ( $this->getnumrows() > 0 )
			return true;
		else	
			return false;
	}

	function isEmailExist( $email ) {
		$sql = " select email from $this->table_useraccount ";
		$sql .= " where email = " . $this->quote( strval( $email ) );
		// echo "s=$sql";		
		$this->query( $sql );
		if ( $this->getnumrows() > 0 )
			return true;
		else	
			return false;
	}
	
	function getLastVisitDate( $uid = 0 ) {
		if ( ! $uid  ) $uid = $this->userID;
		$sql = " select lastvisitdate from $this->table_useraccount  ";
		$sql .= " where userID = " . intval( $uid );
		$this->query( $sql );
		$this->fetchobject( );
		return $this->row->lastvisitdate;
	}
	
	function getUserActivationCode ( $uid = 0 ) {
		if ( ! $uid  ) $uid = $this->userID;
		$sql = " select activationcode from $this->table_useraccount ";	
		$sql .= " where userID = " . intval( $uid ) ;
		$this->query( $sql );
		$this->fetchobject( );
		return $this->row->activationcode ;
	}
	
}
?>