<?php
header ("Content-type: image/png");

include "sicher/config.inc.php";
$conn = mysql_connect($mysql_host, $mysql_user, $mysql_password);
$db = mysql_select_db($mysql_db, $conn);

$heute = getdate();
$stunde = $heute['hours'];
$mday = $heute['mday'];
$zeit = time();

$result = mysql_query("SELECT * FROM mycounter WHERE id='$id'", $conn);
if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$heute2 = getdate($inhalte["zeit"]);
	$stunde2 = $heute2['hours'];
	$mday2 = $heute2['mday'];
	if ($mday2 != $mday) {
		$count_heute = 1;
	} else {
		$count_heute = $inhalte["heute"] + 1;
	}
	if ($stunde2 != $stunde) {
		$count = 1;
	} else {
		$count = $inhalte["stunde"] + 1;
	}
	$count_insg = $inhalte["insg"] + 1;
	mysql_query("UPDATE mycounter SET zeit='$zeit', stunde='$count', heute='$count_heute', insg='$count_insg' WHERE id='$id'", $conn);
} else {
	$mysqlquery = "INSERT INTO mycounter (id,zeit,stunde,heute,insg) VALUES ('$id','$zeit','1','1','1')";
	mysql_query($mysqlquery, $conn);
	$count = 1;
	$count_heute = 1;
	$count_insg = 1;
}
mysql_close($conn);

$text1 = "Aufrufe";
$text2 = "diese Stunde: $count";
$text3 = "Heute: $count_heute";
$text4 = "Insgesamt: $count_insg";

$bild = @ImageCreate (135, 100) or die ("Kann keinen neuen GD-Bild-Stream erzeugen");

if ($c != "") {
	$r = hexdec(substr($c, 0, 2));
	$g = hexdec(substr($c, 2, 2));
	$b = hexdec(substr($c, 4, 2));
	if ($r + $g + $b < 300) {
		$schwarz = ImageColorAllocate ($bild, 255, 255, 255);
	} else {
		$schwarz = ImageColorAllocate ($bild, 0, 0, 0);
	}
	$color = ImageColorAllocate($bild, $r, $g, $b);
} else {
	$color = ImageColorAllocate($bild, 100, 100, 100);
}

$font = realpath("verdanab.ttf");
ImageTTFText($bild, 11, 0, 5, 20, $color, $font, $text1);
ImageLine($bild, 5, 22, 65, 22, $color);
$font = realpath("verdana.ttf");
ImageTTFText($bild, 11, 0, 5, 40, $color, $font, $text2);
ImageTTFText($bild, 11, 0, 5, 60, $color, $font, $text3);
ImageTTFText($bild, 11, 0, 5, 80, $color, $font, $text4);
ImageColorTransparent($bild, $schwarz);
ImagePNG ($bild);

?>