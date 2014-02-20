<?php
/***************************************************************************************************************
 * 											DATABASE CLASS
 * 											--------------
 * 	@author: Wade Dunbar
 * 	@version: 1.0
 *
 * Create Instance: (Copy the below code.)
 * -------------------------------------------------------------------------------------------------------------
	
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

 * -------------------------------------------------------------------------------------------------------------
 *
 * Methods Calls: (Copy the below method code.)
 * -------------------------------------------------------------------------------------------------------------
 	
 	Query Functions - Add Show for the query to be outputted.
	------------------------------------------------------------------------------------------------------------

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

	Fetch Data - Gets the data from the Result. This is not needed for insert, update and delete.
	------------------------------------------------------------------------------------------------------------
	
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
	
	Other
	------------------------------------------------------------------------------------------------------------
	
	# Last Insert ID
	$id = $db->last_insert_id($var = FALSE);

 * -------------------------------------------------------------------------------------------------------------
 * 
 * Errors (Not being used yet.)
 * -------------------------------------------------------------------------------------------------------------
 **************************************************************************************************************/

	class db{

		# Database Setting Variables
		private $hostname, $username, $password, $database, $dbDriver, $charSet;

		# Connection Variables
		private $db, $query_num, $insert_id;

	/**************************************************************************
	 * Construct Function
	 *************************************************************************/

		function __construct($settings){

			isset($settings['hostname']) ? $this->hostname = $settings['hostname']: die("Hostname is not set!");
			isset($settings['username']) ? $this->username = $settings['username']: die("Username is not set!");
			isset($settings['password']) ? $this->password = $settings['password']: die("Password is not set!");
			isset($settings['database']) ? $this->database = $settings['database']: die("Database is not set!");

			$this->dbDriver = isset($settings['dbDriver']) ? $settings['dbDriver']: "mysql";
			$this->charSet 	= isset($settings['charSet']) ? $settings['charSet']: "";

			# Start a connection to DB.
			$this->connect();
		}

	/**************************************************************************
	 * /Construct Function
	 *************************************************************************/

	/**************************************************************************
	 * Connect Function
	 *************************************************************************/

		private function connect(){
			try{
				$this->db 	= new PDO("$this->dbDriver:host=$this->hostname;dbname=$this->database;$this->charSet", $this->username, $this->password);
				$this->error_handling();
			}catch(PDOException $e){
				die($e);
			}
		}

	/**************************************************************************
	 * /Connect Function
	 *************************************************************************/

	/**************************************************************************
	 * Error Handling Function
	 *************************************************************************/

		private function error_handling(){
			if($this->db){
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);	
			}
		}

	/**************************************************************************
	 * /Error Handling Function
	 *************************************************************************/

	/**************************************************************************
	 * Print Function
	 * @param [any] $var []
	 *************************************************************************/
	
		private function _print($var){
			echo is_array($var) ? "<pre>".print_r($var,true)."</pre>": $var."<br>";
		}

	/**************************************************************************
	 * /Print Function
	 *************************************************************************/

	/**************************************************************************
	 * Query Function
	 * @param 	[query] 	$query 	[]
	 * @param 	[boolean] 	$show 	[]
	 * @return 	[result] 	$result []	
	 *************************************************************************/

		function query($query, $show = false){
			($show) ? $this->_print($query): "";

			if($result = $this->db->query($query)): 
				return $result;
			else: 
				$this->_print($this->db->errorInfo());
			endif;
		}

	/**************************************************************************
	 * /Query Function
	 *************************************************************************/

	/**************************************************************************
	 * Query Prepare Function
	 * @param 	[query] 	$query 		[]
	 * @param 	[array] 	$prepare 	[]
	 * @param 	[boolean] 	$show 		[]
	 * @return 	[result] 	$result 	[]	
	 *************************************************************************/

		function query_prepare($query, $prepare, $show = false){
			if($show){
				$this->_print($query);
				$this->_print($prepare);
			}
							
			$result = $this->db->prepare($query);
			if($result->execute($prepare)){
				return $result;
			}else{
				$this->_print($this->db->errorInfo());
			}
		}

	/**************************************************************************
	 * /Query Prepare Function
	 *************************************************************************/

	/**************************************************************************
	 * Num Rows Affected Function
	 * @param  [result]  $result [description]
	 * @return [integer]         [description]
	 *************************************************************************/

		function num_rows_affected(&$result){
			return (isset($result))? $result->rowCount(): "";
		}

	/**************************************************************************
	 * /Num Rows Affected Function
	 *************************************************************************/

	/**************************************************************************
	 * Fetch Array Function 
	 * - Default uses FETCH ASSOC
	 * @param  [result] $result [description]
	 * @return [array]          [description]
	 *************************************************************************/

		function fetch_array(&$result){
			$fetchType = PDO::FETCH_ASSOC;
			return (isset($result)) ? $result->fetchAll($fetchType): "";
		}

	/**************************************************************************
	 * /Fetch Array Function
	 *************************************************************************/

	/**************************************************************************
	 * Fetch Array Assoc Function 
	 * - PDO::FETCH ASSOC
	 * @param  [result] $result [description]
	 * @return [array]          [description]
	 *************************************************************************/

		function fetch_array_assoc(&$result){
			$fetchType = PDO::FETCH_ASSOC;
			return (isset($result)) ? $result->fetchAll($fetchType): "";
		}

	/**************************************************************************
	 * Fetch Array Assoc Function
	 *************************************************************************/
		
	/**************************************************************************
	 * Fetch Both Function
	 * -------------------
	 * PDO::FETCH_BOTH (default): returns an array indexed by both column name 
	 * and 0-indexed column number as returned in your result set.
	 * 
	 * @param  [type] $result [description]
	 * @return [type]         [description]
	 *************************************************************************/

		function fetch_both(&$result){
			$fetchType = PDO::FETCH_BOTH;
			return (isset($result)) ? $result->fetchAll($fetchType): "";
		}

	/**************************************************************************
	 * /Fetch Both Function
	 *************************************************************************/
		
		
	/**************************************************************************
	 * Fetch Bound Function
	 * --------------------
	 * PDO::FETCH_BOUND: returns TRUE and assigns the values of the columns in 
	 * your result set to the PHP variables to which they were bound with the 
	 * PDOStatement::bindColumn() method
	 * 
	 * @param  [type] $result [description]
	 * @return [type]         [description]
	 *************************************************************************/

		function fetch_bound(&$result){
			$fetchType = PDO::FETCH_BOUND;
			return (isset($result)) ? $result->fetchAll($fetchType): "";
		}
	
	/**************************************************************************
	 * /Fetch Bound Function
	 *************************************************************************/	
		
	/**************************************************************************
	 * Fetch Class Function
	 * --------------------
	 * PDO::FETCH_CLASS: returns a new instance of the requested class, mapping 
	 * the columns of the result set to named properties in the class. If fetch_style 
	 * includes PDO::FETCH_CLASSTYPE (e.g. PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE) 
	 * then the name of the class is determined from a value of the first column.
	 * 
	 * @param  [type] $result [description]
	 * @return [type]         [description]
	 *************************************************************************/

		function fetch_class(&$result){
			$fetchType = PDO::FETCH_CLASS;
			return (isset($result)) ? $result->fetchAll($fetchType): "";
		}
	
	/**************************************************************************
	 * /Fetch Class Function
	 *************************************************************************/
		
	/**************************************************************************
	 * Fetch Num Function
	 * ------------------
	 * PDO::FETCH_NUM: returns an array indexed by column number as returned in 
	 * your result set, starting at column 0
	 * 
	 * @param  [type] $result [description]
	 * @return [type]         [description]
	 *************************************************************************/

		function fetch_num(&$result){
			$fetchType = PDO::FETCH_NUM;
			return (isset($result)) ? $result->fetchAll($fetchType): "";
		}
		
	/**************************************************************************
	 * /Fetch Num Function
	 *************************************************************************/
		
	/**************************************************************************
	 * Fetch Object Function
	 * --------------------- 
	 * PDO::FETCH_OBJ: returns an anonymous object with property names that 
	 * correspond to the column names returned in your result set
	 * 
	 * @param  [type] $result [description]
	 * @return [type]         [description]
	 *************************************************************************/

		function fetch_object(&$result){
			$fetchType = PDO::FETCH_OBJ;
			return (isset($result)) ? $result->fetchAll($fetchType): "";
		}

	/**************************************************************************
	 * /Fetch Object Function
	 *************************************************************************/
		
	/**************************************************************************
	 * Num Rows Function
	 * -----------------
	 * Returns the number of rows in a result.
	 * 
	 * @param  [type] $result [description]
	 * @return [type]         [description]
	 *************************************************************************/

		function num_rows($result){
			return (isset($result)) ? $result->rowCount(): "";
		}

	/**************************************************************************
	 * /Num Rows Function
	 *************************************************************************/

	/**************************************************************************
	 * Insert Function
	 * - Insert into database table.
	 * @param  [type]  $table         [description]
	 * @param  [type]  $arFieldValues [description]
	 * @param  boolean $show          [description]
	 * @return [type]                 [description]
	 *************************************************************************/

		function insert($table, $arFieldValues, $show = FALSE){
			# Create Variable instances.
			$escVals = array();$excVals = array();$cnt = 0;

			# Save field and Values to variables.
			$fields 	= array_keys($arFieldValues);
			$values 	= array_values($arFieldValues);
			
			foreach($values as $val){
				$key 			= ":$cnt";
				$escVals[] 		= $key;
				$excVals[$key] 	= $val;
				$cnt++;
			}
		
			$sql = " INSERT INTO $table (" . join(', ',$fields) . ") VALUES(" . join(', ',$escVals) . ")";
		
			($show) ? $this->_print($sql): "";
		
			$result 			= $this->db->prepare($sql);
			$res 				= $result->execute($excVals);
			$this->insert_id 	= $this->db->lastInsertId();
			return $res;
		}

	/**************************************************************************
	 * /Insert Function
	 *************************************************************************/

	/**************************************************************************
	 * Update Function
	 * - Function to update a row or multiple rows in a table	
	 * @param  [type]  $table         [description]
	 * @param  [type]  $arFieldValues [description]
	 * @param  [type]  $arConditions  [description]
	 * @param  boolean $show          [description]
	 * @return [type]                 [description]
	 *************************************************************************/

		function update($table, $arFieldValues, $arConditions, $show = FALSE){
			# Create Variable instances.
			$arUpdates = array();$excVals = array();$arWhere = array();$cnt = 0;

			foreach($arFieldValues as $field => $val){				
				$key 			= ":$cnt";
				$arUpdates[] 	= "$field = $key";
				$excVals[$key] 	= $val;
				$cnt++;
			}
		
			foreach($arConditions as $field => $val){
				$key 			= ":$cnt";
				$arWhere[] 		= "$field = $key";
				$excVals[$key] 	= $val;
				$cnt++;
			}
			
			$sql = "UPDATE $table SET ". join(', ',$arUpdates) . " WHERE " . join(' AND ',$arWhere);
		
			if($show){
				$this->_print($sql);
				$this->_print($excVals);
			}
			
			$result = $this->db->prepare($sql);
			$res 	= $result->execute($excVals);
			return $res;
		}

	/**************************************************************************
	 * /Update Function
	 *************************************************************************/
		
	/**************************************************************************
	 * Delete Function
	 * - Function to delete a row or multiple rows from a table
	 * @param  [type]  $table        [description]
	 * @param  [type]  $arConditions [description]
	 * @param  boolean $show         [description]
	 * @return [type]                [description]
	 *************************************************************************/

		function delete($table, $arConditions, $show = FALSE){
			# Create Variable instances.
			$arWhere = array();$excVals = array();$cnt = 0;
		
			foreach($arConditions as $field => $val){
				$key 			= ":$cnt";
				$arWhere[] 		= "$field = $key";
				$excVals[$key] 	= $val;
				$cnt++;
				
			}
			
			$sql = "DELETE FROM $table WHERE " . join(' AND ',$arWhere);
		
			($show) ? $this->_print($sql): "";
		
			$result = $this->db->prepare($sql);
			foreach($excVals as $k => $v){
				$result->bindParam("$k", $v);
			}
			$res 	= $result->execute();
			return $res;
		}

	/**************************************************************************
	 * /Delete Function
	 *************************************************************************/
		
	/**************************************************************************
	 * Last Insert Id Function
	 * - Get last insert id
	 * @param  boolean $var [description]
	 * @return [type]       [description]
	 *************************************************************************/

		function last_insert_id($var = FALSE){
			$id = ($var) ? $this->db->lastInsertId(): ($this->db) ? $this->insert_id: 0;
			return $id;
		}

	/**************************************************************************
	 * /Last Insert Id Function
	 *************************************************************************/

	}

/***************************************************************************************************************
 * 											/DATABASE CLASS 
 **************************************************************************************************************/