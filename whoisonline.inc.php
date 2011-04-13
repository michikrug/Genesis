<?php

$ip = $_SERVER["REMOTE_ADDR"];
$sit = $_SERVER["REQUEST_URI"];
$aktuell = time();
$daten = mysql_query("SELECT ip FROM genesis_wio WHERE ip='$ip'");
if (mysql_fetch_array($daten, MYSQL_ASSOC)) {
	$daten = mysql_query("UPDATE genesis_wio SET zeit='$aktuell', seite='$sit' WHERE ip='$ip'");
} else {
	$daten = mysql_query("INSERT INTO genesis_wio (ip, zeit, seite) VALUES ('$ip', '$aktuell', '$sit')");
}
$daten = mysql_query("DELETE FROM genesis_wio WHERE zeit < '$aktuell' - 600");

?>