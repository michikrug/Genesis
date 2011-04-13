<?php

if ($inhalte_s["urlaub"] < time()) {

	$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
    $w = isset($_REQUEST["w"]) ? intval($_REQUEST["w"]) : NULL;

	if ($aktion == "Produzieren" && $inhalte_b["konst7"] > 0) {
		$w = 0;
		$prod = 0;
		for ($i = 1; $i <= 8; $i++) {
			$prod = isset($_REQUEST["prod$i"]) ? intval($_REQUEST["prod$i"]) : 0;
			if ($prod > 0) {
				$w = $i;
				break;
			}
		}
		if (($w == 1 && $inhalte_b["konst7"] > 0 && $inhalte_s["forsch1"] > 0 && $inhalte_s["forsch6"] > 0 && $inhalte_s["forsch7"] > 1) || ($w == 2 && $inhalte_b["konst7"] > 4 && $inhalte_s["forsch1"] > 2 && $inhalte_s["forsch2"] > 2 && $inhalte_s["forsch4"] > 3 && $inhalte_s["forsch6"] > 3 && $inhalte_s["forsch7"] > 5) || ($w == 3 && $inhalte_b["konst7"] > 9 && $inhalte_s["forsch1"] > 7 && $inhalte_s["forsch2"] > 9 && $inhalte_s["forsch4"] > 9 && $inhalte_s["forsch6"] > 7 && $inhalte_s["forsch7"] > 11) || ($w == 4 && $inhalte_b["konst7"] > 14 && $inhalte_s["forsch1"] > 14 && $inhalte_s["forsch2"] > 11 && $inhalte_s["forsch4"] > 9 && $inhalte_s["forsch6"] > 11 && $inhalte_s["forsch7"] > 15) || ($w == 5 && $inhalte_b["konst7"] > 19 && $inhalte_s["forsch1"] > 19 && $inhalte_s["forsch2"] > 17 && $inhalte_s["forsch3"] > 9 && $inhalte_s["forsch4"] > 14 && $inhalte_s["forsch6"] > 17 && $inhalte_s["forsch7"] > 19) || ($w == 6 && $inhalte_b["konst7"] > 0 && $inhalte_s["forsch4"] > 0 && $inhalte_s["forsch6"] > 0 && $inhalte_s["forsch7"] > 1 && $inhalte_s["forsch8"] > 0) || ($w == 7 && $inhalte_b["konst7"] > 1 && $inhalte_s["forsch1"] > 1 && $inhalte_s["forsch2"] > 1 && $inhalte_s["forsch6"] > 2 && $inhalte_s["forsch7"] > 1) || ($w == 8 && $inhalte_b["konst7"] > 10 && $inhalte_s["forsch3"] > 5 && $inhalte_s["forsch4"] > 7 && $inhalte_s["forsch6"] > 9 && $inhalte_s["forsch7"] > 9 && $inhalte_s["keimzelle"] == 0)) {
			if ($prod > 0 && $w > 0 && $w <= 8) {
				if ($w > 7 && $prod > 1) $prod = 1;
				$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer,bezeichnung FROM genesis_infos WHERE typ='prod$w'");
				$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
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
				if ($prod > $panz[0]) $prod = $panz[0];
				if ($prod > 0) {
					for ($k = 1; $k <= 4; $k++) $ress_neu[$k] = $inhalte_b["ress$k"] - ($ress[$k] * $prod);
					$result_akt = mysql_query("SELECT id,aktion,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='prod'");
					$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
					$endzeit = time() + dauerprod($inhalte_ress["dauer"], $inhalte_b["konst7"]);
					if ($w == 8) mysql_query("UPDATE genesis_spieler SET keimzelle='1' WHERE id='$sid'");
					mysql_query("UPDATE genesis_basen SET ress1='" . $ress_neu[1] . "', ress2='" . $ress_neu[2] . "', ress3='" . $ress_neu[3] . "', ress4='" . $ress_neu[4] . "' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
					if ($inhalte_akt) {
						$e = $inhalte_akt["einheiten"] . "$w||";
						$r = $inhalte_akt["ress"] . "$prod||";
						mysql_query("UPDATE genesis_aktionen SET einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
					} else {
						mysql_query("INSERT INTO genesis_aktionen (startzeit, endzeit, basis1, typ, aktion, einheiten, ress) VALUES ('" . time() . "', '$endzeit', '" . $inhalte_s["basis$b"] . "', 'prod', '$w', '$w||', '$prod||')");
					}
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $REMOTE_ADDR . "', '" . time() . "', 'START: prod $w - $prod')");
				}
				unset ($inhalte_akt, $result_akt, $e, $r, $w, $prod, $panz, $result_ress, $inhalte_ress, $ress);
			}
		}
	} elseif ($aktion == "a" && $w >= 0 && $inhalte_b["konst7"] > 0) {
		$result_akt = mysql_query("SELECT id,aktion,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='prod'");
		$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
		$ein = explode("||", $inhalte_akt["einheiten"]);
		$anz = explode("||", $inhalte_akt["ress"]);
		$e = "";
		$r = "";
		for ($i = 0; $i < count($ein)-1; $i++) {
			if ($i != $w) {
				$e .= $ein[$i] . "||";
				$r .= $anz[$i] . "||";
			}
		}
		$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer FROM genesis_infos WHERE typ='prod" . $ein[$w] . "'");
		$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
		for ($k = 1; $k <= 4; $k++) {
			$ress_neu[$k] = $inhalte_b["ress$k"] + ($inhalte_ress["ress$k"] * $anz[$w]) - round(($inhalte_ress["ress$k"] * $anz[$w]) * 0.2, 0);
			$ressk[$k] = resskap($inhalte_b["konst" . ($k + 8)]);
			if ($ress_neu[$k] > $ressk[$k]) $ress_neu[$k] = $ressk[$k];
		}
		if ($e == "") {
			mysql_query("DELETE FROM genesis_aktionen WHERE id='" . $inhalte_akt["id"] . "'");
		} else {
			$result_d = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='prod" . $ein[$w + 1] . "'");
			$inhalte_d = mysql_fetch_array($result_d, MYSQL_ASSOC);
			if ($w == 0) {
				$endzeit = time() + dauerprod($inhalte_d["dauer"], $inhalte_b["konst7"]);
				mysql_query("UPDATE genesis_aktionen SET startzeit='" . time() . "', endzeit='$endzeit', aktion='" . $ein[$w + 1] . "', einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
			} else {
				mysql_query("UPDATE genesis_aktionen SET einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
			}
		}
		if ($ein[$w] == 8 && $inhalte_b["prod9"] == 0) mysql_query("UPDATE genesis_spieler SET keimzelle='0' WHERE id='$sid'");
		mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $REMOTE_ADDR . "', '" . time() . "', 'ABBRUCH: prod " . $ein[$w] . " - " . $anz[$w] . "')");
		mysql_query("UPDATE genesis_basen SET ress1='" . $ress_neu[1] . "', ress2='" . $ress_neu[2] . "', ress3='" . $ress_neu[3] . "', ress4='" . $ress_neu[4] . "' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
		unset ($result_akt, $inhalte_akt, $ein, $anz, $e, $r, $w, $prod, $result_ress, $inhalte_ress, $result_d, $inhalte_d, $ress_neu, $ressk);

	} elseif ($aktion == "up" && $w > 0 && $inhalte_b["konst7"] > 0) {
		$result_akt = mysql_query("SELECT id,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='prod'");
		$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
		$ein = explode("||", $inhalte_akt["einheiten"]);
		$anz = explode("||", $inhalte_akt["ress"]);
		$e = $ein[$w] . "||";
		$r = $anz[$w] . "||";
		for ($i = 0; $i < count($ein)-1; $i++) {
			if ($i != $w) {
				$e .= $ein[$i] . "||";
				$r .= $anz[$i] . "||";
			}
		}
		$result_d = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='prod" . $ein[$w] . "'");
		$inhalte_d = mysql_fetch_array($result_d, MYSQL_ASSOC);
		$endzeit = time() + dauerprod($inhalte_d["dauer"], $inhalte_b["konst7"]);
		mysql_query("UPDATE genesis_aktionen SET startzeit='" . time() . "', endzeit='$endzeit', aktion='" . $ein[$w] . "', einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
		unset ($ein, $anz, $e, $r, $w, $result_akt, $inhalte_akt, $result_d, $inhalte_d);
	}
}

?>