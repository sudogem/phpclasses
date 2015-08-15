<?php
/**

 * @author		Arman Ortega <arman.ortega@yahoo.com> 
 * @version   v1.0
 * @created		Dec 30 2006

 */

class Request
{
	function Request()
	{

	}

	
	function getParameter($key)
	{
		if(isset($_REQUEST[$key]) == true)
		{
			return $_REQUEST[$key];
		}
		else
		{
			return null;
		}
	}

	function getPostParameter($key)
	{
		if(isset($_POST[$key]) == true)
		{
			return $_POST[$key];
		}
		else
		{
			return null;
		}
	}

	function getGetParameter($key)
	{
		if(isset($_GET[$key]) == true)
		{
			return $_GET[$key];
		}
		else
		{
			return null;
		}
	}
}
