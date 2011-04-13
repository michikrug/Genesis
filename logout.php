<?php

$name = $_SESSION["name"];
$sid = $_SESSION["sid"];

unset($_SESSION["name"]);
unset($_SESSION["sid"]);
unset($_SESSION["ip"]);
unset($_SESSION["klicks"]);
unset($_SESSION["next"]);
unset($_SESSION["delay"]);
mysql_query("UPDATE genesis_spieler SET sessid='0' WHERE sessid='$id' OR id='$sid'");
setcookie ("gencookie", "", 315532800);
session_unset();
session_destroy();

unset($name, $sid, $loginzeit, $ip, $inhalte, $result);

$ausgabe .= "Logout erfolgreich!<br><br>\n";
$ausgabe .= hlink("", "index.php", "Zur Hauptseite");

?>