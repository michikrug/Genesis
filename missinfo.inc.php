<?php

$abbr = isset($_REQUEST["abbr"]) ? $_REQUEST["abbr"] : NULL;
$a = isset($_REQUEST["a"]) ? $_REQUEST["a"] : NULL;

if ($newmiss == 1) {
	$result = mysql_query("SELECT * FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='miss' AND id=LAST_INSERT_ID()");
} else {
	$result = mysql_query("SELECT * FROM genesis_aktionen WHERE (basis1='" . $inhalte_s["basis1"] . "' or basis1='" . $inhalte_s["basis2"] . "') AND typ='miss' AND id='$mid'");
}
if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if ($abbr == 1 && $inhalte["aktion"] != 5 && $a != 5 && $inhalte["endzeit"] > time()) {
		if ($inhalte["aktion"] != 4 || ($inhalte["aktion"] == 4 && $inhalte["zusatz"] >= 1 && $inhalte["zusatz"] <= 5)) {
			$en = time() + (time() - $inhalte["startzeit"]);
			$mra = $inhalte["ress"];
			$mrab = explode("||", $inhalte["ress"]);
			$eb = round($mrab[5] * (2 * $inhalte["endzeit"] - $inhalte["startzeit"] - time()) / (2 * ($inhalte["endzeit"] - $inhalte["startzeit"])), 0);
			if ($inhalte["aktion"] == 1 || $inhalte["aktion"] == 3) {
				$mra = "0||0||0||0||0||$eb";
			} else {
				$mra = $mrab[0] . "||" . $mrab[1] . "||" . $mrab[2] . "||" . $mrab[3] . "||" . $mrab[4] . "||" . $eb;
			}
			if ($inhalte["aktion"] == 1 || $inhalte["aktion"] == 4) mysql_query("UPDATE genesis_spieler SET angriffe=angriffe-1 WHERE basis1='" . $inhalte["basis1"] . "' or basis2='" . $inhalte["basis1"] . "'");
			if ($inhalte["aktion"] == 1 && $inhalte["zusatz"] == 1) mysql_query("UPDATE genesis_spieler SET attzeit='0' WHERE basis1='" . $inhalte["basis1"] . "' or basis2='" . $inhalte["basis1"] . "'");
			if ($inhalte["aktion"] == 4) mysql_query("UPDATE genesis_spieler SET deffzeit='0' WHERE basis1='" . $inhalte["basis1"] . "' or basis2='" . $inhalte["basis1"] . "'");
			mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . time() . "', endzeit='$en', ress='$mra', zusatz='0' WHERE id='$mid'");
			mysql_free_result($result);
			mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('$name', '" . $REMOTE_ADDR . "', '" . time() . "', 'ABBRUCH: miss " . $inhalte["aktion"] . " - " . $inhalte["basis1"] . " - " . $inhalte["basis2"] . " - " . $inhalte["einheiten"] . " - $mra - " . $inhalte["zusatz"] . "')");
			$result = mysql_query("SELECT * FROM genesis_aktionen WHERE (basis1='" . $inhalte_s["basis1"] . "' or basis1='" . $inhalte_s["basis2"] . "') AND typ='miss' AND id='$mid'");
			$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
		}
	}

	$dauer = $inhalte["endzeit"] - time();
	include "dauer.inc.php";
	mt_srand(microtime() * 1000000);
	$tid = intval(mt_rand(1111111, 9999999));
	$outa = "<font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s</font><script language=JavaScript>init_countdown ('$tid', $dauer, 'Beendet', '', '');</script>";
	$mt = $inhalte["aktion"];
	$mk = explode(":", $inhalte["basis2"]);
	$mkx = $mk[0];
	$mky = $mk[1];
	$mkz = $mk[2];
	$result2 = mysql_query("SELECT name,bname FROM genesis_basen WHERE koordx='$mkx' AND koordy='$mky' AND koordz='$mkz'");
	$inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
	if (!$inhalte2["name"]) {
		$inhalte2["name"] = "unbesetzt";
		$inhalte2["bname"] = "unbekannt";
	}
	$ausgabe .= tr(td(2, "head", "Missionsinformation"));
	$ausgabe .= tr(td(0, "left", "Startpunkt") . td(0, "right", $inhalte["basis1"]));
	$ausgabe .= tr(td(0, "left", "Zielkoordinaten") . td(0, "right", $inhalte["basis2"]));
	if ($mt == 1) {
		if ($inhalte["zusatz"] == 1) $out = "gemeinsamer ";
		$out .= "Angriff";
	}
	if ($mt == 2) {
		$out = "Transport";
		if ($inhalte["zusatz"] == 1) $out = "Stationierung";
		if ($inhalte["zusatz"] == 2) $out = "Save-Mission";
	}
	if ($mt == 3) $out = "Spionage";
	if ($mt == 4) $out = "Verteidigung";
	if ($mt == 5) $out = "Rückkehr";
	if ($mt == 6) $out = "Zellteilung";
	if ($mt == 7) $out = "Eiersuche";
	$ausgabe .= tr(td(0, "left", "Missionstyp") . td(0, "right", $out));
	$ausgabe .= tr(td(0, "left", "Ankunftszeit") . td(0, "right", $zeitpunkt));
	if ($mt == 5) {
		$dauer = $inhalte["endzeit"] - time();
	} elseif ($mt == 4 && $inhalte["zusatz"] <= 5 && $inhalte["zusatz"] >= 1) {
		$dauer = $inhalte["startzeit"] + (($inhalte["endzeit"] - $inhalte["startzeit"]) * 2) - time() + ($inhalte["zusatz"] * 3600);
	} else {
		$dauer = $inhalte["startzeit"] + (($inhalte["endzeit"] - $inhalte["startzeit"]) * 2) - time();
	}
	include "dauer.inc.php";
	$ausgabe .= tr(td(0, "left", "Rückkehrzeit") . td(0, "right", $zeitpunkt));
	$ausgabe .= tr(td(0, "left", "Restzeit") . td(0, "right", $outa));

	$ausgabe .= tr(td(2, "head", "Exo-Zellen"));
	$me = explode("||", $inhalte["einheiten"]);
	for ($i = 1; $i <= 8; $i++) {
		$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
		$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
		if ($me[$i-1] > 0) $ausgabe .= tr(td(0, "left", $inhalte1["bezeichnung"]) . td(0, "right", format($me[$i-1])));
	}
	if ($mt == 1) {
		$ausgabe .= tr(td(2, "head", "Plünderpriorität"));
		$mr = explode("||", $inhalte["ress"]);
		for ($i = 1; $i <= 5; $i++) $ausgabe .= tr(td(0, "left", "$i.") . td(0, "right", num2typ($mr[($i-1)])));
	}
	if ($mt == 2 || $mt == 5 || $mt == 6) {
		$ausgabe .= tr(td(2, "head", "Nährstoffe"));
		$mr = explode("||", $inhalte["ress"]);
		for ($i = 1; $i <= 5; $i++) if ($mr[($i-1)] > 0) $ausgabe .= tr(td(0, "left", num2typ($i)) . td(0, "right", format($mr[($i-1)])));
		/* if ($inhalte["zusatz"] > 0) $ausgabe .= tr(td(0, "left", "Ostereier") . td(0, "right", $inhalte["zusatz"])); */
	}
	if ($mt != 5 && $inhalte["endzeit"] > time()) {
		if ($mt != 4 || ($mt == 4 && $inhalte["zusatz"] >= 1 && $inhalte["zusatz"] <= 5)) {
			$ausgabe .= tr(td(2, "center", "<hr/>"));
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&aktion=info&mid=" . $inhalte["id"] . "&abbr=1&a=$mt", "Mission abbrechen")));
		}
	}
} else {
	$ausgabe .= tr(td(2, "center", "<font color=\"red\">Ungültige Mission</font>"));
}

?>