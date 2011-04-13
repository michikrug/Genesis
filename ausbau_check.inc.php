<?php

if ($inhalte_s["urlaub"] < time()) {
	// Ausbau starten / abbrechen
	$result_akt = mysql_query("SELECT id,aktion FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='konst'");
	$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);

	$w = isset($_REQUEST["w"]) ? intval($_REQUEST["w"]) : NULL;
	$s = isset($_REQUEST["s"]) ? $_REQUEST["s"] : NULL;
	$error = array_fill(1, 17, NULL);

	if ($w > 0 && $w <= 17 && ($inhalte_b["konst$w"] + 1) <= maxstufe($inhalte_s["forsch7"])) {
		if (($w == 1 || ($w == 2 && $inhalte_b["konst1"] > 0) || ($w == 3 && $inhalte_b["konst1"] > 0) || ($w == 4 && $inhalte_b["konst1"] > 2) || ($w == 5 && $inhalte_b["konst1"] > 2) || ($w == 6 && $inhalte_b["konst1"] > 4) || ($w == 7 && $inhalte_b["konst1"] > 7 && $inhalte_s["forsch7"] > 1) || ($w == 8 && $inhalte_b["konst1"] > 5 && $inhalte_b["typ"] == 0) || ($w == 9 && $inhalte_b["konst2"] > 0 && $inhalte_s["forsch6"] > 0) || ($w == 10 && $inhalte_b["konst3"] > 0 && $inhalte_s["forsch6"] > 0) || ($w == 11 && $inhalte_b["konst4"] > 0 && $inhalte_s["forsch6"] > 0) || ($w == 12 && $inhalte_b["konst5"] > 0 && $inhalte_s["forsch6"] > 0) || ($w == 13 && $inhalte_b["konst6"] > 0 && $inhalte_s["forsch6"] > 0) || ($w == 14 && $inhalte_b["konst7"] > 0) || ($w == 15 && $inhalte_s["forsch4"] > 0 && $inhalte_s["forsch7"] > 1) || ($w == 16 && $inhalte_s["forsch5"] > 1 && $inhalte_s["forsch7"] > 2) || ($w == 17 && $inhalte_s["forsch8"] > 1 && $inhalte_s["forsch7"] > 2))) {
			$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer,bezeichnung FROM genesis_infos WHERE typ='konst$w'");
			$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);

            /* START: Einsteigerhilfe */
			$result_max = mysql_query("SELECT MAX(konst$w) as stufe FROM genesis_basen");
			$inhalte_max = mysql_fetch_array($result_max, MYSQL_ASSOC);
			$reduce = 1;
			if ($inhalte_max["stufe"] >= ($inhalte_b["konst$w"]+10)) $reduce = 0.8;
			if ($inhalte_max["stufe"] >= ($inhalte_b["konst$w"]+20)) $reduce = 0.6;
            /* END: Einsteigerhilfe */

            for ($k = 1; $k <= 4; $k++) $ress[$k] = round(kostaus($inhalte_ress["ress$k"], $inhalte_b["konst$w"]) * $reduce);

			if (!$inhalte_akt) {
				for ($k = 1; $k <= 4; $k++) $ress_neu[$k] = $inhalte_b["ress$k"] - $ress[$k];
				if ($ress_neu[1] >= 0 && $ress_neu[2] >= 0 && $ress_neu[3] >= 0 && $ress_neu[4] >= 0) {
					$endzeit = time() + round(daueraus($inhalte_ress["dauer"], $inhalte_b["konst$w"], $inhalte_b["konst1"]) * $reduce);
					mysql_query("UPDATE genesis_basen SET ress1='" . $ress_neu[1] . "', ress2='" . $ress_neu[2] . "', ress3='" . $ress_neu[3] . "', ress4='" . $ress_neu[4] . "' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
					mysql_query("INSERT INTO genesis_aktionen (startzeit, endzeit, basis1, typ, aktion) VALUES ('" . time() . "', '$endzeit', '" . $inhalte_s["basis$b"] . "', 'konst', '$w')");
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "', 'START: konst $w - " . ($inhalte_b["konst$w"] + 1) . "')");
				} else {
					$error[$w] = "Nicht genug Nährstoffe vorhanden!";
				}
			} else {
				if ($s == "a" && $w == $inhalte_akt["aktion"]) {
					for ($k = 1; $k <= 4; $k++) {
						$ress_neu[$k] = $inhalte_b["ress$k"] + $ress[$k];
						$ress_k[$k] = resskap($inhalte_b["konst" . ($k + 8)]);
						if ($ress_neu[$k] > $ress_k[$k]) $ress_neu[$k] = $ress_k[$k];
					}
					mysql_query("UPDATE genesis_basen SET ress1='" . $ress_neu[1] . "', ress2='" . $ress_neu[2] . "', ress3='" . $ress_neu[3] . "', ress4='" . $ress_neu[4] . "' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
					mysql_query("DELETE FROM genesis_aktionen WHERE id='" . $inhalte_akt["id"] . "'");
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "', 'ABBRUCH: konst $w - " . ($inhalte_b["konst$w"] + 1) . "')");
				} else {
					$error[$w] = "Es wird bereits etwas entwickelt!";
				}
			}
			unset ($result_akt, $inhalte_akt, $result_ress, $inhalte_ress);
		}
	}
}

?>