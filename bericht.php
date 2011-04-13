<?php

include_once "ctracker.php";

include_once "header.inc.php";

require_once "sicher/config.inc.php";
$connection = @mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$connection) die("Es konnte keine Datenbankverbindung hergestellt werden.");
mysql_select_db($mysql_db, $connection);

include_once "functions.inc.php";

$ausgabe .= table(809, "main");
$ausgabe .= "<tr><td valign=\"top\" align=\"center\"><br/>\n";
$ausgabe .= table(550, "bg");

unset($gesamtkp, $anzdeff, $anzatt, $gesamtatt, $gesamtattv, $gesamtdeff, $gesamtdeffv, $gesamtress);

if ($id != "") {
	$result = mysql_query("SELECT * FROM genesis_berichte WHERE id='$id'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($inhalte["typ"] == 1) $ausgabe .= tr(td(4, "head", "Angriffsbericht (<a href=# onclick=\"shown('nh')\">Einzeln</a> / <a href=# onclick=\"shown('nt')\">Gesamt</a>)"));
		if ($inhalte["typ"] == 2) $ausgabe .= tr(td(4, "head", "Spionagebericht (<a href=# onclick=\"shown('nh')\">Einzeln</a> / <a href=# onclick=\"shown('nt')\">Gesamt</a>)"));
		$ausgabe .= tr(td(2, "left", "Zeit") . td(2, "right", date("H:i:s (d.m.Y)", $inhalte["zeit"])));
		$ausgabe .= tr(td(2, "left", "Koordinaten") . td(2, "right", $inhalte["koords"]));
		$ausgabe .= tr(td(4, "center", "&nbsp;"));
		$ausgabe .= "</table>\n<table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bg\" name=\"nh\" id=\"nh\">\n";

		$filter = preg_split('//', $inhalte["filter"], -1, PREG_SPLIT_NO_EMPTY);

		$result2 = mysql_query("SELECT * FROM genesis_att WHERE id='$id'");
		while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$ausgabe .= tr(td(4, "head", "Angreifer - " . $inhalte2["alli"] . " " . $inhalte2["name"] . " (" . $inhalte2["koords"] . ")"));
			$ausgabe .= tr(td(0, "left", "<b>Typ</b>") . td(0, "right", "<b>Gesamt</b>") . td(0, "right", "<b>Verlust</b>") . td(0, "right", "<b>in %</b>"));
			for ($i = 1; $i <= 8; $i++) {
				if ($inhalte2["prod$i"] > 0) {
					$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$bez = $inhalte1["bezeichnung"];
					$proz = round($inhalte2["prodv$i"] / $inhalte2["prod$i"] * 100, 1);
					$ausgabe .= tr(td(0, "left", $bez) . td(0, "right", format($inhalte2["prod$i"])) . td(0, "right", format($inhalte2["prodv$i"])) . td(0, "right", $proz . "%"));
					$gesamtatt[$i] += $inhalte2["prod$i"];
					$gesamtattv[$i] += $inhalte2["prodv$i"];
				}
			}
			if ($inhalte["typ"] == 1) {
				if ($inhalte2["ress1"] > 0 || $inhalte2["ress2"] > 0 || $inhalte2["ress3"] > 0 || $inhalte2["ress4"] > 0 || $inhalte2["ress5"] > 0) {
					$ausgabe .= tr(td(4, "head", "erbeutete Nährstoffe"));
					for ($k = 1; $k <= 5; $k++) {
						$ausgabe .= tr(td(2, "left", num2typ($k)) . td(2, "right", format($inhalte2["ress$k"])));
						$gesamtress[$k] += $inhalte2["ress$k"];
					}
				}
				$ausgabe .= tr(td(4, "head", "Kampfpunkte"));
				$ausgabe .= tr(td(2, "left", "Angreifer") . td(2, "right", format($inhalte2["kp"])));
				$gesamtkp["att"] += $inhalte2["kp"];
				$ausgabe .= tr(td(4, "center", "&nbsp;"));
			}
		}
		$ausgabe .= tr(td(4, "center", "&nbsp;"));
		$ausgabe .= "</table>\n<table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bg\" name=\"nh\" id=\"nh\">\n";

		$result2 = mysql_query("SELECT * FROM genesis_deff WHERE id='$id'");
		while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$ausgabe .= tr(td(4, "head", "Verteidiger - " . $inhalte2["alli"] . " " . $inhalte2["name"] . " (" . $inhalte2["koords"] . ")"));

			if ($inhalte["typ"] == 1) {
				$ausgabe .= tr(td(0, "left", "<b>Typ</b>") . td(0, "right", "<b>Gesamt</b>") . td(0, "right", "<b>Verlust</b>") . td(0, "right", "<b>in %</b>"));

				for ($i = 1; $i <= 8; $i++) {
					if ($inhalte2["prod$i"] > 0) {
						$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
						$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
						$bez = $inhalte1["bezeichnung"];
						$proz = round($inhalte2["prodv$i"] / $inhalte2["prod$i"] * 100, 1);
						$ausgabe .= tr(td(0, "left", $bez) . td(0, "right", format($inhalte2["prod$i"])) . td(0, "right", format($inhalte2["prodv$i"])) . td(0, "right", $proz . "%"));
						$gesamtdeff[$i] += $inhalte2["prod$i"];
						$gesamtdeffv[$i] += $inhalte2["prodv$i"];
					}
				}
				for ($i = 1; $i <= 3; $i++) {
					if ($inhalte2["vert$i"] > 0) {
						$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='vert$i'");
						$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
						$bez = $inhalte1["bezeichnung"];
						$proz = round($inhalte2["vertv$i"] / $inhalte2["vert$i"] * 100, 1);
						$ausgabe .= tr(td(0, "left", $bez) . td(0, "right", format($inhalte2["vert$i"])) . td(0, "right", format($inhalte2["vertv$i"])) . td(0, "right", $proz . "%"));
						$gesamtdeff[$i + 8] += $inhalte2["vert$i"];
						$gesamtdeffv[$i + 8] += $inhalte2["vertv$i"];
					}
				}
				$ausgabe .= tr(td(4, "head", "Kampfpunkte"));
				$ausgabe .= tr(td(2, "left", "Verteidiger") . td(2, "right", format($inhalte2["kp"])));
				$ausgabe .= tr(td(4, "center", "&nbsp;"));
				$gesamtkp["deff"] += $inhalte2["kp"];
			}

			if ($inhalte["typ"] == 2) {
				$ausgabe .= tr(td(4, "head", "Verteidigung"));
				for ($i = 1; $i <= 8; $i++) {
					if ($inhalte2["prod$i"] > 0) {
						$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
						$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
						$bez = $inhalte1["bezeichnung"];
						if ($filter[$i-1] == 1) {
							$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", format($inhalte2["prod$i"])));
							$gesamtdeff[$i] += $inhalte2["prod$i"];
							$gesamtdeffv[$i] += $inhalte2["prodv$i"];
						} else {
							$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", "?"));
						}
					}
				}
				for ($i = 1; $i <= 3; $i++) {
					if ($inhalte2["vert$i"] > 0) {
						$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='vert$i'");
						$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
						$bez = $inhalte1["bezeichnung"];
						if ($filter[$i + 8-1] == 1) {
							$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", format($inhalte2["vert$i"])));
							$gesamtdeff[$i + 8] += $inhalte2["vert$i"];
							$gesamtdeffv[$i + 8] += $inhalte2["vertv$i"];
						} else {
							$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", "?"));
						}
					}
				}
			}
		}

		$ausgabe .= "</table>\n<table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bg\" name=\"nt\" id=\"nt\" style=\"display:none\">\n";

		$ausgabe .= tr(td(4, "head", "Angreifer - Gesamt"));
		$ausgabe .= tr(td(0, "left", "<b>Typ</b>") . td(0, "right", "<b>Gesamt</b>") . td(0, "right", "<b>Verlust</b>") . td(0, "right", "<b>in %</b>"));
		for ($i = 1; $i <= 8; $i++) {
			if ($gesamtatt[$i] > 0) {
				$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
				$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
				$bez = $inhalte1["bezeichnung"];
				$proz = round($gesamtattv[$i] / $gesamtatt[$i] * 100, 1);
				$ausgabe .= tr(td(0, "left", $bez) . td(0, "right", format($gesamtatt[$i])) . td(0, "right", format($gesamtattv[$i])) . td(0, "right", $proz . "%"));
			}
		}
		if ($inhalte["typ"] == 1) {
			$ausgabe .= tr(td(4, "head", "erbeutete Nährstoffe"));
			for ($k = 1; $k <= 5; $k++) $ausgabe .= tr(td(2, "left", num2typ($k)) . td(2, "right", format($gesamtress[$k])));
			$ausgabe .= tr(td(4, "head", "Kampfpunkte"));
			$ausgabe .= tr(td(2, "left", "Angreifer") . td(2, "right", format($gesamtkp["att"])));
		}

		$ausgabe .= tr(td(4, "center", "&nbsp;"));
		$ausgabe .= "</table>\n<table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bg\" name=\"nt\" id=\"nt\" style=\"display:none\">\n";

		if ($inhalte["typ"] == 1) {
			$ausgabe .= tr(td(4, "head", "Verteidiger - Gesamt"));
			$ausgabe .= tr(td(0, "left", "<b>Typ</b>") . td(0, "right", "<b>Gesamt</b>") . td(0, "right", "<b>Verlust</b>") . td(0, "right", "<b>in %</b>"));
			for ($i = 1; $i <= 8; $i++) {
				if ($gesamtdeff[$i] > 0) {
					$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$bez = $inhalte1["bezeichnung"];
					$proz = round($gesamtdeffv[$i] / $gesamtdeff[$i] * 100, 1);
					$ausgabe .= tr(td(0, "left", $bez) . td(0, "right", format($gesamtdeff[$i])) . td(0, "right", format($gesamtdeffv[$i])) . td(0, "right", $proz . "%"));
				}
			}
			for ($i = 1; $i <= 3; $i++) {
				if ($gesamtdeff[$i + 8] > 0) {
					$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='vert$i'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$bez = $inhalte1["bezeichnung"];
					$proz = round($gesamtdeffv[$i + 8] / $gesamtdeff[$i + 8] * 100, 1);
					$ausgabe .= tr(td(0, "left", $bez) . td(0, "right", format($gesamtdeff[$i + 8])) . td(0, "right", format($gesamtdeffv[$i + 8])) . td(0, "right", $proz . "%"));
				}
			}
			$ausgabe .= tr(td(4, "head", "Kampfpunkte"));
			$ausgabe .= tr(td(2, "left", "Verteidiger") . td(2, "right", format($gesamtkp["deff"])));
		}
		if ($inhalte["typ"] == 2) {
			$ausgabe .= tr(td(4, "head", "Verteidiger - Gesamt"));
			$ausgabe .= tr(td(4, "head", "Verteidigung"));
			for ($i = 1; $i <= 8; $i++) {
				if ($gesamtdeff[$i] > 0) {
					$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$bez = $inhalte1["bezeichnung"];
					$proz = round($gesamtdeffv[$i] / $gesamtdeff[$i] * 100, 1);
					$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", format($gesamtdeff[$i])));
				}
			}
			for ($i = 1; $i <= 3; $i++) {
				if ($gesamtdeff[$i + 8] > 0) {
					$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='vert$i'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$bez = $inhalte1["bezeichnung"];
					$proz = round($gesamtdeffv[$i + 8] / $gesamtdeff[$i + 8] * 100, 1);
					$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", format($gesamtdeff[$i + 8])));
				}
			}
		}
		$ausgabe .= "</table>\n";

		if ($inhalte["typ"] == 2) {
			$ausgabe .= "\n<table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bg\">\n";

			$ausgabe .= tr(td(4, "head", "Ausbauten"));
			for ($i = 1; $i <= 17; $i++) {
				$anz = $inhalte["konst$i"];
				if ($anz > 0) {
					$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='konst$i'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$bez = $inhalte1["bezeichnung"];
					if ($filter[$i + 8 + 3 - 1] == 1) {
						$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", format($anz)));
					} else {
						$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", "?"));
					}
				}
			}
			$ausgabe .= tr(td(4, "head", "Evolution"));
			for ($i = 1; $i <= 8; $i++) {
				$anz = $inhalte["forsch$i"];
				if ($anz > 0) {
					$result1 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='forsch$i'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$bez = $inhalte1["bezeichnung"];
					if ($filter[$i + 8 + 3 + 17 - 1] == 1) {
						$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", format($anz)));
					} else {
						$ausgabe .= tr(td(2, "left", $bez) . td(2, "right", "?"));
					}
				}
			}

			$ausgabe .= tr(td(4, "head", "Nährstoffe"));
			$ausgabe .= tr(td(2, "left", "<b>Typ</b>") . td(0, "right", "<b>Lagerbestand</b>") . td(0, "right", "<b>Anzahl</b>"));

			for ($i = 1; $i <= 5; $i++) {
				eval("\$ressl = round(\$inhalte[\"ress$i\"] / resskap(\$inhalte[\"konst" . ($i + 8) . "\"]) * 100, 0);");
				eval("\$r = dechex(round(220-(\$ressl*1.5),0));");
				eval("if (strlen(\$r) < 2) { \$r = \"0\". \$r; }");
				eval("\$g = dechex(round(70+\$ressl*1.8,0)) .\"10\";");
				if ($filter[$i + 8 + 3 + 17 + 8 - 1] == 1) {
					$ausgabe .= tr(td(2, "left", num2typ($i)) . td(0, "right", "<font color=\"#$r$g\">$ressl %</font>") . td(0, "right", format($inhalte["ress$i"])));
				} else {
					$ausgabe .= tr(td(2, "left", num2typ($i)) . td(0, "right", "?") . td(0, "right", "?"));
				}
			}

			if ($inhalte["zusatz"] != "") $ausgabe .= tr(td(4, "center", $inhalte["zusatz"]));

			$ausgabe .= "
	</table>\n";
		}
	}
}
$ausgabe .= "\n\t</td></tr>\t\n<tr><td>&nbsp;</td></tr>\n</table>";

mysql_close($connection);

include "footer.inc.php";

$title = "Bericht";
if ($inhalte["typ"] == 1) $title = "Angriffsbericht (" . $inhalte["koords"] . ")";
if ($inhalte["typ"] == 2) $title = "Spionagebericht (" . $inhalte["koords"] . ")";
$ausgabe = str_replace("<title>Genesis</title>", "<title>Genesis@Squeeeze :: $title</title>", $ausgabe);

echo $ausgabe;

?>