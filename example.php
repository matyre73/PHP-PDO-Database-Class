<?php
/***************************************************************************************************************
 * 											EXAMPLE SCRIPT
 * 											--------------
 **************************************************************************************************************/

	# Database Settings - Maybe Put in your config file.
	define("DB_HOSTNAME", "localhost");
	define("DB_USERNAME", "root");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "database");

	if(DB_HOSTNAME != ""){
		include("db.class.php");

		if(class_exists("db")){
			$db = new db(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);		
		}
	}

	# Run Basic Query
 	$result = $db->query("SELECT * FROM Table");
 	$data 	= $db->fetch_array($result);

 	# Run Prepare Query
	$result = $db->query_prepare("SELECT * FROM someTable WHERE something = :comparison", array(':comparison' => $comparison));
	$data 	= $db->fetch_array($result);

	# Insert Query
	$result = $db->insert("table_name", array("column_name"=>"column_value"));
	$id 	= $db->last_insert_id();

	# Update Query
	$result = $db->update('tablename',array('thisfield' => value, 'field2'=>value2),array('conditionfield'=>conValue));

	# Delete Query
	$result = $db->delete("table_name", array("column_name"=>"column_value"));
/***************************************************************************************************************
 * 											/EXAMPLE SCRIPT
 **************************************************************************************************************/
?>