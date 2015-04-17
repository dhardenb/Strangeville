<?php
	 
	require('config.php');
	
	function create_database()
	{

		$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
			or die('Could not connect to MySql database. ' . mysql_error());
				
		$sql = "CREATE DATABASE IF NOT EXISTS " . SQL_DB . ";";

		$res = mysql_query($sql) or die(mysql_error());

		mysql_select_db(SQL_DB, $conn);

		$city_table = <<<EOS
			CREATE TABLE IF NOT EXISTS city (
				id int(11) NOT NULL auto_increment,
				PRIMARY KEY (id)
			) TYPE=MyISAM;
EOS;

		$building_table = <<<EOS
			CREATE TABLE IF NOT EXISTS building (
				id int(11) NOT NULL auto_increment,
				city_id int(11),
				x_pos int(11),
				y_pos int(11),
				type varchar(255),
				PRIMARY KEY (id)
			) TYPE=MyISAM;
EOS;

		$res = mysql_query($city_table) or die(mysql_error());
		$res = mysql_query($building_table) or die(mysql_error());
	}
	
?>