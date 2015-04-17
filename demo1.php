<?php
	
header("Content-type: image/svg+xml");
mt_srand(time());

$chance_of_large_building = 20;
$small_building_size = 30;
$large_building_size = 50;
$building_density = 90;
$park_size = 40;
	
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
	
?>

<html>
	<body>
		<svg width="100%" height="1000" fill="black" version="1.1" baseProfile="full" xmlns="http://www.w3.org/2000/svg">
			<rect x="10" y="10" width="520" height="50" rx="5" ry="5" fill="black" fill-opacity="1.0" />
			<text x="150" y="40" fill="green" font-size="20">Welcome to Strangeville!</text>
			<rect x="10" y="70" width="520" height="520" rx="5" ry="5" fill="black" fill-opacity="1.0" />
			<?php
				for ($x = 20; $x < 472; $x=$x+50) {
					for ($y = 80; $y < 542; $y=$y+50) {
					
						#Find Building
						if (mt_rand(1,100) < $building_density) {
							
							# Find Width
							$width_roll = mt_rand(1,100);
							if ($width_roll < $chance_of_large_building) {
								$width = $large_building_size;
							}
							else {
								$width = $small_building_size;
							}
							
							#Find Height
							$height_roll = mt_rand(1,100);
							if ($height_roll < $chance_of_large_building) {
								$height = $large_building_size;
							}
							else {
								$height = $small_building_size;
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
			?>
		</svg>
	</body>
</html>
