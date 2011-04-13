<?php

require_once "sicher/config.inc.php";
$connection = @mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$connection) die("Es konnte keine Datenbankverbindung hergestellt werden.");
mysql_select_db($mysql_db, $connection);
mysql_query("INSERT INTO genesis_stats SELECT NULL, name, NOW() as zeit, punkte, punktek, punktef, punktem, kampfpkt FROM genesis_spieler");
mysql_query("UPDATE genesis_spieler SET angriffe='0', deffs='0'");
mysql_close($connection);

?>