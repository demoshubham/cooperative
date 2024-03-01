<?php
	date_default_timezone_set("Asia/Kolkata");
	// $db = mysqli_connect("p:localhost", "upcod", "Upcod@123", "upcod_survey");
	$db = mysqli_connect("p:localhost", "root", "mysql", "cooperative");
	 // $db = mysqli_connect("p:localhost", "cloudsdj", "C3o6D4s1@741#", "cloudsdj_upcod");
	 // $db = mysqli_connect("p:localhost", "root", "mysql", "cloudsdj_upcod_2024_02_07");
	//  $db = mysqli_connect("p:localhost", "root", "mysql", "cloudsdj_upcod_13_01_2024");

	if(!$db){
		die("Error 1 : Contact Administrator.");
	}
	mysqli_query($db, 'SET character_set_results=utf8'); 
	mysqli_query($db, 'SET names utf8'); 
	mysqli_query($db, 'SET character_set_client=utf8'); 
	mysqli_query($db, 'SET character_set_connection=utf8'); 
	mysqli_query($db, 'SET character_set_results=utf8'); 
	mysqli_query($db, 'SET collation_connection=utf8_general_ci'); 

function execute_query($query){
	global $db;
	$result = mysqli_query($db, $query);
	if(mysqli_error($db)){
		$sql = 'insert into mysql_dump (mysql_dump, mysql_error) values ("'.$query.'", "'.mysqli_error($db).'")';
		mysqli_query($db, $sql);
	}
	return $result;
}

function insert_id($db=''){
	global $db;
	return mysqli_insert_id($db);
}

function select_data($table, $fields, $where='', $join='', $join_on='', $union='', $union_cols=''){
	
}

function update_data($table, $fields, $values, $where){
	
}

function delete_data($table, $fields, $values, $where){
	
}

?>