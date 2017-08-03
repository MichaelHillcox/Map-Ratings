
<html>
<head>
<title>Server Map Popularity</title> <!-- Set the title of the page -->
<link rel="stylesheet" type="text/css" href="style.css">
<script src="sorttable.js"></script>
</head>
<body>

<?php
// Database setttings
// These should match the database settings in your b3.xml
$db_host = 'localhost';  //MySQL host
$db_user = '';		 //MySQL username
$db_pwd = '';	 //MySQL password
$database = '';	 //B3 databse

//Site settings
$title = 'Map Popularity';
$game = 'cod4'; //Enter your game code for map thumbnail images
			   //or enter "off" to disable them. Codes are listed below.
//Game codes:
// cod = Call of Duty
// cod2 = Call of Duty 2
// cod4 = Call of Duty 4: Modern Warfare
// codww = Call of Duty 5: World at War
// blackops = Call of Duty: Black Ops
//
// cod6 = Call of Duty 6: Modern Warefare 2 (COD6 map images from the Stimulus and Resurgence
// 		  map packs cannot be loaded automatically. Read the section below on how to add
//		  the map images manually.)
//
// urbanterror = Urban Terror
// etqw = Enemy Territory
// q3 = Quake 3
// sof2 = Soldier of Fortune 2
//
// bc2 = Battlefield Bad Company 2
// moh = Medal of Honor 2010
// bf3 = Battlefield 3
//
// homefront = Homefront

// TO ADD CUSTOM OR MISSING MAP IMAGES:
//
// Add the map images to the "mapimages" folder. Make sure the image is a
// 160px x 120px .jpg file and the name of the image matches exactly the name of the map.
// Also, make sure the image name is all lower case.


if (!mysql_connect($db_host, $db_user, $db_pwd))
    die('<div class="alert">Can\'t connect to database</div>');

if (!mysql_select_db($database))
    die('<div class="alert">Can\'t select database</div>');

$result = mysql_query("SELECT `map`, `likes`, `dislikes` FROM `map_stats`");
if (!$result) {
    die('<div class="alert">Query to show fields from table failed</div');
}

$result2 = mysql_query('SELECT SUM(`likes`) AS `likes`, SUM(`dislikes`) AS `dislikes` FROM `map_stats`');
$sum_row = mysql_fetch_assoc($result2);
$total_votes = ($sum_row['likes'] + $sum_row['dislikes']);

$rows_num = mysql_num_rows($result);

$supported = array('cod', 'cod2', 'cod4', 'codww', 'cod6', 'blackops',
				   'urbanterror', 'etqw', 'q3', 'sof2', 'bc2', 'moh',
				   'bf3', 'homefront', 'off');

$imglink = 'http://image.www.gametracker.com/images/maps/160x120/';
$mapsdir = 'mapimages/';
$absdir = realpath(dirname(__FILE__)).'/'.$mapsdir;
function getMapImage($map)
{
	global $game;
	global $imglink;
	global $mapsdir;
	global $absdir;

	if ($game == 'cod6'):
		$namechange = array(
			"mp_boneyard" => "mp_scrapyard",
			"mp_brecourt" => "mp_wasteland",
			"mp_checkpoint" => "mp_karachi",
			"mp_nightshift" => "mp_skidrow",
		);
		$orig = strtolower($map);
		if (array_key_exists(strtolower($map), $namechange)):
			$orig = strtolower($map);
			$map = $namechange[$map];
		endif;
		$truename = $orig.'.jpg';
		$imglink = 'http://www.themodernwarfare2.com/images/mw2/maps/';
		$mapfile = str_replace('mp_', '', strtolower($map).'-prev.jpg');
		$localpath = $mapsdir.$truename;
		$remotepath = $imglink.$mapfile;

		if (file_exists($localpath)):
			$finalimg = '<img src="'.$localpath.'" width="160" height="120">';
		else:
			$imgarray = getimagesize($remotepath);
			if(is_array($imgarray)):
				$image_to_fetch = file_get_contents($remotepath);
				$local_image_file = fopen($absdir.$truename, 'w+');
				chmod($absdir.$truename,0755);
				fwrite($local_image_file, $image_to_fetch);
				fclose($local_image_file);

				resize_image($localpath, 160, 120);

				$finalimg = '<img src="'.$localpath.'" width="160" height="120">';
			else:
				$finalimg = '<img src="'.$mapsdir.'nomap.png">';
			endif;
		endif;
	else:
		$mapfile = strtolower($map).'.jpg';
		$localpath = $mapsdir.$mapfile;
		$remotepath = $imglink.$game.'/'.$mapfile;

		if (file_exists($localpath)):
			$finalimg = '<img src="'.$localpath.'" width="160" height="120">';
		else:
			$imgarray = getimagesize($remotepath);
			if(is_array($imgarray)):
				$image_to_fetch = file_get_contents($remotepath);
				$local_image_file = fopen($absdir.$mapfile, 'w+');
				chmod($absdir.$mapfile,0755);
				fwrite($local_image_file, $image_to_fetch);
				fclose($local_image_file);

				$finalimg = '<img src="'.$localpath.'" width="160" height="120">';
			else:
				$finalimg = '<img src="'.$mapsdir.'nomap.png">';
			endif;
		endif;
	endif;

	return $finalimg;
}

function resize_image($file, $w, $h, $crop=FALSE) {
    $img = new Imagick($file);
    if ($crop) {
        $img->cropThumbnailImage($w, $h);
    } else {
        $img->thumbnailImage($w, $h, TRUE);
    }

    $img->writeImage($file);
}

echo '<h1 style="text-align:center">'.$title.'</h1>';
echo '<h4 style="text-align:center;">Showing '.$total_votes.' total votes on '.$rows_num.' maps</h4>';

if (!is_writable($absdir)):
	echo '<div class="alert">ERROR: The map images directory ('.$mapsdir.') is not writeable by the web server.<br />';
	echo 'Please correct this or set the "$game" setting to "off" to remove this message.</div>';
	$game = 'off';
elseif (!in_array($game, $supported)):
	echo '<div class="alert">ERROR: "'.$game.'" is not a supported game code.<br />';
	echo 'Map thumbnails have been disabled.</div>';
	$game = 'off';
endif;

echo '<h6 style="text-align:center;margin:2px;padding:0px;">Click to sort</h6>';

echo '<table id="list" class="sortable" border="1" align="center">';
echo '<tr>';
echo '<th title="Click to sort by map name" style="text-align:left;">Map</th><th title="Click to sort by likes">Likes</th><th title="Click to sort by popularity">Popularity</th><th title="Click to sort by dislikes">Dislikes</th>';
echo '</tr>';

while($row = mysql_fetch_row($result))
{
    $pct = intval(($row[1] / ($row[1] + $row[2])) * 100);
    $pct2 = (100 - $pct);

    echo "<tr>";

    if($game == "off"):
    	echo '<td class="map">'.$row[0].'</td>';
    else:
    	echo '<td class="map"><div class="tooltip"><span>'.getMapImage($row[0]).'<div class="arrow"></div></span>'.$row[0].'</div></td>';
    endif;

    echo "<td align=\"center\" class=\"like\">$row[1]</td>";

    if($pct < "1"):
    	$bar = '<div class="lbar" style="display:none;"px;"></div><div class="rbar" style="width:'.$pct2.'px;"></div>';
    elseif($pct2 < "1"):
    	$bar = '<div class="lbar" style="width:'.$pct.'px;"></div><div class="rbar" style="display:none;"px;"></div>';
    else:
    	$bar = '<div class="lbar" style="width:'.$pct.'px;"></div><div class="rbar" style="width:'.$pct2.'px;"></div>';
    endif;

    echo '<td class"barcell"><span style="display:none;">'.$pct.'</span>'.$bar.'</td>';
	echo '<td align="center" class="dislike">'.$row[2].'</td>';

    echo '</tr>';
}
mysql_free_result($result);
?>
</body>
</html>
