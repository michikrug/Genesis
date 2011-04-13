<?php

$heute = getdate();
$stunde = $heute['hours'];
$mday = $heute['mday'];
$zeit = time();

$result = mysql_query("SELECT * FROM mycounter WHERE id='1'");
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
	mysql_query("UPDATE mycounter SET zeit='$zeit', stunde='$count', heute='$count_heute', insg='$count_insg' WHERE id='1'");
} else {
	$mysqlquery = "INSERT INTO mycounter (id,zeit,stunde,heute,insg) VALUES ('1','$zeit','1','1','1')";
	mysql_query($mysqlquery);
	$count = 1;
	$count_heute = 1;
	$count_insg = 1;
}

$wiol = 0;
$daten = mysql_query("SELECT seite FROM genesis_wio");
while ($inh = mysql_fetch_array($daten, MYSQL_ASSOC)) if (strpos($inh["seite"], "game") > 0) $wiol++;
$wio = mysql_num_rows($daten);

$ausgabe .= table(150, "bg");
$ausgabe .= tr(td(2, "head", "Counter"));
$ausgabe .= tr(td(0, "left", "Diese Stunde") . td(0, "right", $count));
$ausgabe .= tr(td(0, "left", "Heute") . td(0, "right", $count_heute));
$ausgabe .= tr(td(0, "left", "Insgesamt") . td(0, "right", $count_insg));
$ausgabe .= tr(td(0, "left", "Online") . td(0, "right", $wio));
$ausgabe .= tr(td(0, "left", "Eingeloggt") . td(0, "right", $wiol));
$ausgabe .= "</table>\n<br>\n";

unset($date, $wio, $wio1, $inh);

?>