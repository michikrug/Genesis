<?php

$zeit = time();

$ausgabe .= table(610, "");
$ausgabe .= "<tr>\n\t<td valign=\"top\" class=\"ueb\">\n";

// Übersicht Tabelle
$ausgabe .= table(310, "bg");
$ausgabe .= tr(td(2, "head", "<a class=\"nc\" href=\"#\" onClick=\"showhide('neo')\">Neogen - " . $inhalte_b["bname"] . "</a>"));

$result = mysql_query("SELECT max(id) as id FROM gen_news");
$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
if ($inhalte["id"] > $inhalte_s["lastnews"] && $inhalte_s["shownew"] == 1) $ausgabe .= tr(td(2, "center", "<img src=\"images/blink.gif\"> " . hlink("new", "index.php?nav=home&news_id=" . $inhalte["id"], "Neue Genesis-News vorhanden")));
if ($inhalte_s["ereignisse"] > 0) $ausgabe .= tr(td(2, "center", "<img src=\"images/blink.gif\"> " . hlink("", "game.php?id=$id&b=$b&nav=nachrichten&w=1", "Neue Ereignisse vorhanden (". $inhalte_s["ereignisse"] .")")));
if ($inhalte_s["nachrichten"] > 0) $ausgabe .= tr(td(2, "center", "<img src=\"images/blink.gif\"> " . hlink("", "game.php?id=$id&b=$b&nav=nachrichten&w=3", "Neue Nachrichten vorhanden (". $inhalte_s["nachrichten"] .")")));

$dauer = 0;
include("dauer.inc.php");
$out = "<font id=\"uhr\" title=\"$zeitpunkt\">$zeitpunkt</font><script type=\"text/javascript\">init_countdown ('uhr', '" . time() . "', '', '', '');</script>";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Systemzeit") . td(0, "right", $out) . "\n</tr>\n";

$dauer = $inhalte_b["resszeit"] + 300 - time();
include("dauer.inc.php");
mt_srand(microtime() * 1000000);
$tid = intval(mt_rand(1111111, 9999999));
$out = "<font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s</font><script type=\"text/javascript\">init_countdown ('$tid', $dauer, 'wird übertragen', '', 'game.php?id=$id&b=$b&nav=$nav');</script>";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Nährstofflieferung in") . td(0, "right", $out) . "\n</tr>\n";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Koordinaten") . td(0, "right", $inhalte_s["basis$b"]) . "\n</tr>\n";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Ausbaupunkte") . td(0, "right", format($inhalte_b["punkte"])) . "\n</tr>\n";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Evolutionspunkte") . td(0, "right", format($inhalte_s["punktef"])) . "\n</tr>\n";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Zellpunkte") . td(0, "right", format($inhalte_s["punktem"])) . "\n</tr>\n";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Kampfpunkte") . td(0, "right", format($inhalte_s["kampfpkt"])) . "\n</tr>\n";
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Gesamtpunkte") . td(0, "right", format($inhalte_s["punkte"])) . "\n</tr>\n";
$radar = round(pow($inhalte_s["forsch5"], 1.2) * 300 / 60, 0) * 60 + round(pow($inhalte_b["konst16"], 1.2) * 180 / 60, 0) * 60 + 300;
$radar2 = round(pow($inhalte_s["forsch5"], 1.2) * 300 / 60, 0) * 60 + round(pow($inhalte_b2["konst16"], 1.2) * 180 / 60, 0) * 60 + 300;
$dauer = $radar;
include("dauer.inc.php");
$ausgabe .= "\t<tr name=\"neo\" id=\"neo\">\n" . td(0, "left", "Sensor-Reichweite") . td(0, "right", "$h:$m:$s") . "\n</tr>\n";

if ($inhalte_s["layout"] == 0 && $inhalte_s["showava"] != 2) {
	$ausgabe .= "\n</table><br/>\n";
	$ausgabe .= table(300, "bg");
	if ($inhalte_s["showava"] == 0) {
		$ausgabe .= tr(td(2, "center", "<img src=\"bilder/neogen" . $inhalte_b["bild"] . ".jpg\" height=\"80\" width=\"80\">"));
	} elseif ($inhalte_s["showava"] == 1 && $inhalte_s["avatar"] != "") {
		$Picturedata = getimagesize($inhalte_s["avatar"]);
		$Width = $Picturedata[0];
		$Height = $Picturedata[1];
		$Scaling = $Width / $Height;
		$Width = round(100 * $Scaling, 0);
		$ausgabe .= tr(td(2, "center", "<img src=\"" . $inhalte_s["avatar"] . "\" height=\"100\" width=\"$Width\">"));
	}
}

$ausgabe .= "</table>\n\t</td>\n\t<td valign=\"top\" class=\"ueb\">\n";

// Exo-Zellen Tabelle
$ausgabe .= table(300, "bg");
$ausgabe .= tr(td(2, "head", "<a class=\"nc\" href=\"#\" onClick=\"showhide('exos')\">Exo-Zellen</a>"));

$inhalte_v = array("prod1" => 0,"prod2" => 0,"prod3" => 0,"prod4" => 0,"prod5" => 0,"prod6" => 0,"prod7" => 0,"prod8" => 0);
$inhalte_m = array("prod1" => 0,"prod2" => 0,"prod3" => 0,"prod4" => 0,"prod5" => 0,"prod6" => 0,"prod7" => 0,"prod8" => 0);

$att = 0;
$vert = 0;

$result_a = mysql_query("SELECT basis2,aktion,einheiten,zusatz FROM genesis_aktionen WHERE typ='miss' AND (basis1='" . $inhalte_s["basis$b"] . "' or (basis2='" . $inhalte_s["basis$b"] . "' AND aktion='4' AND zusatz>5))");
while ($inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC)) {
	$me = explode("||", $inhalte_a["einheiten"]);
	for ($c = 1; $c <= 8; $c++) {
		$inhalte_m["prod$c"] += $me[$c-1];
		if ($inhalte_a["basis2"] == $b && $inhalte_a["aktion"] == 4 && $inhalte_a["zusatz"] > 5) $inhalte_v["prod$c"] += $me[$c-1];
	}
	unset ($me, $c);
}
for ($i = 1; $i <= 8; $i++) {
	if ($inhalte_b["prod$i"] > 0 || $inhalte_m["prod$i"] > 0) {
		$resulti1 = mysql_query("SELECT bezeichnung,wert3,wert4 FROM genesis_infos WHERE typ='prod$i'");
		$inhaltei1 = mysql_fetch_array($resulti1, MYSQL_ASSOC);
		$att += angr("prod$i", $inhaltei1["wert3"], $inhalte_s["forsch1"], $inhalte_s["forsch3"]) * $inhalte_b["prod$i"];
		$vert += vert("prod$i", $inhaltei1["wert4"], $inhalte_s["forsch4"], $inhalte_s["forsch3"]) * $inhalte_b["prod$i"];
		$out = format($inhalte_b["prod$i"]);
		if ($inhalte_m["prod$i"]) $out .= " (" . format($inhalte_b["prod$i"] + $inhalte_m["prod$i"]) . ")";
		$ausgabe .= "	<tr name=\"exos\" id=\"exos\">\n" . td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod$i", $inhaltei1["bezeichnung"])) . td(0, "right", $out) . "\n</tr>\n";
	}
}
$ausgabe .= "\t<tr name=\"exos\" id=\"exos\">\n" . td(0, "left", "Angriffs-/Vert.-wert") . td(0, "right", format($att) . "/" . format($vert)) . "\n</tr>\n";

$ausgabe .= "\n</table><br/>\n";

$ausgabe .= table(300, "bg");
$ausgabe .= tr(td(2, "head", "<a class=\"nc\" href=\"#\" onClick=\"showhide('vert')\">Verteidigung</a>"));

$att = angr_immu($inhalte_s["forsch1"], $inhalte_b["konst15"]);
$vert = vert_immu($inhalte_s["forsch4"], $inhalte_b["konst15"]);

for ($i = 1; $i <= 3; $i++) {
	if ($inhalte_b["vert$i"] > 0) {
		$resulti1 = mysql_query("SELECT bezeichnung,wert3,wert4 FROM genesis_infos WHERE typ='vert$i'");
		$inhaltei1 = mysql_fetch_array($resulti1, MYSQL_ASSOC);
		$att += angr("vert$i", $inhaltei1["wert3"], $inhalte_s["forsch1"], $inhalte_s["forsch3"]) * $inhalte_b["vert$i"];
		$vert += vert("vert$i", $inhaltei1["wert4"], $inhalte_s["forsch4"], $inhalte_s["forsch3"]) * $inhalte_b["vert$i"];
		$ausgabe .= "\t<tr name=\"vert\" id=\"vert\">\n" . td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=vert$i", $inhaltei1["bezeichnung"])) . td(0, "right", format($inhalte_b["vert$i"])) . "\n</tr>\n";
	}
}

for ($i = 1; $i <= 8; $i++) {
	if ($inhalte_v["prod$i"] > 0) {
		$resulti1 = mysql_query("SELECT bezeichnung,wert3,wert4 FROM genesis_infos WHERE typ='prod$i'");
		$inhaltei1 = mysql_fetch_array($resulti1, MYSQL_ASSOC);
		$att += angr("prod$i", $inhaltei1["wert3"], $inhalte_s["forsch1"], $inhalte_s["forsch3"]) * $inhalte_v["prod$i"];
		$vert += vert("prod$i", $inhaltei1["wert4"], $inhalte_s["forsch4"], $inhalte_s["forsch3"]) * $inhalte_v["prod$i"];
		$ausgabe .= "\t<tr name=\"vert\" id=\"vert\">\n" . td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod$i", $inhaltei1["bezeichnung"])) . td(0, "right", format($inhalte_v["prod$i"])) . "\n</tr>\n";
	}
}
$ausgabe .= "\t<tr name=\"vert\" id=\"vert\">\n" . td(0, "left", "Angriffs-/Vert.-wert") . td(0, "right", format($att) . "/" . format($vert)) . "\n</tr>\n";

$ausgabe .= "\n\t</table>\n\t</td>\n\t</tr>\n\t</table>\n\t<br/>\n";

// Aktionen Tabelle
$ausgabe .= table(610, "bg");
$ausgabe .= tr(td(4, "head", "<a class=\"nc\" href=\"#\" onClick=\"showhide('aktionen')\">Aktionen</a>"));

$zeit = time();
$i = 0;
$dauerh = array(0,0,0,0,0);
$outh = array("", "", "", "", "");

$result = mysql_query("SELECT * FROM genesis_handel WHERE (sucher='$sid' OR bieter='$sid') AND zeit>'$zeit' ORDER BY zeit");
while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$i++;
	$dauer = $inhalte["zeit"] - $zeit;
	include "dauer.inc.php";
	mt_srand(microtime() * 1000000);
	$tid = intval(mt_rand(1111111, 9999999));
	$outa = "<font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s</font><script type=\"text/javascript\">init_countdown ('$tid', $dauer, 'Beendet', '', '');</script>";
	if ($inhalte["bieter"] == 0) {
		$outb = "Es wurde dir noch nichts für " . format($inhalte["anz_geb"]) . " " . num2typ($inhalte["typ_geb"]) . " geboten";
	} elseif ($inhalte["bieter"] != $sid) {
		$outb = "Es wird dir " . format($inhalte["anz_such"]) . " " . num2typ($inhalte["typ_such"]) . " für " . format($inhalte["anz_geb"]) . " " . num2typ($inhalte["typ_geb"]) . " geboten";
	} else {
		$outb = "Du hast " . format($inhalte["anz_such"]) . " " . num2typ($inhalte["typ_such"]) . " für " . format($inhalte["anz_geb"]) . " " . num2typ($inhalte["typ_geb"]) . " geboten";
	}
	$dauerh[$i] = $dauer;
	$outh[$i] = tr(td(0, "hand", "&nbsp;") . td(0, "left", $outa)  . td(0, "left", "") . td(0, "left", $outb));
	unset($outb, $outa, $dauer);
}
unset($result, $inhalte);

if ($inhalte_s["basis2"]) {
	$result = mysql_query("SELECT * FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis1"] . "' OR basis2='" . $inhalte_s["basis1"] . "' OR basis1='" . $inhalte_s["basis2"] . "' OR basis2='" . $inhalte_s["basis2"] . "' ORDER BY endzeit");
} else {
	$result = mysql_query("SELECT * FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis1"] . "' OR basis2='" . $inhalte_s["basis1"] . "' ORDER BY endzeit");
}

mt_srand(microtime() * 1000000);
while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$dauer = $inhalte["endzeit"] - time();
	include "dauer.inc.php";
	$tid = intval(mt_rand(1111111, 9999999));
	$i = $inhalte["aktion"];
	if ($inhalte["typ"] != "miss") {
		$result2 = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='" . $inhalte["typ"] . "$i'");
		$inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		$bez = $inhalte2["bezeichnung"];
	}
	$bas = explode(":",$inhalte["basis1"]);
	if ($inhalte_s["basis2"]) {
		$zus = " (". $inhalte["basis1"] .")";
	} else {
		$zus = "";
	}
	$bn = ($inhalte["basis1"] == $inhalte_s["basis2"]) ? 2 : 1;
	$outa = "<font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s</font><script type=\"text/javascript\">init_countdown ('$tid', $dauer, 'Beendet', '', 'game.php?id=$id&b=$b&nav=$nav');</script>";
	switch ($inhalte["typ"]) {
		case "konst":
			$outb = "Entwicklung von ";
			$resultb = mysql_query("SELECT konst$i FROM genesis_basen WHERE koordx='". $bas[0] ."' and koordy='". $bas[1] ."' and koordz='". $bas[2] ."'");
			$inhalteb = mysql_fetch_array($resultb, MYSQL_ASSOC);
			$stufe = $inhalteb["konst$i"] + 1;
			$outb .= hlink("nc", "game.php?id=$id&b=$bn&nav=info&t=konst$i", $bez) . " auf Stufe $stufe". $zus;
			$cla = "bau";
			break;
		case "forsch":
			$outb = "Erlernung von ";
			$stufe = $inhalte_s["forsch$i"] + 1;
			$outb .= hlink("nc", "game.php?id=$id&b=$bn&nav=info&t=forsch$i", $bez) . " auf Stufe $stufe";
			$cla = "evo";
			break;
		case "prod":
			$outb = "Produktion von ";
			$outb .= hlink("nc", "game.php?id=$id&b=$bn&nav=info&t=prod$i", $bez) . $zus;
			$cla = "prod";
			break;
		case "vert":
			$outb = "Entwicklung von ";
			$outb .= hlink("nc", "game.php?id=$id&b=$bn&nav=info&t=vert$i", $bez) . $zus;
			$cla = "anti";
			break;
		case "miss":
			if (
				(($inhalte_s["basis1"] == $inhalte["basis2"] || $inhalte_s["basis2"] == $inhalte["basis2"])	&& $inhalte["aktion"] == 4 && $inhalte["zusatz"] > 5)
				||
				($inhalte["endzeit"] - $radar < time() && $inhalte_s["basis$b"] != $inhalte["basis1"] && $inhalte["aktion"] != 3)
				||
				($b == 1 && $inhalte["endzeit"] - $radar2 < time() && $inhalte_s["basis2"] != $inhalte["basis1"] && $inhalte["aktion"] != 3)
				||
				($b == 2 && $inhalte["endzeit"] - $radar2 < time() && $inhalte_s["basis1"] != $inhalte["basis1"] && $inhalte["aktion"] != 3)
				||
				($inhalte_s["basis1"] == $inhalte["basis1"])
				||
				($inhalte_s["basis2"] == $inhalte["basis1"])
			) {
				$mk1 = explode(":", $inhalte["basis1"]);
				$mkx1 = $mk1[0];
				$mky1 = $mk1[1];
				$mkz1 = $mk1[2];
				$mk2 = explode(":", $inhalte["basis2"]);
				$mkx2 = $mk2[0];
				$mky2 = $mk2[1];
				$mkz2 = $mk2[2];
				if ($i == 1) {
					$mt = "Angriff";
					if (($inhalte_s["basis1"] != $inhalte["basis1"] && $inhalte_s["basis2"] != $inhalte["basis1"])) {
						$cla = "att2";
					} else {
						$cla = "att1";
						if ($inhalte["zusatz"] == 1) $mt = "gem. Angriff";
					}
				}
				if ($i == 2) {
					if ($inhalte["zusatz"] == 1) {
						$mt = "Stationierung";
						$cla = "stao";
					} else {
						$mt = "Transport";
						$cla = "trans";
					}
				}
				if ($i == 3) {
					$mt = "Spionage";
					$cla = "spio";
				}
				if ($i == 4) {
					$mt = "Verteidigung";
					$cla = "vert";
				}
				if ($i == 5) {
					$mt = "Rückkehr";
					$cla = "back";
				}
				if ($i == 6) {
					$mt = "Zellteilung";
					$cla = "keim";
				}
				if ($i == 7) {
					$mt = "Eiersuche";
					$cla = "keim";
				}
				$result1 = mysql_query("SELECT name,bname FROM genesis_basen WHERE koordx='$mkx1' AND koordy='$mky1' AND koordz='$mkz1'");
				$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
				$result2 = mysql_query("SELECT name,bname FROM genesis_basen WHERE koordx='$mkx2' AND koordy='$mky2' AND koordz='$mkz2'");
				$inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
				$result3 = mysql_query("SELECT id FROM genesis_spieler WHERE name='" . $inhalte1["name"] . "'");
				$inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);
				$result4 = mysql_query("SELECT id FROM genesis_spieler WHERE name='" . $inhalte2["name"] . "'");
				$inhalte4 = mysql_fetch_array($result4, MYSQL_ASSOC);
				if (!$inhalte1["name"]) {
					$inhalte1["name"] = "unbesetzt";
					$inhalte1["bname"] = "unbekannt";
				}
				if (!$inhalte2["name"]) {
					$inhalte2["name"] = "unbesetzt";
					$inhalte2["bname"] = "unbekannt";
				}
				// Rückkehr
				if ($i == 5 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"])) {
					$outb = hlink("", "game.php?id=$id&b=$b&nav=mission&aktion=info&mid=" . $inhalte["id"], "Mission");
					$outb .= " kehrt von ";
					$outb .= hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte4["id"] . "&k=" . $inhalte["basis2"], $inhalte2["name"]) . " (" . $inhalte["basis2"]. ")";
					$outb .= " zurück nach ";
					$outb .= $inhalte1["bname"] . " (" . $inhalte["basis1"]. ")";
					// Hinflug
				} elseif (($i == 4 && $inhalte["zusatz"] >= 1 && $inhalte["zusatz"] <= 5 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"])) || ($i != 4 && $i != 5 && $inhalte["zusatz"] >= 0 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"]))) {
					$outb = hlink("", "game.php?id=$id&b=$b&nav=mission&aktion=info&mid=" . $inhalte["id"], $mt);
					$outb .= " von ";
					$outb .= $inhalte1["bname"] . " (" . $inhalte["basis1"]. ")";
					$outb .= " erreicht ";
					$outb .= hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte4["id"]. "&k=" . $inhalte["basis2"], $inhalte2["name"]) . " (" . $inhalte["basis2"]. ")";
					// Eigene Verteidigung
				} elseif ($i == 4 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"])) {
					$outb = hlink("", "game.php?id=$id&b=$b&nav=mission&aktion=info&mid=" . $inhalte["id"], "Verteidigung");
					$outb .= " von ";
					$outb .= $inhalte1["bname"] . " (" . $inhalte["basis1"]. ")";
					$outb .= " bei ";
					$outb .= hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte4["id"] . "&k=" . $inhalte["basis2"], $inhalte2["name"]) . " (" . $inhalte["basis2"]. ")";
					// Fremde Verteidigung
				} elseif ($i == 4 && ($inhalte_s["basis1"] == $inhalte["basis2"] || $inhalte_s["basis2"] == $inhalte["basis2"]) && $inhalte["zusatz"] > 5) {
					$outb = hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte3["id"] . "&k=" . $inhalte["basis1"], $inhalte1["name"]) . " (" . $inhalte["basis1"]. ")";
					$outb .= " verteidigt ";
					$outb .= $inhalte2["bname"] . " (" . $inhalte["basis2"]. ")";
					// Fremde Mission
				} elseif ($i != 5 && ($inhalte_s["basis1"] != $inhalte["basis1"] && $inhalte_s["basis2"] != $inhalte["basis1"])) {
					$outb = "$mt von ";
					$outb .= hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte3["id"] . "&k=" . $inhalte["basis1"], $inhalte1["name"]) . " (" . $inhalte["basis1"]. ")";
					$outb .= " erreicht ";
					$outb .= $inhalte2["bname"] . " (" . $inhalte["basis2"]. ")";
				}
			}
			break;
	}
	if ($outb != "" && $outa != "") {
		for ($i = 1; $i <= 4; $i++) {
			if ($dauerh[$i] < $dauer && $outh[$i] != "") {
				$ausgabe .= $outh[$i];
				$outh[$i] = NULL;
			}
		}
		$ausgabe .= "\n<tr name=\"aktionen\" id=\"aktionen\">\n" . td(0, $cla, "&nbsp;") . td(0, "left", $outa) . td(0, "left", "") . td(0, "left", $outb) . "\n</tr>\n";
	}
	unset($outb, $outa);
}

for ($i = 1; $i <= 4; $i++) {
	if ($outh[$i] != "") {
		$ausgabe .= $outh[$i];
		$outh[$i] = NULL;
	}
}

$ausgabe .= "</table>\n";

?>