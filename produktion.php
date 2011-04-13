<?php

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= table(600, "bg");

if ($inhalte_s["urlaub"] > time()) $ausgabe .= tr(td(2, "center", "Im Urlaubsmodus könen keine Exo-Zellen produziert oder abgebrochen werden."));

if ($inhalte_b["konst7"] > 0) {
	for ($i = 1; $i <= 8; $i++) {
		if (($i == 1 && $inhalte_b["konst7"] > 0 && $inhalte_s["forsch1"] > 0 && $inhalte_s["forsch6"] > 0 && $inhalte_s["forsch7"] > 1) || ($i == 2 && $inhalte_b["konst7"] > 4 && $inhalte_s["forsch1"] > 2 && $inhalte_s["forsch2"] > 2 && $inhalte_s["forsch4"] > 3 && $inhalte_s["forsch6"] > 3 && $inhalte_s["forsch7"] > 5) || ($i == 3 && $inhalte_b["konst7"] > 9 && $inhalte_s["forsch1"] > 7 && $inhalte_s["forsch2"] > 9 && $inhalte_s["forsch4"] > 9 && $inhalte_s["forsch6"] > 7 && $inhalte_s["forsch7"] > 11) || ($i == 4 && $inhalte_b["konst7"] > 14 && $inhalte_s["forsch1"] > 14 && $inhalte_s["forsch2"] > 11 && $inhalte_s["forsch4"] > 9 && $inhalte_s["forsch6"] > 11 && $inhalte_s["forsch7"] > 15) || ($i == 5 && $inhalte_b["konst7"] > 19 && $inhalte_s["forsch1"] > 19 && $inhalte_s["forsch2"] > 17 && $inhalte_s["forsch3"] > 9 && $inhalte_s["forsch4"] > 14 && $inhalte_s["forsch6"] > 17 && $inhalte_s["forsch7"] > 19) || ($i == 6 && $inhalte_b["konst7"] > 0 && $inhalte_s["forsch4"] > 0 && $inhalte_s["forsch6"] > 0 && $inhalte_s["forsch7"] > 1 && $inhalte_s["forsch8"] > 0) || ($i == 7 && $inhalte_b["konst7"] > 1 && $inhalte_s["forsch1"] > 1 && $inhalte_s["forsch2"] > 1 && $inhalte_s["forsch6"] > 2 && $inhalte_s["forsch7"] > 1) || ($i == 8 && $inhalte_b["konst7"] > 10 && $inhalte_s["forsch3"] > 5 && $inhalte_s["forsch4"] > 7 && $inhalte_s["forsch6"] > 9 && $inhalte_s["forsch7"] > 9)) {
			$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer,bezeichnung FROM genesis_infos WHERE typ='prod$i'");
			$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
			$bez = $inhalte_ress["bezeichnung"];
			$panz = array();
			for ($k = 1; $k <= 4; $k++) {
				$ress[$k] = $inhalte_ress["ress$k"];
				if ($ress[$k] > 0) {
					if ($inhalte_b["ress$k"] >= $ress[$k]) {
						array_push($panz, bcdiv($inhalte_b["ress$k"], $ress[$k]));
					} else {
						array_push($panz, 0);
					}
				}
			}
			sort($panz);
			$panz = $panz[0];
			if ($i > 7 && $panz > 1) $panz = 1;
			if ($i < 8 || ($i == 8 && $inhalte_s["keimzelle"] == 0)) {
				$outa = input("zahl", "prod$i", 0) . " (<a nohref onclick=\"fuell('prod$i','$panz');\">$panz</a>)";
			} else {
				$outa = "-";
			}

			$dauer = dauerprod($inhalte_ress["dauer"], $inhalte_b["konst7"]);
			include "dauer.inc.php";

			if ($inhalte_s["layout"] == 0) {
				$ausgabe .= tr(td(3, "head", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod$i", "$bez") . " - " . $inhalte_b["prod$i"] . " vorhanden"));
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
				$outb .= "<br>Produktionszeit: $h:$m:$s ($zeitpunkt2)";
				$ausgabe .= tr(td(0, "left", "<img src=\"bilder/prod" . $i . "_klein.jpg\" height=80 width=80>") . td(0, "left", $outb) . td(0, "center", $outa));
			} else {
				$ausgabe .= tr(td(3, "head", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod$i", "$bez") . " - " . $inhalte_b["prod$i"] . " vorhanden"));
				$outb = "Adenin: " . format($ress[1]) . " Thymin: " . format($ress[2]) . " Guanin: " . format($ress[3]) . " Cytosin: " . format($ress[4]) . "<br>Produktionszeit: $h:$m:$s";
				$ausgabe .= tr(td(0, "left", $outb) . td(0, "center", $outa));
			}

			unset ($result, $inhalte, $outa, $outb, $anz, $panz);
		}
	}

	if ($inhalte_b["ress5"] == 0) {
		$ausgabe .= tr(td(3, "center", "<hr/>"));
		$ausgabe .= tr(td(3, "center", "Produktion aufgrund von ATP-Mangel eingestellt!"));
		$ausgabe .= tr(td(3, "center", "<hr/>"));
	} else {
		$ausgabe .= tr(td(3, "center", input("submit", "aktion", "Produzieren")));
		$ausgabe .= tr(td(3, "center", "<hr/>"));
	}

	$result_akt = mysql_query("SELECT startzeit,endzeit,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='prod'");
	$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
	$ein = explode("||", $inhalte_akt["einheiten"]);
	$anz = explode("||", $inhalte_akt["ress"]);
	$dauer = $inhalte_akt["endzeit"] - time();

	for ($i = 0; $i < count($ein)-1; $i++) {
		$result_ress = mysql_query("SELECT bezeichnung,dauer FROM genesis_infos WHERE typ='prod" . $ein[$i] . "'");
		$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
		$dauer += dauerprod($inhalte_ress["dauer"], $inhalte_b["konst7"]) * $anz[$i];
		if ($i == 0) $dauer -= dauerprod($inhalte_ress["dauer"], $inhalte_b["konst7"]);
		include "dauer.inc.php";
		if ($i == 0) {
			mt_srand(microtime() * 1000000);
			$tid = intval(mt_rand(1111111, 9999999));
			$outa = "<font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s</font> (";
			$outa .= hlink("", "game.php?id=$id&b=$b&nav=$nav&aktion=a&w=$i", "Abbrechen");
			$outa .= ")<script type=\"text/javascript\">init_countdown ('$tid', $dauer, 'Beendet', '', 'game.php?id=$id&b=$b&nav=$nav');</script>";
		} else {
			$outa = "$h:$m:$s (";
			$outa .= hlink("", "game.php?id=$id&b=$b&nav=$nav&aktion=a&w=$i", "Abbrechen");
			$outa .= ")";
		}
		$bez = $inhalte_ress["bezeichnung"];
		$outb = ($i > 0) ? hlink("", "game.php?id=$id&b=$b&nav=$nav&aktion=up&w=$i", "^^") : "";
		$ausgabe .= tr(td(3, "center", $anz[$i] . " $bez $outa $outb"));
	}

	$ausgabe .= tr(td(3, "center", "<br/>Achtung! Bei Abbruch eines Postens werden 20% einbehalten."));
	unset ($i, $bez, $panz, $result_ress, $inhalte_ress, $result_akt, $inhalte_akt, $anz, $ein, $result, $inhalte, $dauer);
} else {
	$ausgabe .= tr(td(0, "head", "Produktion"));
	$ausgabe .= tr(td(0, "center", "Um Exo-Zellen produzieren zu können, benötigst du ein höheres Evolutionsstadium!"));
	$ausgabe .= tr(td(0, "center", "Bitte entwickle das Knochenmark."));
}

$ausgabe .= "</table>\n</form>\n";

?>