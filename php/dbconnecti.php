<?php

require_once("../config/dbconfig.php");

function db_connect() {

	// Define connection as a static variable, to avoid connecting more than once 
	// static $connection;

	// Try and connect to the database, if a connection has not been established yet
	if(!isset($connection)) {
		 // Load configuration as an array. Use the actual location of your configuration file
		
		$config = new DBConfig();
		$host = $config->host;
		$username = $config->user;
		$password = $config->pass;
		$database = $config->db;
		
		$connection = mysqli_connect($host,$username,$password,$database);
	}

	// If connection was not successful, handle the error
	if($connection === false) {
		// Handle error - notify administrator, log to a file, show an error screen, etc.
		return "DB Connect Error: ".mysqli_connect_error(); 
	}
	return $connection;
}

function db_query($query) {
	// Connect to the database
	$connection = db_connect();

	// Query the database
	$result = mysqli_query($connection,$query);
	
	//If query error, show the error
	if($result === false) {
		echo "Query Error: ".mysqli_error($connection);
	}
	
	//Close connection;
	$connection->close();

	return $result;
}

function get_rows($sql)
{
	// A select query. $result will be a `mysqli_result` object if successful
	$result = db_query($sql);
	
	if($result === false) {
		return false;
	} else {
		// Check number of records
		if($result->num_rows){
			// Fetch all the rows in an array
			$rows = array();
			while ($row = mysqli_fetch_assoc($result)) {
				$rows[] = $row;
			}
			return $rows;
		}
		else{
			return false;
		}
	}
}

function get_row($sql)
{
	// A select query. $result will be a `mysqli_result` object if successful
	$result = db_query($sql);
	
	if($result === false) {
		return false;
	} else {
		// Check number of records
		if($result->num_rows){
			return mysqli_fetch_assoc($result);
		}
		else{
			return false;
		}
	}
}

function get_val($sql)
{
	// A select query. $result will be a `mysqli_result` object if successful
	$result = db_query($sql);
	
	if($result === false) {
		return false;
	} else {
		// Check number of records
		if($result->num_rows){
			$row = mysqli_fetch_assoc($result);
			foreach($row as $key=>$val)
			{
				return $val;
			}
		}
		else{
			return false;
		}
	}
}

function exe_mysql($function)
{
	$sql = "($function) as value";
	$result = db_query($sql);
	
	if($result === false) {
		return false;
	} else {
		// Check number of records
		if($result->num_rows){
			$row = mysqli_fetch_assoc($result);
			foreach($row as $key=>$val)
			{
				return $val;
			}
		}
		else{
			return false;
		}
	}
}

function run_query($sql)
{
	// A select query. $result will be a `mysqli_result` object if successful
	$result = db_query($sql);
	
	if($result === false) {
		return false;
	} else {
		return true;
	}
}

function getrs($sql)
{
	// A select query. $result will be a `mysqli_result` object if successful
	$result = db_query($sql);
	
	if($result === false) {
		return false;
	} else {
		// Check number of records
		if($result->num_rows){
			// Fetch all the rows in an array
			$rows = array();
			while ($row = mysqli_fetch_assoc($result)) {
				$rows[] = $row;
			}
			return $rows;
		}
		else{
			return false;
		}
	}
}

function setrs($sql)
{
	// A select query. $result will be a `mysqli_result` object if successful
	$result = db_query($sql);
	
	if($result === false) {
		return false;
	} else {
		return true;
	}
}
?>