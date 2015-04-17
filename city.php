<?php

	# This file demonstrates how to:
	# 	1. Create a MySql database from scratch
	# 	2. Programmaticly generate an xml file in memory
	# 	3. Read an xml file from memory and save it to the database
	# 	4. Read data from the database and save it as an xml file in memory
	#
	# The next steps would be to:
	#	1. Create an object model in javascript to hold the data structure
	#	2. Write the AJAX code to get the xml data from the server to the client
	#	3. Create a javascript engine to render the data structure in SVG
	#
	# And then...
	#	1. Write the javescrpt events to capture user commands
	#	2. Updat the AJAX code to get the commands back to the server
	#	3. Write the game logic to process the commands
	
	require('config.php');
	require('create_database.php');

	# This functions in this script will randomly generate a city and
	# save it in memory as an xml document.
	#
	# Here is the overview of the data structure.
	#	<city>
	#		<building>
	#			<x_pos>0</x_pos>
	#			<y_pos>0</y_pos>
	#			<type>building_type</type>
	#		</building>
	#	</city>
	
	define("BUILDING_DENSITY", 90);
	
	define("CHANCE_OF_A_SMALL_BUILDING", 40);
	define("CHANCE_OF_A_VERTICAL_BUILDING", 55);
	define("CHANCE_OF_A_HORIZONTAL_BUILDING", 70);
	define("CHANCE_OF_A_LARGE_BUILDING", 80);
	define("CHANCE_OF_A_FOUNTAIN", 90);
	
	define("FOUNTAIN_DENSITY", 50);
	define("NUMBER_OF_BLOCKS", 10);
	
	create_database();
	$city_generated_xml = generate_city();
	$city_id = insert_city($city_generated_xml);
	$city_xml_from_database = select_city($city_id);
	
	header("Content-Type: text/xml");
	echo $city_xml_from_database->SaveXML();
	
	function generate_city() {
	
		$root = new DomDocument('1.0', 'iso-8859-1');
		$city_node = $root->createElement("city");
		
		for ($x = 0; $x < NUMBER_OF_BLOCKS; $x++) {
			for ($y = 0; $y < NUMBER_OF_BLOCKS; $y++) {
			
				# Roll for building type.
				$type_roll = mt_rand(1,100);
				
				if ($type_roll <= CHANCE_OF_A_SMALL_BUILDING) {
					$type = 'small';
				}
				else if ($type_roll <= CHANCE_OF_A_VERTICAL_BUILDING){
					$type = 'vertical';
				}
				else if ($type_roll <= CHANCE_OF_A_HORIZONTAL_BUILDING){
					$type = 'horizontal';
				}
				else if ($type_roll <= CHANCE_OF_A_LARGE_BUILDING){
					$type = 'large';
				}
				else if ($type_roll <= CHANCE_OF_A_FOUNTAIN){
					$type = 'fountain';
				}
				else {
					$type = 'park';
				}
				
				$building_node = generate_building($root, $x, $y, $type);
				$city_node->appendChild($building_node);
			}
		}
		
		$root->appendChild($city_node);
		return $root;
	}
	
	function generate_building($root, $x, $y, $type) {
		
		$building_node = $root->createElement('building');
		
		$x_pos_node = $root->createElement('x_pos', $x);
		$building_node->appendChild($x_pos_node);
		
		$y_pos_node = $root->createElement('y_pos', $y);
		$building_node->appendChild($y_pos_node);
		
		$type_node = $root->createElement('type', $type);
		$building_node->appendChild($type_node);
		
		return $building_node;
	}
	
	function insert_city($city_xml) {
	
		$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
			or die('Could not connect to MySql database. ' . mysql_error());

		mysql_select_db(SQL_DB, $conn);
		
		$insert_query = "INSERT INTO city (id) VALUES (NULL)";
		mysql_query($insert_query) or die(mysql_error());
		
		$select_query = "SELECT id FROM city ORDER by id DESC LIMIT 1";
		$result = mysql_query($select_query) or die(mysql_error());
		while ($row = mysql_fetch_array($result)) {
			$city_id = $row['id'];
		}
		
		$buildings = $city_xml->getElementsByTagName("building");
		foreach($buildings as $building) {
			
			$x_poss = $building->getElementsByTagName("x_pos");
			foreach($x_poss as $x_pos) {
				$x_pos_value = $x_pos->nodeValue;
			}
			
			$y_poss = $building->getElementsByTagName("y_pos");
			foreach($y_poss as $y_pos) {
				$y_pos_value = $y_pos->nodeValue;
			}
			
			$types = $building->getElementsByTagName("type");
			foreach($types as $type) {
				$type_value = $type->nodeValue;
			}
			
			$insert_query = "INSERT INTO building (city_id, x_pos, y_pos, type) VALUES (" . $city_id . ", " . $x_pos_value . ", " . $y_pos_value . ", '" . $type_value . "')";
			mysql_query($insert_query) or die(mysql_error());
		}
		
		return $city_id;
	}
	
	function select_city($city_id) {
	
		$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
			or die('Could not connect to MySql database. ' . mysql_error());

		mysql_select_db(SQL_DB, $conn);
		
		$root = new DomDocument('1.0', 'iso-8859-1');
		$city_node = $root->createElement("city");
		
		$select_query = "SELECT x_pos, y_pos, type FROM building WHERE city_id = " . $city_id;
		$result = mysql_query($select_query) or die(mysql_error());
		while ($row = mysql_fetch_array($result)) {
		
			$building_node = $root->createElement('building');
		
			$x_pos_node = $root->createElement('x_pos', $row['x_pos']);
			$building_node->appendChild($x_pos_node);
			
			$y_pos_node = $root->createElement('y_pos', $row['y_pos']);
			$building_node->appendChild($y_pos_node);
			
			$type_node = $root->createElement('type', $row['type']);
			$building_node->appendChild($type_node);
			
			$city_node->appendChild($building_node);
		}
		
		$root->appendChild($city_node);
		return $root;
	}
?>