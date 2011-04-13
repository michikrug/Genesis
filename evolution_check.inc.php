<?php

if ($inhalte_s["urlaub"] < time()) {
	// Evolution starten / abbrechen
	$result_akt = mysql_query("SELECT id,aktion FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='forsch'");
	$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);

	$w = isset($_REQUEST["w"]) ? intval($_REQUEST["w"]) : NULL;
	$s = isset($_REQUEST["s"]) ? $_REQUEST["s"] : NULL;
	$error = array_fill(1, 8, NULL);

	if ($w > 0 && $w <= 8) {
		if (($w == 1 && $inhalte_b["konst8"] > 0) || ($w == 2 && $inhalte_b["konst8"] > 4) || ($w == 3 && $inhalte_b["konst8"] > 14 && $inhalte_s["forsch1"] > 9 && $inhalte_s["forsch2"] > 9) || ($w == 4 && $inhalte_b["konst8"] > 3 && $inhalte_s["forsch1"] > 0) || ($w == 5 && $inhalte_b["konst8"] > 2) || ($w == 6 && $inhalte_b["konst8"] > 0) || ($w == 7 && $inhalte_b["konst8"] > 1) || ($w == 8 && $inhalte_b["konst8"] > 3 && $inhalte_b["konst15"] > 0)) {
			$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer,bezeichnung FROM genesis_infos WHERE typ='forsch$w'");
			$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);

			/* START: Einsteigerhilfe */
			$result_max = mysql_query("SELECT MAX(forsch$w) as stufe FROM genesis_spieler");
			$inhalte_max = mysql_fetch_array($result_max, MYSQL_ASSOC);
			$reduce = 1;
			if ($inhalte_max["stufe"] >= ($inhalte_s["forsch$w"]+6)) $reduce = 0.8;
			if ($inhalte_max["stufe"] >= ($inhalte_s["forsch$w"]+12)) $reduce = 0.6;
			/* END: Einsteigerhilfe */

			for ($k = 1; $k <= 4; $k++) $ress[$k] = round(kostevo($inhalte_ress["ress$k"], $inhalte_s["forsch$w"]) * $reduce);

			if (!$inhalte_akt) {
				for ($k = 1; $k <= 4; $k++) $ress_neu[$k] = $inhalte_b["ress$k"] - $ress[$k];
				if ($ress_neu[1] >= 0 && $ress_neu[2] >= 0 && $ress_neu[3] >= 0 && $ress_neu[4] >= 0) {
					$endzeit = time() + round(dauerevo($inhalte_ress["dauer"], $inhalte_s["forsch$w"], $inhalte_b["konst8"]) * $reduce);
					mysql_query("UPDATE genesis_basen SET ress1='" . $ress_neu[1] . "', ress2='" . $ress_neu[2] . "', ress3='" . $ress_neu[3] . "', ress4='" . $ress_neu[4] . "' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
					mysql_query("INSERT INTO genesis_aktionen (startzeit, endzeit, basis1, typ, aktion) VALUES ('" . time() . "', '$endzeit', '" . $inhalte_s["basis$b"] . "', 'forsch', '$w')");
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "', 'START: forsch $w - " . ($inhalte_s["forsch$w"] + 1) . "')");
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
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "', 'ABBRUCH: forsch $w - " . ($inhalte_s["forsch$w"] + 1) . "')");
				} else {
					$error[$w] = "Dein Neogen kann nur einen Prozess gleichzeitig erlernen!";
				}
			}
			unset ($result_akt, $inhalte_akt, $result_ress, $inhalte_ress);
		}
	}
}

?>