<?php

$ausgabe .= table(600, "bg");

if ($inhalte_s["urlaub"] > time()) $ausgabe .= tr(td(2, "center", "Im Urlaubsmodus kann keine Evolution gestartet oder abgebrochen werden."));

// Evolutionen auflisten
if ($inhalte_b["konst8"] > 0) {
	$result_akt = mysql_query("SELECT id,aktion,endzeit FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='forsch'");
	$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
	for ($i = 1; $i <= 8; $i++) {
		if (($i == 1 && $inhalte_b["konst8"] > 0) || ($i == 2 && $inhalte_b["konst8"] > 4) || ($i == 3 && $inhalte_b["konst8"] > 14 && $inhalte_s["forsch1"] > 9 && $inhalte_s["forsch2"] > 9) || ($i == 4 && $inhalte_b["konst8"] > 3 && $inhalte_s["forsch1"] > 0) || ($i == 5 && $inhalte_b["konst8"] > 2) || ($i == 6 && $inhalte_b["konst8"] > 0) || ($i == 7 && $inhalte_b["konst8"] > 1) || ($i == 8 && $inhalte_b["konst8"] > 3 && $inhalte_b["konst15"] > 0)) {
			$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer,bezeichnung FROM genesis_infos WHERE typ='forsch$i'");
			$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
			$bez = $inhalte_ress["bezeichnung"];

			/* START: Einsteigerhilfe */
			$result_max = mysql_query("SELECT MAX(forsch$i) as stufe FROM genesis_spieler");
			$inhalte_max = mysql_fetch_array($result_max, MYSQL_ASSOC);
			$reduce = 1;
			if ($inhalte_max["stufe"] >= ($inhalte_s["forsch$i"]+6)) $reduce = 0.8;
			if ($inhalte_max["stufe"] >= ($inhalte_s["forsch$i"]+12)) $reduce = 0.6;
			/* END: Einsteigerhilfe */

			for ($k = 1; $k <= 4; $k++) $ress[$k] = round(kostevo($inhalte_ress["ress$k"], $inhalte_s["forsch$i"]) * $reduce);

			$rz = array();
			if ($inhalte_b["ress1"] < $ress[1] || $inhalte_b["ress2"] < $ress[2] || $inhalte_b["ress3"] < $ress[3] || $inhalte_b["ress4"] < $ress[4]) {
				for ($k = 1; $k <= 4; $k++) {
					$result1 = mysql_query("SELECT wert1 FROM genesis_infos WHERE typ='konst" . ($k + 1) . "'");
					$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					if ($inhalte_b["ress$k"] < $ress[$k]) array_push($rz, round(($ress[$k] - $inhalte_b["ress$k"]) / ressprod($inhalte1["wert1"], $inhalte_b["konst" . ($k + 1)]) * 60, 0) * 60);
					unset($result1, $inhalte1);
				}
				rsort ($rz);
				$class = "nein";
			} else {
				$class = "ja";
			}

			if ($inhalte_akt) {
				if ($i == $inhalte_akt["aktion"]) {
					$dauer = $inhalte_akt["endzeit"] - time();
					include "dauer.inc.php";
					mt_srand(microtime() * 1000000);
					$tid = intval(mt_rand(1111111, 9999999));
					$outa = "Erlerne Stufe " . ($inhalte_s["forsch$i"] + 1) . "<br><font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s<br>";
					$outa .= hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$i&s=a", "Abbrechen");
					$outa .= "</font><script language=\"JavaScript\">init_countdown ('$tid', $dauer, 'Beendet', '<br/>";
					$outa .= hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$i&s=a", "Abbrechen");
					$outa .= "', 'game.php?id=$id&b=$b&nav=$nav');</script>";
				} else {
					$outa = "-";
				}
			} else {
				if ($class == "nein") {
					$outa = "Erlernung mˆglich ";
					$diffzeit = time() + $rz[0] - (time() - $inhalte_b["resszeit"]) + 600;
					$diffday = bcdiv(($diffzeit - mktime(0, 0, 0, date("m", time()), date("d", time()) + 1, date("Y", time()))) + 86400, 86400);
					if ($diffday == 0) $outa .= "heute";
					if ($diffday == 1) $outa .= "morgen";
					if ($diffday > 1) $outa .= "am " . date("d", time() + ($diffday * 86400)) . ".";
					if ($error[$i]) {
						$outa = $error[$i] . "<br/>" . $outa . "<br/>um " . date("H:i", $diffzeit) . "Uhr";
					} else {
						$outa .= " um " . date("H:i", $diffzeit) . "Uhr";
						$outa = "<a class=\"$class\" title=\"$outa\" href=\"game.php?id=$id&b=$b&nav=$nav&w=$i\">Stufe " . ($inhalte_s["forsch$i"] + 1) . " erlernen</a>";
					}
				} else {
					$outa = hlink($class, "game.php?id=$id&b=$b&nav=$nav&w=$i", "Stufe " . ($inhalte_s["forsch$i"] + 1) . " erlernen") . $error[$i];
				}
			}

			$dauer = round(dauerevo($inhalte_ress["dauer"], $inhalte_s["forsch$i"], $inhalte_b["konst8"]) * $reduce);
			include "dauer.inc.php";

			if ($inhalte_s["layout"] == 0) {
				$ausgabe .= tr(td(3, "head", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch$i", "$bez") . " - Stufe " . $inhalte_s["forsch$i"]));
				$outb = "";
				for ($k = 1; $k <= 4; $k++) {
					if ($ress[$k] > 0) {
						$outb .= num2typ($k) . ": ";
						if ($ress[$k] <= $inhalte_b["ress$k"]) {
							$outb .= "<font class=\"ja\">" . format($ress[$k]) . "</font>";
						} else {
							$outb .= "<font class=\"nein\">" . format($ress[$k]) . "</font>";
						}
						$outb .= "<br/>\n";
					}
				}
				$outb .= "<br>Evolutionsdauer: $h:$m:$s ($zeitpunkt2)";
				$ausgabe .= tr(td(0, "left", "<img src=\"bilder/forsch" . $i . "_klein.jpg\" height=80 width=80>") . td(0, "left", $outb) . td(0, "center", $outa));
			} else {
				$ausgabe .= tr(td(2, "head", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch$i", "$bez") . " - Stufe " . $inhalte_s["forsch$i"]));
				$outb = "Adenin: " . format($ress[1]) . " Thymin: " . format($ress[2]) . " Guanin: " . format($ress[3]) . " Cytosin: " . format($ress[4]) . "<br>Entwicklungszeit: $h:$m:$s ($zeitpunkt2)";
				$ausgabe .= tr(td(0, "left", $outb) . td(0, "center", $outa));
			}
			unset($rz, $result_ress, $inhalte_ress, $a, $t, $g, $c, $outb, $outa);
		}
	}
	unset ($result_akt, $inhalte_akt);
} else {
	$ausgabe .= tr(td(0, "head", "Evolution"));
	$ausgabe .= tr(td(0, "center", "Dein Neogen ist nicht weit genug entwickelt, um solch komplexe Prozesse zu erlernen."));
	$ausgabe .= tr(td(0, "center", "Bitte entwickle das Groﬂhirn."));
}
$ausgabe .= "</table>\n";

?>