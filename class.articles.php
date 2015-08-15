<?php
/**
 * @author		Arman Ortega <arman.ortega@yahoo.com> 
 * @version   v1.0
 * @created		Dec 30 2006
 */

class NewsArticles extends database  
{
	var $table_articles = "news" ;
	
 	var $newsID;
 	var $article;
 	var $newstitle;
	var $body ; 	
 	var $datecreated ;
 	var $submittedby ;
 	var $lastmodifieddate ;
 	var $categoryID ;
	

	function NewsArticles()
	{
		// do nothing . . .
	}
	

	function setnewsID( $newsID )
	{
		$this->newsID = $newsID ;
	}
	
	function setArticleNewstitle( $newstitle )
	{
		$this->newstitle = $newstitle ;
	}
	
	function setArticleBody( $body )
	{
		$this->body = $body ;
	}
		
	function setArticleCategoryID( $categoryID )
	{
		$this->categoryID = $categoryID ;
	}
	
	function setArticleSubmittedBy( $author )
	{
		$this->submittedby = $author ;
	}
	
	function setArticleDateCreated( $datecreated )
	{
		$this->datecreated = $datecreated ;
	}
	
	function setArticleStatus ( $statusid )
	{
		$this->status = $statusid ;
	}
	
	function getNewsID()
	{
		return $this->newsID ;
	}
	
	function getArticleNewstitle()
	{
		return $this->newstitle ;	
	}

	function getArticleBody()
	{
		return $this->body ;	
	}
	
	function getArticleSubmittedBy()
	{
		return $this->submittedby ;
	}
	
	function getArticleDateCreated()
	{
		return $this->datecreated ; 
	}
	
	function getArticleStatus (  )
	{
		return $this->status ;
	}
	
	
	function &getInstance()
	{
		static $instance ;
		if ( !isset( $instance ) )
		{
			$instance = new newsarticles();
		}
		return $instance ;
	}
	
	function getAllNewsAndEvents( )
	{
		$sql = " select * from $this->table_articles ";
		$sql .= " where isarchive = 0 ";
		$sql .= " order by datecreated desc ";
		$articledata = array();
		$articledata  = $this->buffered_query( $sql );		
		if ( $this->getnumrows() > 0 )
			return $this->formatArticleResultSet( $articledata );
		else return null;		
	}
	
	function getAllNewsAndEventsByLimit( $offset = 0 , $limit = 0  ) {
		$sql = " select * from $this->table_articles where 1=1 ";
		$sql .= " and isarchive = 0 ";
		$sql .= " order by datecreated desc  ";		
		if ( $offset != 0 || $limit != 0 ) $sql .= " limit $offset , $limit ";
		$data = array();
		//echo "s=$sql";
		$this->query( $sql );
		while( $data[] = $this->fetchobject() );
		return $data;
	}
	
	function getAllPublishedArticles( $num = 0 )
	{
		$sql = " select * from $this->table_articles ";
		$sql .= " where status = '2' ";
		$sql .= " order by datecreated desc ";
		if ( $num != 0 ) $sql .= " limit $num ";
		$result = $this->query( $sql );
		$articledata = array();
		while( $row = $this->fetchobject() ) $articledata[] = $row; 
		if ( $this->getnumrows() > 0 )
			return $this->formatArticleResultSet( $articledata );
		else return null;		
	}
	
	function getAllArticlesWrittenByAuthor( $authorid )
	{
		$sql = " select * from $this->table_articles ";
		$sql .= " where submittedby = " . intval( $authorid );
		$sql .= " and isarchive = 0 ";
		$sql .= " order by datecreated desc ";
		$result = $this->query( $sql );
		$articledata = array();
		while( $row = $this->fetchobject() ) $articledata[] = $row; 
		if ( $this->getnumrows() > 0 )
			return $this->formatArticleResultSet( $articledata );
		else return null;
	}
	
	function getAllRemovedArticlesWrittenByAuthor( $authorid )
	{
		$sql = " select * from $this->table_articles ";
		$sql .= " where submittedby = " . intval( $authorid );
		$sql .= " and isarchive = 1 ";
		$result = $this->query( $sql );
		$articledata = array();
		while( $row = $this->fetchobject() )$articledata[] = $row;
		if ( $this->getnumrows() > 0 )
			return $this->formatArticleResultSet( $articledata );
		else return null;
	}
	
	function getArticlesByLimit( $limit , $offset )
	{
		$sql = " select * from $this->table_articles ";
		$sql .= " order by datecreated desc limit $limit , $offset ";
		$result = $this->query( $sql );
		$articledata = array( );
		while( $row = $this->fetchobject() ) $articledata[] = $row ;
		return $articledata ; 
	}
	
	function getArticleByID( $newsID )
	{
		$sql = " select * from $this->table_articles ";
		$sql .= " where newsID = " . intval( $newsID );
		$result = $this->query( $sql );
		$articledata = array();
		while( $row = $this->fetchobject() ) $articledata[] = $row ;
		return $this->formatArticleResultSet( $articledata );
	}
	
	function formatArticleResultSet( $result )
	{
		$articledata = array();
		if ( $result != null )
		{
			$n = count( $result );
			for( $i=0 ; $i < $n ; $i++ )
			{
				$article = new NewsArticles();
				$article->setnewsID( $result[$i]->newsID );
				$article->setArticleNewstitle( $result[$i]->newstitle );
				$article->setArticleBody(  $result[$i]->newsdesc ); 
				$article->setArticleDateCreated( $result[$i]->datecreated );
				$article->setArticleStatus( $result[$i]->status );
				$articledata[$i] = $article ; 
			}
			return $articledata ;
		}
		else
			return false ;
	}
	
	function addArticle( $postdata = array() )
	{
		$fields = array (
			'newstitle' => 'string' , 
			'newsdesc' =>  'string' ,
			'status' => 'integer' ,
			'datecreated' => 'integer' ,
		);
		
		$sanitizer = textsanitizer::getInstance( );
		
		$fieldlist = implode( " , " , array_keys( $fields ) );
		while( list( $fieldname , $fieldtype ) = each( $fields ) )
		{
			if ( !strcmp( $fieldtype , "string" ) ) 
			{
				$valuelist[] = $this->quote( $sanitizer->htmlspecialchars( $postdata[$fieldname] )); 					
			}
			else
			{
				$valuelist[] = ( int ) $postdata[$fieldname];
			}
		}
		$insertvalues = implode( " , " , $valuelist );	
		$sql = " insert into $this->table_articles ( $fieldlist ) " ;			
		$sql .= " values( $insertvalues ) ";
		// echo "s=$sql";
		$result = $this->query( $sql );
		return $result ;
	}
	
	function editArticle( $postdata = array() , $id ) 
	{
		$fields = array (
			'newstitle' => 'string' , 
			'newsdesc' =>  'string' ,
			'datecreated' => 'string' ,
			'isarchive' => 'string' , 
			'submittedby' => 'integer' ,
			'lastmodifiedDate' => 'integer' ,
			'status' => 'integer'
		);
		
		$sanitizer =& textsanitizer::getInstance( );
		
		$fieldlist = implode( " ," , array_keys( $fields ) );
		foreach( $postdata as $key => $value ) {
			if ( in_array( $key , array_keys( $fields ) ) ) {
				$fieldtype = $fields[$key]; 
				if ( !strcmp( $fieldtype , "string" )  ) 
				{
					$value = $this->quote( $sanitizer->htmlspecialchars( $postdata[$key] ) );
					$updatelist[] = $key . '=' . $value ;				
				}
				
				if ( !strcmp( $fieldtype , "integer" )  ) 
				{
					$value = ( int ) $postdata[$key];	
					$updatelist[] = $key . '=' . $value ; 			
				}
			}
		}
		$updatevalues = implode( " , " , $updatelist );		
		$sql = " update $this->table_articles "; 
		$sql .= " set $updatevalues ";
		$sql .= " where newsID = " . intval( $id );
		// echo $sql ;
		$result = $this->query( $sql );
		return $result ; 
	}
	
	function removeArticle( $newsID = 0 )
	{
		if ( ! $newsID ) $newsID = $this->newsID;
		$sql = " update $this->table_articles ";
		$sql .= " set isarchive = 1 ";
		$sql .= " where newsID = " . intval ( $newsID );
		$result = $this->query( $sql );
		return $result ;
	}

	function restoreArticle( $newsID = 0 )
	{
		if ( ! $newsID ) $newsID = $this->newsID;
		$sql = " update $this->table_articles ";
		$sql .= " set isarchive = 0 ";
		$sql .= " where newsID = " . intval ( $newsID );
		$result = $this->query( $sql );
		return $result ;
	}
	
	
}

?>