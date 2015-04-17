<?php

header("Content-type: image/svg+xml");
# mt_srand(time());

define("TITLE", "Welcome to Strangeville!");
define("CHANCE_OF_A_LARGE_BUILDING", 20);
define("SMALL_BUILDING_SIZE", 30);
define("LARGE_BUILDING_SIZE", 50);
define("BUILDING_DENSITY", 90);
define("ZOMBIE_DENSITY", 4);
define("HUMAN_DENSITY", 2);
define("PARK_SIZE", 40);
	
function render_building($x, $y, $width, $height) {
	
	# Adjust position offsets to center building
	if ($width == 30) {
		$x = $x + 10;
	}
	if ($height == 30) {
		$y = $y + 10;
	}
	
	echo '<rect x="';
	echo $x;
	echo '" y="';
	echo $y;
	echo '" width="';
	echo $width;
	echo '" height="';
	echo $height;
	echo '" fill="gray"';
	echo ' fill-opacity="1.0" />';
	
	for ($i=$x+3; $i < $x+$width-3; $i++) {
		for ($j=$y+3; $j < $y+$height-3; $j++) {
		
			# Find Humans
			if (mt_rand(1,2000) < HUMAN_DENSITY) {
				render_human($i, $j);
			}
		}
	}
}
	
function render_park($x, $y) {
	
	# Adjust position offsets to center park
	$x = $x + 5;
	$y = $y + 5;

	echo '<rect x="';
	echo $x;
	echo '" y="';
	echo $y;
	echo '" width="40"';
	echo ' height="40"';
	echo ' fill="green"';
	echo ' fill-opacity="0.3" />';
}
	
function render_zombie($x, $y) {
	echo '<circle cx="';
	echo $x;
	echo '" cy="';
	echo $y;
	echo '" r="3"';
	echo ' fill="green"';
	echo ' fill-opacity="0.75" />';
}

function render_human($x, $y) {
	echo '<circle cx="';
	echo $x;
	echo '" cy="';
	echo $y;
	echo '" r="3"';
	echo ' fill="red"';
	echo ' fill-opacity="0.75" />';
}
	
?>
<html>
	<body>
		<svg width="100%" height="1000" fill="black" version="1.1" baseProfile="full" xmlns="http://www.w3.org/2000/svg">
			<rect x="10" y="10" width="520" height="50" rx="5" ry="5" fill="black" fill-opacity="1.0" />
			<text x="150" y="40" fill="green" font-size="20"><?php echo TITLE; ?></text>
			<rect x="10" y="70" width="520" height="520" rx="5" ry="5" fill="black" fill-opacity="1.0" />
			<?php
			
				for ($x = 20; $x < 472; $x=$x+50) {
					for ($y = 80; $y < 542; $y=$y+50) {
					
						#Find Building
						if (mt_rand(1,100) < BUILDING_DENSITY) {
							
							# Find Width
							$width_roll = mt_rand(1,100);
							if ($width_roll < CHANCE_OF_A_LARGE_BUILDING) {
								$width = LARGE_BUILDING_SIZE;
							}
							else {
								$width = SMALL_BUILDING_SIZE;
							}
							
							#Find Height
							$height_roll = mt_rand(1,100);
							if ($height_roll < CHANCE_OF_A_LARGE_BUILDING) {
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
				
				for ($x = 20; $x < 472; $x++) {
					for ($y = 80; $y < 542; $y++) {
						# Find Zombies
						if (mt_rand(1,2000) < ZOMBIE_DENSITY) {
							render_zombie($x, $y);
						}
					}
				}
			?>
		</svg>
	</body>
</html>
