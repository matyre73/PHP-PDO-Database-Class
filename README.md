PHP:PDO Database Class
======================

This is a basic implementation of PDO to make database connection and querying a database easy.

**AUTHOR :** Wade Dunbar <br>
**VERSION:** 1.0

### Create Instance: *Copy the below code.*

````php
	
	# Database Settings
	define("DB_HOSTNAME", "localhost");
   	define("DB_USERNAME", "root");
   	define("DB_PASSWORD", "");
   	define("DB_DATABASE", "database");

    if(DB_HOSTNAME != ""){
    	include("db.class.php");
	    
	    if(class_exists("db")){
			$db = new db(array("hostname" => DB_HOSTNAME, "username" => DB_USERNAME, "password" => DB_PASSWORD, "database" => DB_DATABASE));
		}
	}

````
	
### Methods Calls: *Copy the below method code.*

##### Query Functions - Add Show for the query to be outputted.

````php

	# Run Basic Query
	$result = $db->query("SELECT * FROM Table");

	# Run Prepare Query
	$result = $db->query_prepare("SELECT * FROM someTable WHERE something = :comparison", array(':comparison' => $comparison));

	# Insert Query
	$result = $db->insert("table_name", array("column_name"=>"column_value"));

	# Update Query
	$result = $db->update('tablename',array('thisfield' => value, 'field2'=>value2),array('conditionfield'=>conValue));

	# Delete Query
	$result = $db->delete("table_name", array("column_name"=>"column_value"));

	# Num Rows Affected
	$result = $db->num_rows_affected($result);

````

##### Fetch Data - Gets the data from the Result. 
*(This is not needed for insert, update and delete.)*

````php

	# Fetch Data into an array
	# - Default uses FETCH ASSOC
	$data = $db->fetch_array($result);

	# Fetch Data into an array 
	# - PDO::FETCH_ASSOC: returns an array indexed by column name as returned in your result set
	$data = $db->fetch_array_assoc($result);

	# Fetch Data into an array
	# - PDO::FETCH_BOTH (default)b: returns an array indexed by both column name and 0-indexed 
	#   column number as returned in your result set
	$data = $db->fetch_both($result);

	# Fetch Data into an array
	# - PDO::FETCH_BOUND: returns TRUE and assigns the values of the columns in your result set 
	#   to the PHP variables to which they were bound with the PDOStatement::bindColumn() method
	$data = $db->fetch_bound($result);

	# Fetch Data into an array
	# - PDO::FETCH_CLASS: returns a new instance of the requested class, mapping the columns of 
	#	the result set to named properties in the class. If fetch_style includes PDO::FETCH_CLASSTYPE 
	#	(e.g. PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE) then the name of the class is determined 
	# 	from a value of the first column.
	$data = $db->fetch_class($result);

	# Fetch Data into an array
	# - PDO::FETCH_NUM: returns an array indexed by column number as returned in your result set, 
	#	starting at column 0
	$data = $db->fetch_num($result);

	# Fetch Data into an array
	# - PDO::FETCH_OBJ: returns an anonymous object with property names that correspond to the 
	#	column names returned in your result set
	$data = $db->fetch_object($result);

````
	
##### Other

````php

	# Last Insert ID
	$id = $db->last_insert_id($var = FALSE);
	
````
