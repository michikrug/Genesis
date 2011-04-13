<?php

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= table(600, "bg");

if ($inhalte_s["urlaub"] > time()) $ausgabe .= tr(td(2, "center", "Im Urlaubsmodus könen keine Anti-Körper entwickelt oder abgebrochen werden."));

if ($inhalte_b["konst15"] > 0) {
	$result_akt = mysql_query("SELECT startzeit,endzeit,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='vert'");
	$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
	$ein = explode("||", $inhalte_akt["einheiten"]);
	$anz = explode("||", $inhalte_akt["ress"]);
	$anzv = 0;
	for ($i = 0; $i < count($anz)-1; $i++) $anzv += $anz[$i];
	$maxanz = maxvert($inhalte_b["konst15"]) - $inhalte_b["vert1"] - $inhalte_b["vert2"] - $inhalte_b["vert3"] - $anzv;

	for ($i = 1; $i <= 3; $i++) {
		if (($i == 1 && $inhalte_b["konst15"] > 0 && $inhalte_s["forsch1"] > 0 && $inhalte_s["forsch4"] > 0 && $inhalte_s["forsch7"] > 2) || ($i == 2 && $inhalte_b["konst15"] > 5 && $inhalte_s["forsch1"] > 4 && $inhalte_s["forsch4"] > 6 && $inhalte_s["forsch7"] > 7) || ($i == 3 && $inhalte_b["konst15"] > 11 && $inhalte_s["forsch1"] > 9 && $inhalte_s["forsch4"] > 9 && $inhalte_s["forsch7"] > 14)) {
			$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer,bezeichnung FROM genesis_infos WHERE typ='vert$i'");
			$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
			$bez = $inhalte_ress["bezeichnung"];
			$vanz = array();
			for ($k = 1; $k <= 4; $k++) {
				$ress[$k] = $inhalte_ress["ress$k"];
				if ($ress[$k] > 0) {
					if ($inhalte_b["ress$k"] >= $ress[$k]) {
						array_push($vanz, bcdiv($inhalte_b["ress$k"], $ress[$k]));
					} else {
						array_push($vanz, 0);
					}
				}
			}
			sort($vanz);
			$vanz = $vanz[0];
			if ($vanz > $maxanz) $vanz = $maxanz;
			$outa = input("zahl", "vert$i", 0) . " (<a nohref onclick=\"fuell('vert$i','$vanz');\">$vanz</a>)";
			$dauer = dauervert($inhalte_ress["dauer"], $inhalte_b["konst15"]);
			include "dauer.inc.php";

			if ($inhalte_s["layout"] == 0) {
				$ausgabe .= tr(td(3, "head", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=vert$i", "$bez") . " - " . $inhalte_b["vert$i"] . " vorhanden"));
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
				$outb .= "<br>Entwicklungszeit: $h:$m:$s ($zeitpunkt2)";
				$ausgabe .= tr(td(0, "left", "<img src=\"bilder/vert" . $i . "_klein.jpg\" height=80 width=80>") . td(0, "left", $outb) . td(0, "center", $outa));
			} else {
				$ausgabe .= tr(td(3, "head", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=vert$i", "$bez") . " - " . $inhalte_b["vert$i"] . " vorhanden"));
				$outb = "Adenin: " . format($ress[1]) . " Thymin: " . format($ress[2]) . " Guanin: " . format($ress[3]) . " Cytosin: " . format($ress[4]) . "<br>Entwicklungszeit: $h:$m:$s";
				$ausgabe .= tr(td(0, "left", $outb) . td(0, "center", $outa));
			}

			unset ($result, $inhalte, $outa, $outb, $vanz);
		}
	}

	$ausgabe .= tr(td(3, "center", input("submit", "aktion", "Entwickeln")));
	$ausgabe .= tr(td(3, "center", "<hr/>"));

	$dauer = $inhalte_akt["startzeit"] - time();

	for ($i = 0; $i < count($ein)-1; $i++) {
		$result_ress = mysql_query("SELECT bezeichnung,dauer FROM genesis_infos WHERE typ='vert" . $ein[$i] . "'");
		$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
		$dauer += dauervert($inhalte_ress["dauer"], $inhalte_b["konst15"]) * $anz[$i];
		include "dauer.inc.php";
		if ($i == 0) {
			mt_srand(microtime() * 1000000);
			$tid = intval(mt_rand(1111111, 9999999));
			$outa = "<font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s</font> (";
			$outa .= hlink("", "game.php?id=$id&b=$b&nav=$nav&aktion=a&w=$i", "Abbrechen");
			$outa .= ")<script language=JavaScript>init_countdown ('$tid', $dauer, 'Beendet', '', 'game.php?id=$id&b=$b&nav=$nav');</script>";
		} else {
			$outa = "$h:$m:$s (";
			$outa .= hlink("", "game.php?id=$id&b=$b&nav=$nav&aktion=a&w=$i", "Abbrechen");
			$outa .= ")";
		}
		$bez = $inhalte_ress["bezeichnung"];
		if ($i > 0) $outb = hlink("", "game.php?id=$id&b=$b&nav=$nav&aktion=up&w=$i", "^^");
		$ausgabe .= tr(td(3, "center", $anz[$i] . " $bez $outa $outb"));
	}

	$ausgabe .= tr(td(3, "center", "<br/>Achtung! Bei Abbruch eines Postens werden 20% einbehalten."));
	$ausgabe .= tr(td(3, "center", "(" . ($inhalte_b["vert1"] + $inhalte_b["vert2"] + $inhalte_b["vert3"]) . " von " . maxvert($inhalte_b["konst15"]) . " möglichen Antikörpern)"));
	unset ($i, $maxanz, $anzv, $bez, $result_akt, $inhalte_akt, $anz, $ein, $result, $inhalte, $result_ress, $inhalte_ress, $dauer);
} else {
	$ausgabe .= tr(td(0, "head", "Verteidigung"));
	$ausgabe .= tr(td(0, "center", "Um Antikörper entwickeln zu können, benötigst du ein höheres Evolutionsstadium!"));
	$ausgabe .= tr(td(0, "center", "Bitte entwickle das Immunsystem."));
}

$ausgabe .= "</table>\n</form>\n";

?>