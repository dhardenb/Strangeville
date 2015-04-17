<?php

header("Content-type: image/svg+xml");

# What I will eventualy do is create a seed that is set to
# the current time hack and then use that to seed the
# random algorithm.
# mt_srand(time());

define("TITLE", "Welcome to Strangeville!");
define("CHANCE_OF_A_LARGE_BUILDING", 25);
define("SMALL_BUILDING_SIZE", 30);
define("LARGE_BUILDING_SIZE", 52);
define("BUILDING_DENSITY", 90);
define("ZOMBIE_DENSITY", 4);
define("HUMAN_DENSITY", 2);
define("PARK_SIZE", 40);
define("ZOMBIE_COLOR", "YellowGreen");
define("ZOMBIE_SIZE", 3);
define("ZOMBIE_OPACITY", "1.0");
define("HUMAN_SIZE", 3);
define("HUMAN_COLOR", "SandyBrown");
define("HUMAN_OPACITY", "1.0");
define("FOUNTAIN_DENSITY", 50);
define("FOUNTAIN_SIZE", 7);
define("FOUNTAIN_COLOR", "Gray");
define("FOUNTAIN_OPACITY", "1.0");
define("WATER_SIZE", 5);
define("WATER_COLOR", "DodgerBlue");
define("WATER_OPACITY", "1.0");
	
function render_rectangle($x, $y, $width, $height, $color, $opacity) {
	echo '<rect x="';
	echo $x;
	echo '" y="';
	echo $y;
	echo '" width="';
	echo $width;
	echo '" height="';
	echo $height;
	echo '" fill="';
	echo $color;
	echo '" fill-opacity="';
	echo $opacity;
	echo '" />';
}

function render_circle($x, $y, $r, $color, $opacity) {
	echo '<circle cx="';
	echo $x;
	echo '" cy="';
	echo $y;
	echo '" r="';
	echo $r;
	echo '" fill="';
	echo $color;
	echo '" fill-opacity="';
	echo $opacity;
	echo '" />';
}
	
function render_line($x1, $y1, $x2, $y2) {

	$style = "stroke-dasharray: 5, 3; stroke: yellow; stroke-width: 1.0;";
	
	echo '<line ';
	echo 'x1="'; echo $x1; echo '" ';
	echo 'y1="'; echo $y1; echo '" ';
	echo 'x2="'; echo $x2; echo '" ';
	echo 'y2="'; echo $y2; echo '" ';
	echo 'style="'; echo $style; echo '" ';
	echo '/>';
}
	
function render_building($x, $y, $width, $height) {
	
	# Adjust position offsets to center building
	if ($width == 30) {
		$x = $x + 10;
	}
	else {
		$x = $x - 1;
	}
	if ($height == 30) {
		$y = $y + 10;
	}
	else {
		$y = $y - 1;
	}
	
	# Draw the buildings
	render_rectangle($x, $y, $width, $height, 'Gray', '1.0');
}

function render_park($x, $y) {
	render_rectangle($x+5, $y+5, PARK_SIZE, PARK_SIZE, 'ForestGreen', '0.5');
	
	if (mt_rand(1,100) <= FOUNTAIN_DENSITY) {
		render_circle($x+25, $y+25, FOUNTAIN_SIZE, FOUNTAIN_COLOR, FOUNTAIN_OPACITY);
		render_circle($x+25, $y+25, WATER_SIZE, WATER_COLOR, WATER_OPACITY);
	}
}
	
function render_city() {

	# Render Street Lines
	for ($x = 70; $x < 472; $x=$x+50) {
		render_line($x, 80, $x, 582);
	}
	for ($y = 130; $y < 572; $y=$y+50) {
		render_line(20, $y, 522, $y);
	}

	# Render Characters 
	#for ($x = 20; $x < 472; $x++) {
	#	for ($y = 80; $y < 542; $y++) {
	#	
	#		# Find Zombies
	#		if (mt_rand(1,2000) < ZOMBIE_DENSITY) {
	#			render_circle($x, $y, ZOMBIE_SIZE, ZOMBIE_COLOR, ZOMBIE_OPACITY);
	#		}
	#		
	#		# Find Humans
	#		if (mt_rand(1,2000) < HUMAN_DENSITY) {
	#			render_circle($x, $y, HUMAN_SIZE, HUMAN_COLOR, HUMAN_OPACITY);
	#		}
	#	}
	#}
	
	for ($x = 20; $x < 472; $x=$x+50) {
		for ($y = 80; $y < 542; $y=$y+50) {
		
			#Find Building
			if (mt_rand(1,100) < BUILDING_DENSITY) {
				
				# Find Width
				if (mt_rand(1,100) <= CHANCE_OF_A_LARGE_BUILDING) {
					$width = LARGE_BUILDING_SIZE;
				}
				else {
					$width = SMALL_BUILDING_SIZE;
				}
				
				#Find Height
				if (mt_rand(1,100) <= CHANCE_OF_A_LARGE_BUILDING) {
					$height = LARGE_BUILDING_SIZE;
				}
				else {
					$height = SMALL_BUILDING_SIZE;
				}
				
				#Render Building
				render_building($x, $y, $width, $height);
			}
			else {
				#Render Park
				render_park($x, $y);
			}
		}
	}
}
	
?>

<html>
	<svg width="100%" height="1000" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<rect x="10" y="10" width="520" height="50" rx="5" ry="5" fill="black" fill-opacity="1.0" />
		<text x="150" y="40" fill="green" font-size="20"><?php echo TITLE; ?></text>
		<rect x="10" y="70" width="520" height="520" rx="5" ry="5" fill="black" fill-opacity="1.0" />
		<?php render_city(); ?>
	</svg>
</html>
