<?php

if ($inhalte_s["urlaub"] < time()) {

	$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
	$w = isset($_REQUEST["w"]) ? intval($_REQUEST["w"]) : NULL;

	if ($aktion == "Entwickeln" && $inhalte_b["konst15"] > 0) {
		$w = 0;
		$vert = 0;
		for ($i = 1; $i <= 3; $i++) {
			$vert = isset($_REQUEST["vert$i"]) ? intval($_REQUEST["vert$i"]) : 0;
			if ($vert > 0) {
				$w = $i;
				break;
			}
		}

		$result_akt = mysql_query("SELECT ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='vert'");
		$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
		$anz = explode("||", $inhalte_akt["ress"]);
		$anzv = 0;
		for ($i = 0; $i < count($anz)-1; $i++) $anzv += $anz[$i];
		$maxanz = maxvert($inhalte_b["konst15"]) - $inhalte_b["vert1"] - $inhalte_b["vert2"] - $inhalte_b["vert3"] - $anzv;

		if (($w == 1 && $inhalte_b["konst15"] > 0 && $inhalte_s["forsch1"] > 0 && $inhalte_s["forsch4"] > 0 && $inhalte_s["forsch7"] > 2) || ($w == 2 && $inhalte_b["konst15"] > 5 && $inhalte_s["forsch1"] > 4 && $inhalte_s["forsch4"] > 6 && $inhalte_s["forsch7"] > 7) || ($w == 3 && $inhalte_b["konst15"] > 11 && $inhalte_s["forsch1"] > 9 && $inhalte_s["forsch4"] > 9 && $inhalte_s["forsch7"] > 14)) {
			if ($vert > 0 && $w >= 1 && $w <= 3) {
				$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer,bezeichnung FROM genesis_infos WHERE typ='vert$w'");
				$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
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
				if ($vert > $vanz[0]) $vert = $vanz[0];
				if ($vert > $maxanz) $vert = $maxanz;
				if ($vert > 0) {
					for ($k = 1; $k <= 4; $k++) $ress_neu[$k] = $inhalte_b["ress$k"] - ($ress[$k] * $vert);
					$result_akt = mysql_query("SELECT id,aktion,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='vert'");
					$inhalte_akt = mysql_fetch_array($result_akt, MYSQL_ASSOC);
					$endzeit = time() + dauervert($inhalte_ress["dauer"], $inhalte_b["konst15"]);
					mysql_query("UPDATE genesis_basen SET ress1='" . $ress_neu[1] . "', ress2='" . $ress_neu[2] . "', ress3='" . $ress_neu[3] . "', ress4='" . $ress_neu[4] . "' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
					if ($inhalte_akt) {
						$e = $inhalte_akt["einheiten"] . "$w||";
						$r = $inhalte_akt["ress"] . "$vert||";
						mysql_query("UPDATE genesis_aktionen SET einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
					} else {
						mysql_query("INSERT INTO genesis_aktionen (startzeit, endzeit, basis1, typ, aktion, einheiten, ress) VALUES ('" . time() . "', '$endzeit', '" . $inhalte_s["basis$b"] . "', 'vert', '$w', '$w||', '$vert||')");
					}
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "', 'START: vert $w - $vert')");
				}
				unset ($inhalte_akt, $result_akt, $e, $r, $w, $vert, $vanz, $result_ress, $inhalte_ress, $ress);
			}
		}

	} elseif ($aktion == "a" && $w >= 0 && $inhalte_b["konst15"] > 0) {
		$result_akt = mysql_query("SELECT id,aktion,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='vert'");
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
		$result_ress = mysql_query("SELECT ress1,ress2,ress3,ress4,dauer FROM genesis_infos WHERE typ='vert" . $ein[$w] . "'");
		$inhalte_ress = mysql_fetch_array($result_ress, MYSQL_ASSOC);
		for ($k = 1; $k <= 4; $k++) {
			$ress_neu[$k] = $inhalte_b["ress$k"] + ($inhalte_ress["ress$k"] * $anz[$w]) - round(($inhalte_ress["ress$k"] * $anz[$w]) * 0.2, 0);
			$ressk[$k] = resskap($inhalte_b["konst" . ($k + 8)]);
			if ($ress_neu[$k] > $ressk[$k]) $ress_neu[$k] = $ressk[$k];
		}
		if ($e == "") {
			mysql_query("DELETE FROM genesis_aktionen WHERE id='" . $inhalte_akt["id"] . "'");
		} else {
			$result_d = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='vert" . $ein[$w + 1] . "'");
			$inhalte_d = mysql_fetch_array($result_d, MYSQL_ASSOC);
			if ($w == 0) {
				$endzeit = time() + dauervert($inhalte_d["dauer"], $inhalte_b["konst15"]);
				mysql_query("UPDATE genesis_aktionen SET startzeit='" . time() . "', endzeit='$endzeit', aktion='" . $ein[$w + 1] . "', einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
			} else {
				mysql_query("UPDATE genesis_aktionen SET einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
			}
		}
		mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte_s["name"] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "', 'ABBRUCH: vert " . $ein[$w] . " - " . $anz[$w] . "')");
		mysql_query("UPDATE genesis_basen SET ress1='" . $ress_neu[1] . "', ress2='" . $ress_neu[2] . "', ress3='" . $ress_neu[3] . "', ress4='" . $ress_neu[4] . "' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
		unset ($result_akt, $inhalte_akt, $ein, $anz, $e, $r, $w, $vert, $result_ress, $inhalte_ress, $result_d, $inhalte_d, $ress_neu, $ressk);

    } elseif ($aktion == "up" && $w > 0 && $inhalte_b["konst15"] > 0) {
		$result_akt = mysql_query("SELECT id,einheiten,ress FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='vert'");
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
		$result_d = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='vert" . $ein[$w] . "'");
		$inhalte_d = mysql_fetch_array($result_d, MYSQL_ASSOC);
		$endzeit = time() + dauervert($inhalte_d["dauer"], $inhalte_b["konst15"]);
		mysql_query("UPDATE genesis_aktionen SET startzeit='" . time() . "', endzeit='$endzeit', aktion='" . $ein[$w] . "', einheiten='$e', ress='$r' WHERE id='" . $inhalte_akt["id"] . "'");
		unset ($ein, $anz, $e, $r, $w, $result_akt, $inhalte_akt, $result_d, $inhalte_d);
	}
}

?>