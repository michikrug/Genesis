<?php

$result = mysql_query("SELECT * FROM genesis_handel WHERE (bieter='$sid' OR sucher='$sid') AND zeit<'" . time() . "' ORDER BY zeit");
while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if ($inhalte["bieter"] == 0) {
		$result1 = mysql_query("SELECT s.name as name, b.ress" . $inhalte["typ_geb"] . " as ress" . $inhalte["typ_geb"] . " FROM genesis_spieler s LEFT JOIN genesis_basen b USING(name) WHERE s.id='" . $inhalte["sucher"] . "' and b.typ='0'");
		$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
		$ress_neu = $inhalte1["ress" . $inhalte["typ_geb"]] + $inhalte["anz_geb"] - round($inhalte["anz_geb"] / 10, 0);

		mysql_query("UPDATE genesis_fond SET ress" . $inhalte["typ_geb"] . "=(ress" . $inhalte["typ_geb"] . "+" . round($inhalte["anz_geb"] / 10, 0) . ")");
		mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte1["name"] . "', '0', '" . time() . "', 'ENDE: handel von " . $inhalte1["name"] . " - " . $inhalte["anz_such"] . " " . $inhalte["typ_such"] . "')");
		mysql_query("UPDATE genesis_basen SET ress" . $inhalte["typ_geb"] . "='$ress_neu' WHERE name='" . $inhalte1["name"] . "' and typ='0'");
		mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte["sucher"] . "','" . time() . "','ereignis','Handel','Dein Angebot wurde leider nicht wahrgenommen. (10% Provision wurden einbehalten)')");
		mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte["sucher"] . "'");
		mysql_query("DELETE FROM genesis_handel WHERE id='" . $inhalte["id"] . "'");
	} else {
		$result1 = mysql_query("SELECT s.name as name, b.ress" . $inhalte["typ_such"] . " as ress" . $inhalte["typ_such"] . " FROM genesis_spieler s LEFT JOIN genesis_basen b USING(name) WHERE s.id='" . $inhalte["sucher"] . "' and b.typ='0'");
		$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
		$result2 = mysql_query("SELECT s.name as name, b.ress" . $inhalte["typ_such"] . " as ress" . $inhalte["typ_such"] . ", b.ress" . $inhalte["typ_geb"] . " as ress" . $inhalte["typ_geb"] . " FROM genesis_spieler s LEFT JOIN genesis_basen b USING(name) WHERE s.id='" . $inhalte["bieter"] . "' and b.typ='0'");
		$inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		$ress_neu1 = $inhalte1["ress" . $inhalte["typ_such"]] + $inhalte["anz_such"];
		$ress_neu2 = $inhalte2["ress" . $inhalte["typ_geb"]] + $inhalte["anz_geb"];
		$ress_neu3 = $inhalte2["ress" . $inhalte["typ_such"]] - $inhalte["anz_such"];
		if ($ress_neu3 >= 0) {
			mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte1["name"] . "', '0', '" . time() . "', 'ENDE: handel von " . $inhalte1["name"] . " und " . $inhalte2["name"] . " - " . $inhalte["anz_geb"] . " " . $inhalte["typ_geb"] . " gegen " . $inhalte["anz_such"] . " " . $inhalte["typ_such"] . "')");
			mysql_query("UPDATE genesis_basen SET ress" . $inhalte["typ_such"] . "='$ress_neu1' WHERE name='" . $inhalte1["name"] . "' and typ='0'");
			mysql_query("UPDATE genesis_basen SET ress" . $inhalte["typ_geb"] . "='$ress_neu2', ress" . $inhalte["typ_such"] . "='$ress_neu3' WHERE name='" . $inhalte2["name"] . "' and typ='0'");
			mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte["sucher"] . "','" . time() . "','ereignis','Handel','Ein Handel wurde vollzogen:<br/>Du hast " . format($inhalte["anz_geb"]) . " " . num2typ($inhalte["typ_geb"]) . " gegen " . format($inhalte["anz_such"]) . " " . num2typ($inhalte["typ_such"]) . " eingetauscht.'),('0','" . $inhalte["bieter"] . "','" . time() . "','ereignis','Handel','Ein Handel wurde vollzogen:<br/>Du hast " . format($inhalte["anz_such"]) . " " . num2typ($inhalte["typ_such"]) . " gegen " . format($inhalte["anz_geb"]) . " " . num2typ($inhalte["typ_geb"]) . " eingetauscht.')");
			mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte["sucher"] . "'");
			mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte["bieter"] . "'");
			mysql_query("DELETE FROM genesis_handel WHERE id='" . $inhalte["id"] . "'");
		}
	}
}
unset($inhalte, $result, $inhaltes1, $results1, $inhalteb1, $resultb1, $inhaltes2, $results2, $inhalteb2, $resultb2, $ress_neu, $ress_neu1, $ress_neu2, $ress_neu3);

$ze = time() - 300;
$result_check = mysql_query("SELECT * FROM genesis_basen WHERE resszeit>'0' AND resszeit<'$ze' ORDER BY resszeit LIMIT 10");
while ($inhalte_check = mysql_fetch_array($result_check, MYSQL_ASSOC)) {
	$result_check2 = mysql_query("SELECT * FROM genesis_spieler WHERE name='" . $inhalte_check["name"] . "'");
	$inhalte_check2 = mysql_fetch_array($result_check2, MYSQL_ASSOC);
	$result_check_b = mysql_query("SELECT * FROM genesis_basen WHERE name='" . $inhalte_check["name"] . "' and id<>" . $inhalte_check["id"]);
	$inhalte_check_b = mysql_fetch_array($result_check_b, MYSQL_ASSOC);

	$punkte = 0;
	$punktem = 0;
	$rz = $inhalte_check["resszeit"] + 300;
	$bcheck = $inhalte_check["koordx"] . ":" . $inhalte_check["koordy"] . ":" . $inhalte_check["koordz"];
	$bcheck_b = $inhalte_check_b["koordx"] . ":" . $inhalte_check_b["koordy"] . ":" . $inhalte_check_b["koordz"];
	$aid = $inhalte_check["id"];

	for ($c = 1; $c <= 8; $c++) {
		$inhalte_check["prodv$c"] = 0;
		$inhalte_check["prodm$c"] = 0;
	}

	$diff = round(300 / 3600, 6);
	for ($c = 1; $c <= 5; $c++) {
		$result_info = mysql_query("SELECT wert1 FROM genesis_infos WHERE typ='konst" . ($c + 1) . "'");
		$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
		if ($inhalte_check2["urlaub"] > time()) {
			$inhalte_check["ress$c"] += round(ressprod($inhalte_info["wert1"], $inhalte_check["konst" . ($c + 1)]) * $diff / 10, 0);
			if ($c == 5) $inhalte_check["ress5"] -= round($inhalte_info["wert1"] * $diff / 10, 0) + round($inhalte_check["verbrauch"] * $diff / 10, 0);
		} else {
			$inhalte_check["ress$c"] += round(ressprod($inhalte_info["wert1"], $inhalte_check["konst" . ($c + 1)]) * $diff, 0);
			if ($c == 5) $inhalte_check["ress5"] -= round($inhalte_info["wert1"] * $diff, 0) + round($inhalte_check["verbrauch"] * $diff, 0);
		}
		$ressk = resskap($inhalte_check["konst" . ($c + 8)]);
		if ($inhalte_check["ress$c"] > $ressk) $inhalte_check["ress$c"] = $ressk;
		unset ($result_info, $inhalte_info, $ressk);
	}
	if ($inhalte_check["ress5"] < 0) $inhalte_check["ress5"] = 0;

	$result_checka = mysql_query("SELECT basis1,basis2,einheiten,aktion FROM genesis_aktionen WHERE typ='miss' AND (basis1='" . $inhalte_check2["basis1"] . "' OR basis1='" . $inhalte_check2["basis2"] . "' OR (basis2='" . $inhalte_check2["basis1"] . "' AND aktion='4' AND zusatz>5) OR (basis2='" . $inhalte_check2["basis2"] . "' AND aktion='4' AND zusatz>5))");
	while ($inhalte_checka = mysql_fetch_array($result_checka, MYSQL_ASSOC)) {
		$me = explode("||", $inhalte_checka["einheiten"]);
		for ($c = 1; $c <= 8; $c++) {
			if ($inhalte_checka["basis2"] == $bcheck && $inhalte_checka["aktion"] == 4) $inhalte_check["prodv$c"] += $me[$c-1];
			if ($inhalte_checka["basis1"] == $bcheck) $inhalte_check["prodm$c"] += $me[$c-1];
			if ($inhalte_checka["basis1"] == $bcheck_b) $inhalte_check["prodm$c"] += $me[$c-1];
		}
		unset ($me, $c);
	}
	unset($result_checka, $inhalte_checka);

	$verbrauch = 0;
	for ($c = 1; $c <= 8; $c++) {
		$result_info = mysql_query("SELECT wert1,wert2 FROM genesis_infos WHERE typ='prod$c'");
		$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
		$punktem += $inhalte_info["wert2"] * ($inhalte_check_b["prod$c"] + $inhalte_check["prod$c"] + $inhalte_check["prodm$c"]);
		$verbrauch += round(verbrauch($inhalte_info["wert1"]) / 8, 0) * ($inhalte_check["prod$c"] + $inhalte_check["prodv$c"]);
		unset ($inhalte_info, $result_info);
	}
	for ($c = 1; $c <= 3; $c++) {
		$result_info = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='vert$c'");
		$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
		$punktem += $inhalte_info["wert2"] * ($inhalte_check["vert$c"] + $inhalte_check_b["vert$c"]);
		unset ($inhalte_info, $result_info);
	}
	if ($inhalte_check2["atttime"] < (time()-86400)) {
		$inhalte_check2["attcount"] -= round(($inhalte_check["punkte"] + $inhalte_check_b["punkte"] + $inhalte_check2["punktef"] + round($inhalte_check2["kampfpkt"] * 0.3, 0) + $punktem) / 200, 0);
		$inhalte_check2["atttime"] += 86400;
	}

	/*
	if ($inhalte_check["eier"]+$inhalte_check_b["eier"] >= 150) {
	mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','1','" . time() . "','news','Osterspecial','". $inhalte_check["name"] ." hat ". ($inhalte_check["eier"]+$inhalte_check_b["eier"]) ." Ostereier gesammelt.')");
	}
	*/

	mysql_query("UPDATE genesis_basen SET ress1='" . $inhalte_check["ress1"] . "', ress2='" . $inhalte_check["ress2"] . "', ress3='" . $inhalte_check["ress3"] . "', ress4='" . $inhalte_check["ress4"] . "', ress5='" . $inhalte_check["ress5"] . "', resszeit='$rz', verbrauch='$verbrauch' WHERE id='$aid'");
	mysql_query("UPDATE genesis_spieler SET punkte='" . ($inhalte_check["punkte"] + $inhalte_check_b["punkte"] + $inhalte_check2["punktef"] + round($inhalte_check2["kampfpkt"] * 0.3, 0) + $punktem) . "', punktek='" . ($inhalte_check["punkte"] + $inhalte_check_b["punkte"]) . "', punktem='$punktem', attcount='" . $inhalte_check2["attcount"] . "', atttime='" . $inhalte_check2["atttime"] . "' WHERE id='" . $inhalte_check2["id"] . "'");

	$resultp = mysql_query("SELECT count(*) as anz, sum(punkte) as punkte, sum(punktek) as punktek, sum(punktef) as punktef, sum(punktem) as punktem, sum(kampfpkt) as kampfpkt FROM genesis_spieler WHERE alli='" . $inhalte_check2["alli"] . "'");
	$inhaltep = mysql_fetch_array($resultp, MYSQL_ASSOC);
	mysql_query("UPDATE genesis_allianzen SET punkte='" . $inhaltep["punkte"] . "', kampfpkt='" . $inhaltep["kampfpkt"] . "', punkted='" . round($inhaltep["punkte"] / $inhaltep["anz"]) . "', anz='" . $inhaltep["anz"] . "', punktem='" . $inhaltep["punktem"] . "', punktek='" . $inhaltep["punktek"] . "', punktef='" . $inhaltep["punktef"] . "' WHERE id='" . $inhalte_check2["alli"] . "'");
}

unset($pkte, $epkte, $apkte, $kpkte, $mpkte, $punkte, $inhalte_check, $result_check, $inhalte_check2, $result_check2, $inhaltep, $resultp, $punktek, $punktem, $punktef);

$result_check = mysql_query("SELECT * FROM genesis_aktionen WHERE endzeit<='" . time() . "' order by endzeit limit 10");
while ($inhalte_check = mysql_fetch_array($result_check, MYSQL_ASSOC)) {
	$result_check_c = mysql_query("SELECT id FROM genesis_aktionen WHERE id='" . $inhalte_check["id"] . "' and typ='" . $inhalte_check["typ"] . "' and aktion='" . $inhalte_check["aktion"] . "'");
	if (mysql_fetch_array($result_check_c, MYSQL_ASSOC)) {
		$aid = $inhalte_check["id"];
		$koords = explode(":", $inhalte_check["basis1"]);
		$kx = $koords[0];
		$ky = $koords[1];
		$kz = $koords[2];
		$resultc = mysql_query("SELECT id,name,missmsg,endmsg FROM genesis_spieler WHERE basis1='" . $inhalte_check["basis1"] . "' or basis2='" . $inhalte_check["basis1"] . "'");
		$inhaltec = mysql_fetch_array($resultc, MYSQL_ASSOC);
		$cid = $inhaltec["id"];

		switch ($inhalte_check["typ"]) {
			case "konst":
				$var = "konst" . $inhalte_check["aktion"];
				$result_konst = mysql_query("SELECT $var,punkte FROM genesis_basen WHERE koordx='$kx' AND koordy='$ky' AND koordz='$kz'");
				$inhalte_konst = mysql_fetch_array($result_konst, MYSQL_ASSOC);
				$s = $inhalte_konst[$var] + 1;
				$pkt = $inhalte_konst["punkte"] + getpunkte("konst", $inhalte_konst[$var], $inhalte_check["aktion"]);
				if ($var == "konst7") {
					$result2 = mysql_query("SELECT id,startzeit,aktion FROM genesis_aktionen WHERE basis1='" . $inhalte_check["basis1"] . "' AND typ='prod'");
					if ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$result3 = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='prod" . $inhalte2["aktion"] . "'");
						$inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);
						$endzeitneu = $inhalte2["startzeit"] + dauerprod($inhalte3["dauer"], $s);
						if ($endzeitneu < time()) $endzeitneu = time() + 60;
						mysql_query("UPDATE genesis_aktionen SET endzeit='$endzeitneu' WHERE id='" . $inhalte2["id"] . "'");
					}
					unset($result2, $inhalte2, $result3, $inhalte3, $endzeitneu);
				} elseif ($var == "konst8") {
					$result2 = mysql_query("SELECT id,startzeit,aktion FROM genesis_aktionen WHERE basis1='" . $inhalte_check["basis1"] . "' AND typ='forsch'");
					if ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$result3 = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='forsch" . $inhalte2["aktion"] . "'");
						$inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);
						$result4 = mysql_query("SELECT forsch" . $inhalte2["aktion"] . " FROM genesis_spieler WHERE basis1='" . $inhalte_check["basis1"] . "' or basis2='" . $inhalte_check["basis1"] . "'");
						$inhalte4 = mysql_fetch_array($result4, MYSQL_ASSOC);

						/* START: Einsteigerhilfe */
						$result_max = mysql_query("SELECT MAX(forsch" . $inhalte2["aktion"] . ") as stufe FROM genesis_spieler");
						$inhalte_max = mysql_fetch_array($result_max, MYSQL_ASSOC);
						$reduce = 1;
						if ($inhalte_max["stufe"] >= ($inhalte4["forsch" . $inhalte2["aktion"]]+6)) $reduce = 0.8;
						if ($inhalte_max["stufe"] >= ($inhalte4["forsch" . $inhalte2["aktion"]]+12)) $reduce = 0.6;
						/* END: Einsteigerhilfe */

						$endzeitneu = $inhalte2["startzeit"] + round(dauerevo($inhalte3["dauer"], $inhalte4["forsch" . $inhalte2["aktion"]], $s) * $reduce);
						if ($endzeitneu < time()) $endzeitneu = time() + 60;
						mysql_query("UPDATE genesis_aktionen SET endzeit='$endzeitneu' WHERE id='" . $inhalte2["id"] . "'");
					}
					unset($result2, $inhalte2, $result3, $inhalte3, $result4, $inhalte4, $endzeitneu);
				}
				mysql_query("UPDATE genesis_basen SET punkte='$pkt', $var='$s' WHERE koordx='$kx' AND koordy='$ky' AND koordz='$kz'");
				if ($inhaltec["endmsg"] == 1) {
					$result_info = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='$var'");
					$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
					mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','$cid','" . $inhalte_check["endzeit"] . "','ereignis','Ausbau','Entwicklung von " . $inhalte_info["bezeichnung"] . " auf Stufe $s abgeschlossen (" . $inhalte_check["basis1"] . ")')");
					mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $cid . "'");
				}
				mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
				mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhaltec["name"] . "', '0', '" . time() . "', 'ENDE: konst " . $inhalte_check["aktion"] . " - $s')");
				unset($result_konst, $inhalte_konst, $result_info, $inhalte_info, $s, $var, $pkt);
				break;

			case "forsch":

				$var = "forsch" . $inhalte_check["aktion"];
				$result_forsch = mysql_query("SELECT $var,punktef FROM genesis_spieler WHERE id='$cid'");
				$inhalte_forsch = mysql_fetch_array($result_forsch, MYSQL_ASSOC);
				$s = $inhalte_forsch[$var] + 1;
				$pkt = $inhalte_forsch["punktef"] + getpunkte("forsch", $inhalte_forsch[$var], $inhalte_check["aktion"]);
				mysql_query("UPDATE genesis_spieler SET $var='$s', punktef='$pkt' WHERE id='$cid'");
				if ($inhaltec["endmsg"] == 1) {
					$result_info = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='$var'");
					$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
					mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','$cid','" . $inhalte_check["endzeit"] . "','ereignis','Evolution','Erlernung von " . $inhalte_info["bezeichnung"] . " auf Stufe $s abgeschlossen')");
					mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $cid . "'");
				}
				mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
				mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhaltec["name"] . "', '0', '" . time() . "', 'ENDE: forsch " . $inhalte_check["aktion"] . " - $s')");
				unset($result_forsch, $inhalte_forsch, $result_info, $inhalte_info, $s, $var, $pkt);
				break;

			case "prod":

				$var = "prod" . $inhalte_check["aktion"];
				$result_prod = mysql_query("SELECT ress5,$var,konst7 FROM genesis_basen WHERE koordx='$kx' AND koordy='$ky' AND koordz='$kz'");
				$inhalte_prod = mysql_fetch_array($result_prod, MYSQL_ASSOC);
				if ($inhalte_prod["konst7"] > 0) {
					if ($inhalte_prod["ress5"] > 0) {
						$result_info = mysql_query("SELECT bezeichnung,dauer FROM genesis_infos WHERE typ='$var'");
						$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
						$ein = explode("||", $inhalte_check["einheiten"]);
						$anz = explode("||", $inhalte_check["ress"]);
						$e = "";
						$r = "";
						$restdauer = time() - $inhalte_check["endzeit"] + dauerprod($inhalte_info["dauer"], $inhalte_prod["konst7"]);
						$an = intval($restdauer / dauerprod($inhalte_info["dauer"], $inhalte_prod["konst7"]));
						if ($an > $anz[0]) $an = $anz[0];
						$s = $inhalte_prod[$var] + $an;
						$anz[0] -= $an;
						$startzeit = $inhalte_check["endzeit"] + (dauerprod($inhalte_info["dauer"], $inhalte_prod["konst7"]) * ($an-1));
						if ($anz[0] > 0) {
							$e .= $ein[0] . "||";
							$r .= $anz[0] . "||";
							$akt = $ein[0];
							$result_info = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='prod" . $ein[0] . "'");
							$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
						} else {
							$akt = $ein[1];
							if ($inhaltec["endmsg"] == 1) {
								mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','$cid','" . $inhalte_check["endzeit"] . "','ereignis','Produktion','Produktion von " . $inhalte_info["bezeichnung"] . " abgeschlossen (" . $inhalte_check["basis1"] . ")')");
								mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $cid . "'");
							}
							$result_info = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='prod" . $ein[1] . "'");
							$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
						}
						for ($i = 1; $i < count($ein)-1; $i++) {
							$e .= $ein[$i] . "||";
							$r .= $anz[$i] . "||";
						}
						$endzeit = dauerprod($inhalte_info["dauer"], $inhalte_prod["konst7"]) + $startzeit;
						mysql_query("UPDATE genesis_basen SET $var='$s' WHERE koordx='$kx' AND koordy='$ky' AND koordz='$kz'");
						if ($e == "") {
							mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
						} else {
							mysql_query("UPDATE genesis_aktionen SET startzeit='$startzeit', endzeit='$endzeit', aktion='$akt', einheiten='$e', ress='$r' WHERE id='$aid'");
						}
					} else {
						$startzeit = $inhalte_check["endzeit"];
						$endzeit = 3600 + $inhalte_check["endzeit"];
						mysql_query("UPDATE genesis_aktionen SET startzeit='$startzeit', endzeit='$endzeit' WHERE id='$aid'");
					}
				} else {
					echo $inhaltec["name"] . " $kx:$ky:$kz";
				}
				unset($startzeit, $endzeit, $akt, $anz, $ein, $result_prod, $inhalte_prod, $result_info, $inhalte_info, $s, $var, $e, $r, $an);
				break;

			case "vert":

				$var = "vert" . $inhalte_check["aktion"];
				$result_vert = mysql_query("SELECT $var,konst15 FROM genesis_basen WHERE koordx='$kx' AND koordy='$ky' AND koordz='$kz'");
				$inhalte_vert = mysql_fetch_array($result_vert, MYSQL_ASSOC);
				if ($inhalte_vert["konst15"] > 0) {
					$result_info = mysql_query("SELECT bezeichnung,dauer FROM genesis_infos WHERE typ='$var'");
					$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
					$ein = explode("||", $inhalte_check["einheiten"]);
					$anz = explode("||", $inhalte_check["ress"]);
					$e = "";
					$r = "";
					$restdauer = time() - $inhalte_check["endzeit"] + dauervert($inhalte_info["dauer"], $inhalte_vert["konst15"]);
					$an = intval($restdauer / dauervert($inhalte_info["dauer"], $inhalte_vert["konst15"]));
					if ($an > $anz[0]) $an = $anz[0];
					$s = $inhalte_vert[$var] + $an;
					$anz[0] -= $an;
					$startzeit = $inhalte_check["endzeit"] + (dauervert($inhalte_info["dauer"], $inhalte_vert["konst15"]) * ($an-1));
					if ($anz[0] > 0) {
						$e .= $ein[0] . "||";
						$r .= $anz[0] . "||";
						$akt = $ein[0];
						$result_info = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='vert" . $ein[0] . "'");
						$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
					} else {
						$akt = $ein[1];
						if ($inhaltec["endmsg"] == 1) {
							mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','$cid','" . $inhalte_check["endzeit"] . "','ereignis','Verteidigung','Entwicklung von " . $inhalte_info["bezeichnung"] . " abgeschlossen (" . $inhalte_check["basis1"] . ")')");
							mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $cid . "'");
						}
						$result_info = mysql_query("SELECT dauer FROM genesis_infos WHERE typ='vert" . $ein[1] . "'");
						$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
					}
					for ($i = 1; $i < count($ein)-1; $i++) {
						$e .= $ein[$i] . "||";
						$r .= $anz[$i] . "||";
					}
					$endzeit = dauervert($inhalte_info["dauer"], $inhalte_vert["konst15"]) + $inhalte_check["endzeit"];
					mysql_query("UPDATE genesis_basen SET $var='$s' WHERE koordx='$kx' AND koordy='$ky' AND koordz='$kz'");
					if ($e == "") {
						mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
					} else {
						mysql_query("UPDATE genesis_aktionen SET startzeit='$startzeit', endzeit='$endzeit', aktion='$akt', einheiten='$e', ress='$r' WHERE id='$aid'");
					}
				} else {
					echo $inhaltec["name"] . " $kx:$ky:$kz";
				}
				unset($startzeit, $endzeit, $akt, $anz, $ein, $result_vert, $inhalte_vert, $result_info, $inhalte_info, $s, $var, $e, $r, $an);
				break;

			case "miss":

				$mtc = $inhalte_check["aktion"];
				$mk1 = explode(":", $inhalte_check["basis1"]);
				$mkx1 = $mk1[0];
				$mky1 = $mk1[1];
				$mkz1 = $mk1[2];

				$mk2 = explode(":", $inhalte_check["basis2"]);
				$mkx2 = $mk2[0];
				$mky2 = $mk2[1];
				$mkz2 = $mk2[2];

				$result1 = mysql_query("SELECT * FROM genesis_basen WHERE koordx='$mkx1' AND koordy='$mky1' AND koordz='$mkz1'");
				$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
				$result2 = mysql_query("SELECT * FROM genesis_basen WHERE koordx='$mkx2' AND koordy='$mky2' AND koordz='$mkz2'");
				$inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
				$result3 = mysql_query("SELECT * FROM genesis_spieler WHERE name='" . $inhalte1["name"] . "'");
				$inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);
				$result4 = mysql_query("SELECT * FROM genesis_spieler WHERE name='" . $inhalte2["name"] . "'");
				$inhalte4 = mysql_fetch_array($result4, MYSQL_ASSOC);

				if (!$inhalte1["name"]) {
					$inhalte1["name"] = "unbesetzt";
					$inhalte1["bname"] = "unbekannt";
				}
				if (!$inhalte2["name"]) {
					$inhalte2["name"] = "unbesetzt";
					$inhalte2["bname"] = "unbekannt";
				}
				$en = $inhalte_check["endzeit"] + ($inhalte_check["endzeit"] - $inhalte_check["startzeit"]);
				// if ($mtc == 1 && (time() > 1198753200 || time() < 1198494000)) {
				// mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', ress='". $inhalte_check["ress"] ."', einheiten='". $inhalte_check["einheiten"] ."' WHERE id='$aid'");
				// }
				if ($mtc == 1) {
					unset($mr1, $mr2, $mr3, $mr4, $mr5);
					$outa = "Angriff";
					$me = explode("||", $inhalte_check["einheiten"]);
					$mr = explode("||", $inhalte_check["ress"]);
					$resschance = 100;
					$alle = 0;
					$alle2 = 0;

					if ($inhalte4["urlaub"] < time() && ($inhalte4["deffs"] < 8 || $inhalte4["log"] < (time() - 86400 * 7))) {
						unset($zerstoert, $pkteatt, $pktedeff, $prio1, $prio2, $prio3, $prio4, $prio5, $sklade, $skd, $ska, $vertn2, $vertn3, $vertn1, $vert, $vert_verlust, $einheit_verlust, $einheit1_verlust, $einheit2_verlust, $einheit3_verlust, $meatt, $einheit1, $werte, $medeff, $einheit, $werte_lade, $werte_angr1, $werte_angr2, $werte_vert1, $werte_vert2, $angr_wert1, $angr_wert2, $vert_wert1, $vert_wert2, $lade_wert);

						$resultdeff = mysql_query("SELECT id FROM genesis_aktionen WHERE basis2='" . $inhalte_check["basis2"] . "' and typ='miss' and aktion='4' and zusatz>5");
						$anzdeff = mysql_num_rows($resultdeff) + 1;

						$resultdeff = mysql_query("SELECT id,basis1,einheiten FROM genesis_aktionen WHERE basis2='" . $inhalte_check["basis2"] . "' and typ='miss' and aktion='4' and zusatz>5");
						while ($inhaltedeff = mysql_fetch_array($resultdeff, MYSQL_ASSOC)) {
							$medeff = explode("||", $inhaltedeff["einheiten"]);
							$resultsa = mysql_query("SELECT punkte,forsch1,forsch3,forsch4,forsch6 FROM genesis_spieler WHERE basis1='" . $inhaltedeff["basis1"] . "' OR basis2='" . $inhaltedeff["basis1"] . "'");
							$inhaltesa = mysql_fetch_array($resultsa, MYSQL_ASSOC);
							for ($i = 1; $i <= 8; $i++) {
								$einheit1[$i] += $medeff[$i-1];
								$resultw = mysql_query("SELECT wert3,wert4,wert5 FROM genesis_infos WHERE typ='prod$i'");
								$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
								$wa = angr("prod$i", $werte["wert3"], $inhaltesa["forsch1"], $inhaltesa["forsch3"]);
								$wv = vert("prod$i", $werte["wert4"], $inhaltesa["forsch4"], $inhaltesa["forsch3"]);
								$angr_wert2 += $wa * $medeff[$i-1];
								$vert_wert2 += $wv * $medeff[$i-1];
								$skd[$inhaltedeff["id"]] += $wa * $medeff[$i-1] + $wv * $medeff[$i-1];
								$werte_angr2[$i] += $wa;
								$werte_vert2[$i] += $wv;
							}
							$pktedeff += $inhaltedeff["punkte"];
						}

						if ($inhalte_check["zusatz"] == 1) {
							$resultatt = mysql_query("SELECT id FROM genesis_aktionen WHERE basis2='" . $inhalte_check["basis2"] . "' and typ='miss' and zusatz='1' and aktion='1' and endzeit>='" . ($inhalte_check["endzeit"]) . "' and endzeit<='" . ($inhalte_check["endzeit"] + 60) . "' and id<>'$aid'");
							$anzatt = mysql_num_rows($resultatt) + 1;

							$resultatt = mysql_query("SELECT id,basis1,einheiten,ress FROM genesis_aktionen WHERE basis2='" . $inhalte_check["basis2"] . "' and typ='miss' and zusatz='1' and aktion='1' and endzeit>='" . ($inhalte_check["endzeit"]) . "' and endzeit<='" . ($inhalte_check["endzeit"] + 60) . "' and id<>'$aid'");
							while ($inhalteatt = mysql_fetch_array($resultatt, MYSQL_ASSOC)) {
								$meatt = explode("||", $inhalteatt["einheiten"]);
								$resultsa = mysql_query("SELECT punkte,forsch1,forsch3,forsch4,forsch6 FROM genesis_spieler WHERE basis1='" . $inhalteatt["basis1"] . "' OR basis2='" . $inhalteatt["basis1"] . "'");
								$inhaltesa = mysql_fetch_array($resultsa, MYSQL_ASSOC);
								for ($i = 1; $i <= 8; $i++) {
									$einheit[$i] += $meatt[$i-1];
									$resultw = mysql_query("SELECT wert3,wert4,wert5 FROM genesis_infos WHERE typ='prod$i'");
									$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
									$wa = angr("prod$i", $werte["wert3"], $inhaltesa["forsch1"], $inhaltesa["forsch3"]);
									$wv = vert("prod$i", $werte["wert4"], $inhaltesa["forsch4"], $inhaltesa["forsch3"]);
									$angr_wert1 += $wa * $meatt[$i-1];
									$vert_wert1 += $wv * $meatt[$i-1];
									$ska[$inhalteatt["id"]] += $wa * $meatt[$i-1] + $wv * $meatt[$i-1];
									$sklade[$inhalteatt["id"]] += ladekap($werte["wert5"], $inhaltesa["forsch6"]) * $meatt[$i-1];
									$werte_angr1[$i] += $wa;
									$werte_vert1[$i] += $wv;
									$lade_wert += ladekap($werte["wert5"], $inhaltesa["forsch6"]) * $meatt[$i-1];
									$werte_lade[$i] += ladekap($werte["wert5"], $inhaltesa["forsch6"]);
								}
								$merr = explode("||", $inhalteatt["ress"]);
								$prio1 += $merr[0];
								$prio2 += $merr[1];
								$prio3 += $merr[2];
								$prio4 += $merr[3];
								$prio5 += $merr[4];
								$lade_wert -= $merr[5];
								$pkteatt += $inhalteatt["punkte"];
							}
						} else {
							$anzatt = 1;
						}

						$pkteatt += $inhalte3["punkte"];
						$pktedeff += $inhalte4["punkte"];
						$pkteatt = round($pkteatt / $anzatt, 0);
						$pktedeff = round($pktedeff / $anzdeff, 0);

						for ($i = 1; $i <= 3; $i++) {
							$vert[$i] = $inhalte2["vert$i"];
							$vert_verlust[$i] = 0;
							$resultw = mysql_query("SELECT wert3,wert4 FROM genesis_infos WHERE typ='vert$i'");
							$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
							$vert_werte_att[$i] = angr("vert$i", $werte["wert3"], $inhalte4["forsch1"], $inhalte4["forsch3"]);
							$vert_werte[$i] = vert("vert$i", $werte["wert4"], $inhalte4["forsch4"], $inhalte4["forsch3"]);
						}

						$ska[0] = 1;
						$skd[0] = 1;

						for ($i = 1; $i <= 8; $i++) {
							$einheit[$i] += $me[$i-1];
							$einheit1[$i] += $inhalte2["prod$i"];

							if ($me[$i-1] > 0 || $inhalte2["prod$i"] > 0) {
								$resultw = mysql_query("SELECT wert3,wert4,wert5 FROM genesis_infos WHERE typ='prod$i'");
								$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
								if ($inhalte4["gesperrt"] < time()) {
									$wa = angr("prod$i", $werte["wert3"], $inhalte3["forsch1"], $inhalte3["forsch3"]);
									$wv = vert("prod$i", $werte["wert4"], $inhalte3["forsch4"], $inhalte3["forsch3"]);
									$angr_wert1 += $wa * $me[$i-1];
									$vert_wert1 += $wv * $me[$i-1];
									$werte_angr1[$i] += $wa;
									$werte_vert1[$i] += $wv;
									$ska[0] += $wa * $me[$i-1] + $wv * $me[$i-1];
									$wa = angr("prod$i", $werte["wert3"], $inhalte4["forsch1"], $inhalte4["forsch3"]);
									$wv = vert("prod$i", $werte["wert4"], $inhalte4["forsch4"], $inhalte4["forsch3"]);
									$angr_wert2 += $wa * $inhalte2["prod$i"];
									$vert_wert2 += $wv * $inhalte2["prod$i"];
									$werte_angr2[$i] += $wa;
									$werte_vert2[$i] += $wv;
									$skd[0] += $wa * $inhalte2["prod$i"] + $wv * $inhalte2["prod$i"];
								}
								$lade_wert += ladekap($werte["wert5"], $inhalte3["forsch6"]) * $me[$i-1];
								$werte_lade[$i] += ladekap($werte["wert5"], $inhalte3["forsch6"]);
							}

							$einheit_verlust[$i] = 0;
							$einheit1_verlust[$i] = 0;
							$einheit2_verlust[$i] = 0;
							$einheit3_verlust[$i] = 0;

							$werte_lade[$i] = round($werte_lade[$i] / $anzatt, 0);
							$werte_angr1[$i] = round($werte_angr1[$i] / $anzatt, 0);
							$werte_vert1[$i] = round($werte_vert1[$i] / $anzatt, 0);
							$werte_angr2[$i] = round($werte_angr2[$i] / $anzdeff, 0);
							$werte_vert2[$i] = round($werte_vert2[$i] / $anzdeff, 0);
						}

						$lade_wert -= $mr[5];
						// Gesperrt
						if ($inhalte4["gesperrt"] < time()) {
							$angr_wert1a = $angr_wert1;
							$vert_wert1a = $vert_wert1;

							$angr_wert2a = $angr_wert2 + angr_immu($inhalte4["forsch1"], $inhalte2["konst15"]);
							$vert_wert2a = $vert_wert2 + vert_immu($inhalte4["forsch4"], $inhalte2["konst15"]);
							$skd[0] += angr_immu($inhalte4["forsch1"], $inhalte2["konst15"]);
							$skd[0] += vert_immu($inhalte4["forsch4"], $inhalte2["konst15"]);
							$angr_wert2 += angr_immu($inhalte4["forsch1"], $inhalte2["konst15"]);
							$vert_wert2 += vert_immu($inhalte4["forsch4"], $inhalte2["konst15"]);

							for ($i = 1; $i <= 3; $i++) {
								$angr_wert2a += $vert_werte_att[$i] * $vert[$i];
								$vert_wert2a += $vert_werte[$i] * $vert[$i];
								$skd[0] += $vert_werte_att[$i] * $vert[$i];
								$skd[0] += $vert_werte[$i] * $vert[$i];
								$angr_wert2 += $vert_werte_att[$i] * $vert[$i];
								$vert_wert2 += $vert_werte[$i] * $vert[$i];
							}

							mt_srand((double)microtime() * 1000000);
							$randval = mt_rand(-50, 50) / 1000 + 1;
							$angr_wert1 = $angr_wert1 * $randval;
							$vert_wert1 = $vert_wert1 * $randval;

							$verh1 = $angr_wert1 + $vert_wert1 + 1;
							$verh2 = $angr_wert2 + $vert_wert2 + 1;
							$verh = round(sqrt($verh1 / $verh2), 4);

							if ($verh > 1) {
								$faktor1 = 1;
								$faktor2 = round(1 / $verh, 4);
							} else {
								$faktor1 = $verh;
								$faktor2 = 1;
							}
							$angr_wert1 = round($angr_wert1 * $faktor1, 0) + 1;
							$vert_wert1 = round($vert_wert1 * $faktor1, 0) + 1;
							$angr_wert2 = round($angr_wert2 * $faktor2, 0) + 1;
							$vert_wert2 = round($vert_wert2 * $faktor2, 0) + 1;
							$resschance = round($angr_wert1 / ($vert_wert2 + 1) * 60, 1);
							$diff = $angr_wert1 - (vert_immu($inhalte4["forsch4"], $inhalte2["konst15"]) * 3);

							$zp[1] = 3;
							$zp[2] = 2.2;
							$zp[3] = 1.7;
							$zp[4] = 1.3;
							$zp[5] = 1;
							$zp[6] = 0.3;
							$zp[7] = 0.5;

							$vp[1] = 3;
							$vp[2] = 2;
							$vp[3] = 1;

							if ($diff > 0) {
								unset($i, $j, $k, $feld, $ges);
								$ges = 1;
								for ($i = 1; $i <= 3; $i++) {
									$ges += $vert[$i];
								}
								for ($i = 1; $i <= 3; $i++) {
									for ($j = 1; $j <= (round(10 / $ges * $vp[$i] * $vert[$i], 0) + 1); $j++) {
										$feld[$k + $j] = $i;
									}
									$k += $j-1;
								} while ($diff > 0) {
									$zufall = mt_rand(1, $k);
									$zufall = $feld[$zufall];
									if ($vert[$zufall] > $vert_verlust[$zufall] && $diff >= $vert_werte[$zufall]) {
										$diff -= $vert_werte[$zufall];
										$vert_verlust[$zufall]++;
									} elseif ($vert[1] <= $vert_verlust[1] && $vert[2] <= $vert_verlust[2] && $vert[3] <= $vert_verlust[3]) {
										break;
									} else {
										$diff -= $vert_werte[$zufall];
									}
								}
							}

							if ($diff > 0) {
								unset($i, $j, $k, $feld, $ges);
								$ges = 1;
								for ($i = 1; $i <= 7; $i++) {
									$ges += $einheit1[$i];
								}
								for ($i = 1; $i <= 7; $i++) {
									for ($j = 1; $j <= (round(30 / $ges * $zp[$i] * $einheit1[$i], 0) + 1); $j++) {
										$feld[$k + $j] = $i;
									}
									$k += $j-1;
								} while ($diff > 0) {
									$zufall = mt_rand(1, $k);
									$zufall = $feld[$zufall];
									if ($einheit1[$zufall] > $einheit1_verlust[$zufall] && $diff >= round($werte_vert2[$zufall] * $faktor2, 1)) {
										$diff -= round($werte_vert2[$zufall] * $faktor2, 1);
										$einheit1_verlust[$zufall]++;
									} elseif ($einheit1[1] <= $einheit1_verlust[1] && $einheit1[2] <= $einheit1_verlust[2] && $einheit1[3] <= $einheit1_verlust[3] && $einheit1[4] <= $einheit1_verlust[4] && $einheit1[5] <= $einheit1_verlust[5] && $einheit1[6] <= $einheit1_verlust[6] && $einheit1[7] <= $einheit1_verlust[7]) {
										$alle2 = 1;
										break;
									} else {
										$diff -= round($werte_vert2[$zufall] * $faktor2, 1);
									}
								}

								if ($diff > 0) {
									if ($einheit1[8] > 0) {
										$einheit1_verlust[8] = $einheit1[8];
										mysql_query("UPDATE genesis_spieler SET keimzelle='0' WHERE id='" . $inhalte4["id"] . "'");
									}
								}
							}

							$diff2 = $angr_wert2;
							if ($diff2 > 0) {
								unset($i, $j, $k, $feld, $ges);
								$ges = 1;
								for ($i = 1; $i <= 7; $i++) {
									$ges += $einheit[$i];
								}
								for ($i = 1; $i <= 7; $i++) {
									for ($j = 1; $j <= (round(30 / $ges * $zp[$i] * $einheit[$i], 0) + 1); $j++) {
										$feld[$k + $j] = $i;
									}
									$k += $j-1;
								} while ($diff2 > 0) {
									$zufall = mt_rand(1, $k);
									$zufall = $feld[$zufall];
									if ($einheit[$zufall] > $einheit_verlust[$zufall] && $diff2 >= round($werte_vert1[$zufall] * $faktor1, 1)) {
										$diff2 -= round($werte_vert1[$zufall] * $faktor1, 1);
										$einheit_verlust[$zufall]++;
										$lade_wert -= $werte_lade[$zufall];
									} elseif ($einheit[1] <= $einheit_verlust[1] && $einheit[2] <= $einheit_verlust[2] && $einheit[3] <= $einheit_verlust[3] && $einheit[4] <= $einheit_verlust[4] && $einheit[5] <= $einheit_verlust[5] && $einheit[6] <= $einheit_verlust[6] && $einheit[7] <= $einheit_verlust[7]) {
										$alle = 1;
										$resschance = 0;
										break;
									} else {
										$diff2 -= round($werte_vert1[$zufall] * $faktor1, 1);
									}
								}
							}
							unset($i, $j, $k, $feld, $ges);
						} // Gesperrt
						$kampfpkt = 0;
						$kampfpkt1 = 0;
						$sladeg = $lade_wert;

						$r1n = $inhalte2["ress1"];
						$r2n = $inhalte2["ress2"];
						$r3n = $inhalte2["ress3"];
						$r4n = $inhalte2["ress4"];
						$r5n = $inhalte2["ress5"];

						do {
							$bid = strgen(10);
							$resultbid = mysql_query("SELECT id FROM genesis_berichte WHERE id='$bid'");
						} while (mysql_fetch_array($resultbid, MYSQL_ASSOC));
						mysql_free_result($resultbid);

						for ($i = 1; $i <= 8; $i++) {
							$resultw = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
							$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
							$kampfpkt += $einheit1_verlust[$i] * $werte["wert2"];
							$kampfpkt1 += $einheit_verlust[$i] * $werte["wert2"];
						}
						for ($i = 1; $i <= 3; $i++) {
							$resultw = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='vert$i'");
							$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
							$kampfpkt += $vert_verlust[$i] * $werte["wert2"];
						}

						$skag = $angr_wert1a + $vert_wert1a + 1;
						$skdg = $angr_wert2a + $vert_wert2a + 1;

						$resultpkt = mysql_query("SELECT ROUND((sum(punkte) / count(*)),0) FROM genesis_spieler");
						$inhaltepkt = mysql_fetch_array($resultpkt, MYSQL_NUM);

						$allitag2 = "";
						if ($inhalte4["alli"] != 0) {
							$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte4["alli"] . "'");
							$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
							$allitag2 = "[" . $allitag["tag"] . "] ";
						}

						unset($einheit2, $einheit2_verlust, $kpd);
						$resultdeff = mysql_query("SELECT id,basis1,einheiten,zusatz FROM genesis_aktionen WHERE basis2='" . $inhalte_check["basis2"] . "' and typ='miss' and aktion='4' and zusatz>5");
						while ($inhaltedeff = mysql_fetch_array($resultdeff, MYSQL_ASSOC)) {
							$medeff = explode("||", $inhaltedeff["einheiten"]);
							unset($medeff3, $medeff1, $medeff2);
							for ($i = 1; $i <= 8; $i++) {
								$medeff1[$i-1] = 0;
								$medeff2[$i-1] = 0;
								if ($medeff[$i-1] > 0) {
									$faktor = $einheit1[$i] / $medeff[$i-1];
									$medeff1[$i-1] = intval($einheit1_verlust[$i] / $faktor);
									$medeff2[$i-1] = $medeff[$i-1] - $medeff1[$i-1];
									$einheit2_verlust[$i] += $medeff1[$i-1];
									$einheit2[$i] += $medeff2[$i-1];
								}
								$medeff3 .= $medeff2[$i-1] . "||";
							}

							$kpktd = round($kampfpkt1 / ($skdg / $skd[$inhaltedeff["id"]]), 0);

							if ($medeff3 == "0||0||0||0||0||0||0||0||") {
								mysql_query("DELETE FROM genesis_aktionen WHERE id='" . $inhaltedeff["id"] . "'");
							} else {
								mysql_query("UPDATE genesis_aktionen SET einheiten='$medeff3' WHERE id='" . $inhaltedeff["id"] . "'");
							}
							$allitag = "";
							$resulta1 = mysql_query("SELECT id,name,alli,punkte,kampfpkt,bonus FROM genesis_spieler WHERE basis1='" . $inhaltedeff["basis1"] . "' or basis2='" . $inhaltedeff["basis1"] . "'");
							$inhaltea1 = mysql_fetch_array($resulta1, MYSQL_ASSOC);

							$bonuspkt = round($kpktd * (($pkteatt - $inhaltea1["punkte"]) / $inhaltepkt[0]), 0);

							if ($inhaltea1["alli"] != 0) {
								$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhaltea1["alli"] . "'");
								$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
								$allitag = "[" . $allitag["tag"] . "] ";
							}
							$qry = "INSERT INTO genesis_deff ";
							$qry .= "(id,alli,name,koords,";
							$qry .= "prod1,prod2,prod3,prod4,prod5,prod6,prod7,prod8,prodv1,prodv2,prodv3,prodv4,prodv5,prodv6,prodv7,prodv8,";
							$qry .= "kp,bonus";
							$qry .= ") VALUES ";
							$qry .= "('$bid','$allitag','" . $inhaltea1["name"] . "','" . $inhaltedeff["basis1"] . "',";
							$qry .= "'" . $medeff[0] . "','" . $medeff[1] . "','" . $medeff[2] . "','" . $medeff[3] . "','" . $medeff[4] . "','" . $medeff[5] . "','" . $medeff[6] . "','" . $medeff[7] . "',";
							$qry .= "'" . $medeff1[0] . "','" . $medeff1[1] . "','" . $medeff1[2] . "','" . $medeff1[3] . "','" . $medeff1[4] . "','" . $medeff1[5] . "','" . $medeff1[6] . "','" . $medeff1[7] . "',";
							$qry .= "'$kpktd','$bonuspkt')";
							mysql_query($qry);
							$msg = "Deine verteidigenden Exo-Zellen bei <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "\">" . $inhalte2["name"] . "</a> ($mkx2:$mky2:$mkz2) wurden angegriffen!<br/><a href=\"bericht.php?id=$bid\" target=_blank>Zum Bericht</a>";
							mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhaltea1["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
							$kpktd += $inhaltea1["kampfpkt"];
							$bonuspkt += $inhaltea1["bonus"];
							mysql_query("UPDATE genesis_spieler SET bonus='$bonuspkt', kampfpkt='$kpktd', ereignisse=ereignisse+1 WHERE id='" . $inhaltea1["id"] . "'");
							mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhaltea1["name"] . "', '0', '" . time() . "', 'ENDE: miss 1 - " . $inhaltedeff["basis1"] . " - $mkx2:$mky2:$mkz2 - $bid')");
						}

						if ($inhalte_check["zusatz"] == 1) {
							unset($einheit3, $einheit3_verlust, $mratt, $mratt1, $mratt2, $mratt3, $mratt4, $mratt5, $kpa, $kapatt);
							$resultatt = mysql_query("SELECT id,basis1,einheiten,ress,endzeit,startzeit FROM genesis_aktionen WHERE basis2='" . $inhalte_check["basis2"] . "' and typ='miss' and zusatz='1' and aktion='1' and endzeit>='" . ($inhalte_check["endzeit"]) . "' and endzeit<='" . ($inhalte_check["endzeit"] + 60) . "' and id<>'$aid'");
							while ($inhalteatt = mysql_fetch_array($resultatt, MYSQL_ASSOC)) {
								unset($meatt3, $meatt1, $meatt2, $mratt1, $mratt2, $mratt3, $mratt4, $mratt5, $kapatt);
								$meatt = explode("||", $inhalteatt["einheiten"]);
								$mratt = explode("||", $inhalteatt["ress"]);
								for ($i = 1; $i <= 8; $i++) {
									$meatt1[$i-1] = 0;
									$meatt2[$i-1] = 0;
									if ($meatt[$i-1] > 0) {
										$faktor = $einheit[$i] / $meatt[$i-1];
										$meatt1[$i-1] = intval($einheit_verlust[$i] / $faktor);
										$meatt2[$i-1] = $meatt[$i-1] - $meatt1[$i-1];
										$kapatt += $meatt2[$i-1] * $werte_lade[$i];
										$einheit3[$i] += $meatt2[$i-1];
										$einheit3_verlust[$i] += $meatt1[$i-1];
									}
									$meatt3 .= $meatt2[$i-1] . "||";
								}

								$kpkta = round($kampfpkt / ($skag / $ska[$inhalteatt["id"]]), 0);

								if ($meatt3 == "0||0||0||0||0||0||0||0||") {
									$mratt1 = 0;
									$mratt2 = 0;
									$mratt3 = 0;
									$mratt4 = 0;
									$mratt5 = 0;
									mysql_query("DELETE FROM genesis_aktionen WHERE id='" . $inhalteatt["id"] . "'");
								} else {
									if ($resschance > 50) {
										$kap = round($kapatt / $sladeg, 10);
										for ($i = 1; $i <= 5; $i++) {
											eval("\$prio$i = \$mratt[" . ($i-1) . "];");
											eval("\$mrn$i = round($kap * (\$inhalte2[\"ress$i\"] - round(resskap(\$inhalte2[\"konst" . ($i + 8) . "\"]) / 10)));");
											eval("if (\$mrn$i < 0) { \$mrn$i = 0; }");
										}
										eval("\$an = \$kapatt - \$mrn$prio1;");
										if ($an > 0) {
											eval("\$kapatt -= \$mrn$prio1;");
											eval("\$mratt$prio1 = \$mrn$prio1;");
											eval("\$an = \$kapatt - \$mrn$prio2;");
											if ($an > 0) {
												eval("\$kapatt -= \$mrn$prio2;");
												eval("\$mratt$prio2 = \$mrn$prio2;");
												eval("\$an = \$kapatt - \$mrn$prio3;");
												if ($an > 0) {
													eval("\$kapatt -= \$mrn$prio3;");
													eval("\$mratt$prio3 = \$mrn$prio3;");
													eval("\$an = \$kapatt - \$mrn$prio4;");
													if ($an > 0) {
														eval("\$kapatt -= \$mrn$prio4;");
														eval("\$mratt$prio4 = \$mrn$prio4;");
														eval("\$an = \$kapatt - \$mrn$prio5;");
														if ($an > 0) {
															eval("\$kapatt -= \$mrn$prio5;");
															eval("\$mratt$prio5 = \$mrn$prio5;");
														} else {
															eval("\$mratt$prio5 = \$kapatt;");
														}
													} else {
														eval("\$mratt$prio4 = \$kapatt;");
													}
												} else {
													eval("\$mratt$prio3 = \$kapatt;");
												}
											} else {
												eval("\$mratt$prio2 = \$kapatt;");
											}
										} else {
											eval("\$mratt$prio1 = \$kapatt;");
										}
									}
									if ($mratt1 == "") $mratt1 = 0;
									if ($mratt2 == "") $mratt2 = 0;
									if ($mratt3 == "") $mratt3 = 0;
									if ($mratt4 == "") $mratt4 = 0;
									if ($mratt5 == "") $mratt5 = 0;
									$r1n -= $mratt1;
									$r2n -= $mratt2;
									$r3n -= $mratt3;
									$r4n -= $mratt4;
									$r5n -= $mratt5;
									$mrnatt = "$mratt1||$mratt2||$mratt3||$mratt4||$mratt5||0";
									$en2 = $inhalteatt["endzeit"] + ($inhalteatt["endzeit"] - $inhalteatt["startzeit"]);
									mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalteatt["endzeit"] . "', endzeit='$en2', einheiten='$meatt3', ress='$mrnatt', zusatz='0' WHERE id='" . $inhalteatt["id"] . "'");
								}

								$allitag = "";
								$resulta1 = mysql_query("SELECT id,name,alli,punkte,kampfpkt,bonus FROM genesis_spieler WHERE basis1='" . $inhalteatt["basis1"] . "' or basis2='" . $inhalteatt["basis1"] . "'");
								$inhaltea1 = mysql_fetch_array($resulta1, MYSQL_ASSOC);

								$bonuspkt = round((round(($mratt1 + $mratt2 + $mratt3 + $mratt4 + $mratt5) / 10000, 0) + $kpkta) * (($pktedeff - $inhaltea1["punkte"]) / $inhaltepkt[0]), 0);

								if ($inhaltea1["alli"] != 0) {
									$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhaltea1["alli"] . "'");
									$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
									$allitag = "[" . $allitag["tag"] . "] ";
								}

								$qry = "INSERT INTO genesis_att ";
								$qry .= "(id,alli,name,koords,";
								$qry .= "prod1,prod2,prod3,prod4,prod5,prod6,prod7,prod8,prodv1,prodv2,prodv3,prodv4,prodv5,prodv6,prodv7,prodv8,";
								$qry .= "ress1,ress2,ress3,ress4,ress5,kp,bonus";
								$qry .= ") VALUES ";
								$qry .= "('$bid','$allitag','" . $inhaltea1["name"] . "','" . $inhalteatt["basis1"] . "',";
								$qry .= "'" . $meatt[0] . "','" . $meatt[1] . "','" . $meatt[2] . "','" . $meatt[3] . "','" . $meatt[4] . "','" . $meatt[5] . "','" . $meatt[6] . "','" . $meatt[7] . "',";
								$qry .= "'" . $meatt1[0] . "','" . $meatt1[1] . "','" . $meatt1[2] . "','" . $meatt1[3] . "','" . $meatt1[4] . "','" . $meatt1[5] . "','" . $meatt1[6] . "','" . $meatt1[7] . "',";
								$qry .= "'$mratt1','$mratt2','$mratt3','$mratt4','$mratt5','$kpkta','$bonuspkt')";
								mysql_query($qry);
								$msg = "Du hast <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . "</a> ($mkx2:$mky2:$mkz2) angegriffen!<br/><a href=\"bericht.php?id=$bid\" target=_blank>Zum Bericht</a>";
								mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhaltea1["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
								$kpkta += $inhaltea1["kampfpkt"];
								$bonuspkt += $inhaltea1["bonus"];
								mysql_query("UPDATE genesis_spieler SET bonus='$bonuspkt', kampfpkt='$kpkta', ereignisse=ereignisse+1 WHERE id='" . $inhaltea1["id"] . "'");
								mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhaltea1["name"] . "', '0', '" . time() . "', 'ENDE: miss 1 - " . $inhalteatt["basis1"] . " - $mkx2:$mky2:$mkz2 - $bid')");
							}
						}
						unset($mratt, $mratt1, $mratt2, $mratt3, $mratt4, $mratt5, $kpa, $kapatt);

						$kapatt = 0;
						for ($i = 1; $i <= 8; $i++) {
							$einheit_verlust[$i] = $einheit_verlust[$i] - $einheit3_verlust[$i];
							$einheit1_verlust[$i] = $einheit1_verlust[$i] - $einheit2_verlust[$i];
							eval("\$men$i = \$me[" . ($i-1) . "] - \$einheit_verlust[$i];");
							eval("\$kapatt += \$men$i * \$werte_lade[$i];");
							eval("\$en$i = \$inhalte2[\"prod$i\"] - \$einheit1_verlust[$i];");
						}
						for ($i = 1; $i <= 3; $i++) {
							eval("\$vertn$i = \$inhalte2[\"vert$i\"] - \$vert_verlust[$i];");
							eval("if (\$vertn$i < 0) { \$vertn$i = 0; }");
						}
						$kap = round($kapatt / $sladeg, 10);
						if ($anzatt == 1) {
							$kap = 1;
						}
						for ($i = 1; $i <= 5; $i++) {
							eval("\$prio$i = \$mr[" . ($i-1) . "];");
							eval("\$mrn$i = round($kap * (\$inhalte2[\"ress$i\"] - round(resskap(\$inhalte2[\"konst" . ($i + 8) . "\"]) / 10)));");
							eval("if (\$mrn$i < 0) { \$mrn$i = 0; }");
						}
						if ($resschance > 50) {
							eval("\$an = \$kapatt - \$mrn$prio1;");
							if ($an > 0) {
								eval("\$kapatt -= \$mrn$prio1;");
								eval("\$mratt$prio1 = \$mrn$prio1;");
								eval("\$an = \$kapatt - \$mrn$prio2;");
								if ($an > 0) {
									eval("\$kapatt -= \$mrn$prio2;");
									eval("\$mratt$prio2 = \$mrn$prio2;");
									eval("\$an = \$kapatt - \$mrn$prio3;");
									if ($an > 0) {
										eval("\$kapatt -= \$mrn$prio3;");
										eval("\$mratt$prio3 = \$mrn$prio3;");
										eval("\$an = \$kapatt - \$mrn$prio4;");
										if ($an > 0) {
											eval("\$kapatt -= \$mrn$prio4;");
											eval("\$mratt$prio4 = \$mrn$prio4;");
											eval("\$an = \$kapatt - \$mrn$prio5;");
											if ($an > 0) {
												eval("\$kapatt -= \$mrn$prio5;");
												eval("\$mratt$prio5 = \$mrn$prio5;");
											} else {
												eval("\$mratt$prio5 = \$kapatt;");
											}
										} else {
											eval("\$mratt$prio4 = \$kapatt;");
										}
									} else {
										eval("\$mratt$prio3 = \$kapatt;");
									}
								} else {
									eval("\$mratt$prio2 = \$kapatt;");
								}
							} else {
								eval("\$mratt$prio1 = \$kapatt;");
							}
						}
						if ($mratt1 == "") $mratt1 = 0;
						if ($mratt2 == "") $mratt2 = 0;
						if ($mratt3 == "") $mratt3 = 0;
						if ($mratt4 == "") $mratt4 = 0;
						if ($mratt5 == "") $mratt5 = 0;
						$r1n -= $mratt1;
						$r2n -= $mratt2;
						$r3n -= $mratt3;
						$r4n -= $mratt4;
						$r5n -= $mratt5;

						$kpkta = round($kampfpkt / ($skag / $ska[0]), 0);
						$kpktd = round($kampfpkt1 / ($skdg / $skd[0]), 0);

						$allitag1 = "";
						if ($inhalte3["alli"] != 0) {
							$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte3["alli"] . "'");
							$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
							$allitag1 = "[" . $allitag["tag"] . "] ";
						}
						$allitag2 = "";
						if ($inhalte4["alli"] != 0) {
							$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte4["alli"] . "'");
							$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
							$allitag2 = "[" . $allitag["tag"] . "] ";
						}

						$bonuspkta = round((round(($mratt1 + $mratt2 + $mratt3 + $mratt4 + $mratt5) / 10000, 0) + $kpkta) * (($pktedeff - $inhalte3["punkte"]) / $inhaltepkt[0]), 0);

						$qry = "INSERT INTO genesis_att ";
						$qry .= "(id,alli,name,koords,";
						$qry .= "prod1,prod2,prod3,prod4,prod5,prod6,prod7,prod8,prodv1,prodv2,prodv3,prodv4,prodv5,prodv6,prodv7,prodv8,";
						$qry .= "ress1,ress2,ress3,ress4,ress5,kp,bonus";
						$qry .= ") VALUES ";
						$qry .= "('$bid','$allitag1','" . $inhalte1["name"] . "','" . $inhalte_check["basis1"] . "',";
						$qry .= "'" . $me[0] . "','" . $me[1] . "','" . $me[2] . "','" . $me[3] . "','" . $me[4] . "','" . $me[5] . "','" . $me[6] . "','" . $me[7] . "',";
						$qry .= "'" . $einheit_verlust[1] . "','" . $einheit_verlust[2] . "','" . $einheit_verlust[3] . "','" . $einheit_verlust[4] . "','" . $einheit_verlust[5] . "','" . $einheit_verlust[6] . "','" . $einheit_verlust[7] . "','" . $einheit_verlust[8] . "',";
						$qry .= "'$mratt1','$mratt2','$mratt3','$mratt4','$mratt5','$kpkta','$bonuspkta')";
						mysql_query($qry);

						$bonuspktd = round($kpktd * (($pkteatt - $inhalte4["punkte"]) / $inhaltepkt[0]), 0);

						$qry = "INSERT INTO genesis_deff ";
						$qry .= "(id,alli,name,koords,";
						$qry .= "prod1,prod2,prod3,prod4,prod5,prod6,prod7,prod8,vert1,vert2,vert3,prodv1,prodv2,prodv3,prodv4,prodv5,prodv6,prodv7,prodv8,vertv1,vertv2,vertv3,";
						$qry .= "kp,bonus";
						$qry .= ") VALUES ";
						$qry .= "('$bid','$allitag2','" . $inhalte2["name"] . "','" . $inhalte_check["basis2"] . "',";
						$qry .= "'" . $inhalte2["prod1"] . "','" . $inhalte2["prod2"] . "','" . $inhalte2["prod3"] . "','" . $inhalte2["prod4"] . "','" . $inhalte2["prod5"] . "','" . $inhalte2["prod6"] . "','" . $inhalte2["prod7"] . "','" . $inhalte2["prod8"] . "','" . $inhalte2["vert1"] . "','" . $inhalte2["vert2"] . "','" . $inhalte2["vert3"] . "',";
						$qry .= "'" . $einheit1_verlust[1] . "','" . $einheit1_verlust[2] . "','" . $einheit1_verlust[3] . "','" . $einheit1_verlust[4] . "','" . $einheit1_verlust[5] . "','" . $einheit1_verlust[6] . "','" . $einheit1_verlust[7] . "','" . $einheit1_verlust[8] . "','" . $vert_verlust[1] . "','" . $vert_verlust[2] . "','" . $vert_verlust[3] . "',";
						$qry .= "'$kpktd','$bonuspktd')";
						mysql_query($qry);

						$qry = "INSERT INTO genesis_berichte ";
						$qry .= "(id,typ,zeit,koords,zusatz) VALUES ";
						$qry .= "('$bid','1','" . $inhalte_check["endzeit"] . "','" . $inhalte_check["basis2"] . "','$zerstoert')";
						mysql_query($qry);

						$msg1 = "Dein Neogen (" . $inhalte_check["basis2"] . ") wurde angegriffen!<br/><a href=\"bericht.php?id=$bid\" target=_blank>Zum Bericht</a>";
						$msg2 = "Du hast <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> angegriffen!<br/><a href=\"bericht.php?id=$bid\" target=_blank>Zum Bericht</a>";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg1'), ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg2')");

						$kpac = $kpkta;
						$kpdc = $kpktd;
						$kpkta += $inhalte3["kampfpkt"];
						$kpktd += $inhalte4["kampfpkt"];
						$bonuspkta += $inhalte3["bonus"];
						$bonuspktd += $inhalte4["bonus"];

						$mrn = "$mratt1||$mratt2||$mratt3||$mratt4||$mratt5||0";
						$men = "$men1||$men2||$men3||$men4||$men5||$men6||$men7||$men8";

						mysql_query("UPDATE genesis_basen SET ress1='$r1n', ress2='$r2n', ress3='$r3n', ress4='$r4n', ress5='$r5n', prod1='$en1', prod2='$en2', prod3='$en3', prod4='$en4', prod5='$en5', prod6='$en6', prod7='$en7', prod8='$en8', vert1='$vertn1', vert2='$vertn2', vert3='$vertn3' WHERE id='" . $inhalte2["id"] . "'");
						mysql_query("UPDATE genesis_spieler SET bonus='$bonuspkta', kampfpkt='$kpkta', attcount=(attcount+" . $kpdc . "), ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");

						if ($resschance > 50 && ($inhalte3["alli"] != $inhalte4["alli"] || $inhalte3["alli"] == 0)) {
							mysql_query("UPDATE genesis_spieler SET bonus='$bonuspktd', kampfpkt='$kpktd', attcount=(attcount+" . $kpac . "), atttime='" . time() . "', ereignisse=ereignisse+1, deffs=deffs+1 WHERE id='" . $inhalte4["id"] . "'");
						} else {
							mysql_query("UPDATE genesis_spieler SET bonus='$bonuspktd', kampfpkt='$kpktd', attcount=(attcount+" . $kpac . "), atttime='" . time() . "', ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
						}
						mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 1 - " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2 - $bid')");

						if ($alle == 1 || $men == "0||0||0||0||0||0||0||0") {
							mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
						} else {
							mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', ress='$mrn', einheiten='$men', zusatz='0' WHERE id='$aid'");
						}
					} else {
						$mrn = "0||0||0||0||0||0";
						mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', ress='$mrn', zusatz='0' WHERE id='$aid'");
						$msg = "Der Angriff auf <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> konnte nicht durchgeführt werden, da der Spieler geschützt ist.";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'START: rueckkehr von $mkx2:$mky2:$mkz2 nach " . $inhalte_check["basis1"] . "')");
					}
				}
				if ($mtc == 2) {
					$outa = "Transport";
					$me = explode("||", $inhalte_check["einheiten"]);
					$mr = explode("||", $inhalte_check["ress"]);
					$mrn = "";
					$msg3 = "";
					$msg4 = "";
					for ($i = 1; $i <= 5; $i++) {
						eval("\$ress" . $i . "n = \$inhalte2[\"ress$i\"] + \$mr[" . ($i-1) . "];");
						eval("\$ress" . $i . "k = resskap(\$inhalte2[\"konst" . ($i + 8) . "\"]);");
						eval("if (\$ress" . $i . "n > \$ress" . $i . "k) { \$ress" . $i . "n = \$ress" . $i . "k; }");
						eval("\$ress" . $i . "r = \$ress" . $i . "n - \$inhalte2[\"ress$i\"];");
						eval("if (\$ress" . $i . "r > \$mr[" . ($i-1) . "]) { \$ress" . $i . "r = \$mr[" . ($i-1) . "]; }");
						eval("\$mrn .= (\$mr[" . ($i-1) . "] - \$ress" . $i . "r) .\"||\";");
						eval("if (\$ress" . $i . "r > 0) { \$msg3 .= num2typ($i) .\": \". format(\$ress" . $i . "r) .\"<br/>\n\"; }");
					}
					if ($inhalte_check["zusatz"] == 2 && (($inhalte3["basis1"] == "$mkx1:$mky1:$mkz1" && $inhalte3["basis2"] == "$mkx2:$mky2:$mkz2") || ($inhalte3["basis2"] == "$mkx1:$mky1:$mkz1" && $inhalte3["basis1"] == "$mkx2:$mky2:$mkz2"))) {
						//$msg = "Du hast folgende Nährstoffe und Exo-Zellen von $mkx1:$mky1:$mkz1 zu $mkx2:$mky2:$mkz2 verlagert:<br/>\n$msg3<br/>\n$msg4";
						//mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						//mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
						mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', zusatz='0' WHERE id='$aid'");
					} elseif ($inhalte_check["zusatz"] == 1 && (($inhalte3["basis1"] == "$mkx1:$mky1:$mkz1" && $inhalte3["basis2"] == "$mkx2:$mky2:$mkz2") || ($inhalte3["basis2"] == "$mkx1:$mky1:$mkz1" && $inhalte3["basis1"] == "$mkx2:$mky2:$mkz2"))) {
						for ($i = 1; $i <= 7; $i++) {
							$result_info = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
							$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
							eval("\$prod" . $i . "n = \$inhalte2[\"prod$i\"] + \$me[" . ($i-1) . "];");
							if ($me[($i-1)] > 0) $msg4 .= $inhalte_info["bezeichnung"] . " : " . format($me[($i-1)]) . "<br/>\n";
						}
						$msg = "Du hast folgende Nährstoffe und Exo-Zellen von $mkx1:$mky1:$mkz1 zu $mkx2:$mky2:$mkz2 verlagert:<br/>\n$msg3<br/>\n$msg4";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
						mysql_query("UPDATE genesis_basen SET ress1='$ress1n', ress2='$ress2n', ress3='$ress3n', ress4='$ress4n', ress5='$ress5n', prod1='$prod1n', prod2='$prod2n', prod3='$prod3n', prod4='$prod4n', prod5='$prod5n', prod6='$prod6n', prod7='$prod7n' WHERE id='" . $inhalte2["id"] . "'");
						mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
					} else {
						if ($msg3 == "") $msg3 = "keine";
						if ($inhalte3["id"] != $inhalte4["id"]) {
							$msg1 = "Ein Transport von <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte3["id"] . "&k=$mkx1:$mky1:$mkz1\">" . $inhalte1["name"] . " ($mkx1:$mky1:$mkz1)</a> hat dir folgende Nährstoffe auf $mkx2:$mky2:$mkz2 geliefert:<br/>\n$msg3";
							$msg2 = "Du hast <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> folgende Nährstoffe geliefert:<br/>\n$msg3";
							mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg1'), ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg2')");
							mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
							mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
						} else {
							$msg1 = "Du hast folgende Nährstoffe von $mkx1:$mky1:$mkz1 nach $mkx2:$mky2:$mkz2 transportiert:<br/>\n$msg3";
							mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg1')");
							mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
						}
						mysql_query("UPDATE genesis_basen SET ress1='$ress1n', ress2='$ress2n', ress3='$ress3n', ress4='$ress4n', ress5='$ress5n' WHERE id='" . $inhalte2["id"] . "'");
						mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', ress='$mrn', zusatz='0' WHERE id='$aid'");
					}
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 2 - " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2 - " . $inhalte_check["einheiten"] . " - " . $inhalte_check["ress"] . " - " . $inhalte_check["zusatz"] . "')");
				}

				if ($mtc == 3) {
					$outa = "Spionage";
					$me = explode("||", $inhalte_check["einheiten"]);
					$mrn = "0||0||0||0||0||0";
					$entd = false;
					mt_srand((double)microtime() * 1000000);
					$randval = mt_rand(-50, 50) / 1000 + 1;
					$diff = pow(($inhalte3["forsch8"] * 1.5 + $inhalte1["konst17"] + sqrt($me[5] / 50) + 1) / ($inhalte4["forsch8"] * 1.5 + $inhalte2["konst17"] + 1), 3) * $randval;
					$verl = round($me[5] * (0.2 / pow($diff, 1.4)), 0);
					if (((($inhalte4["forsch8"] * 1.5 + $inhalte2["konst17"] + 1) * 40 + 100) < ($inhalte3["forsch8"] * 1.5 + $inhalte1["konst17"] + 1) * 10 + $me[5]) && ($diff < 80)) $entd = true;
					if ($diff > 2 && $entd == false) $verl = 0;
					if ($verl >= $me[5]) {
						$verl = $me[5];
						mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
					} else {
						$men = str_replace("||" . $me[5] . "||", "||" . ($me[5] - $verl) . "||", $inhalte_check["einheiten"]);
						mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', einheiten='$men', ress='$mrn', zusatz='0' WHERE id='$aid'");
					}
					$filter = "";
					for ($i = 1; $i <= 41; $i++) {
						$randval = mt_rand(-30 / $diff, 10 * $diff);
						if ($randval < 0) {
							$filter .= "0";
						} else {
							$filter .= "1";
						}
					}

					if ($diff >= 0.8) {
						do {
							$bid = strgen(10);
							$resultbid = mysql_query("SELECT id FROM genesis_berichte WHERE id='$bid'");
						} while (mysql_fetch_array($resultbid, MYSQL_ASSOC));
						mysql_free_result($resultbid);

						$allitag1 = "";
						if ($inhalte3["alli"] != 0) {
							$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte3["alli"] . "'");
							$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
							$allitag1 = "[" . $allitag["tag"] . "] ";
						}

						$qry = "INSERT INTO genesis_att ";
						$qry .= "(id,alli,name,koords,prod6,prodv6) VALUES ";
						$qry .= "('$bid','$allitag1','" . $inhalte1["name"] . "','" . $inhalte_check["basis1"] . "','" . $me[5] . "','$verl')";
						mysql_query($qry);

						$resultdeff = mysql_query("SELECT id,basis1,einheiten,zusatz FROM genesis_aktionen WHERE basis2='" . $inhalte_check["basis2"] . "' and typ='miss' and aktion='4' and zusatz>5");
						while ($inhaltedeff = mysql_fetch_array($resultdeff, MYSQL_ASSOC)) {
							$medeff = explode("||", $inhaltedeff["einheiten"]);
							$allitag = "";
							$resulta1 = mysql_query("SELECT name,alli FROM genesis_spieler WHERE basis1='" . $inhaltedeff["basis1"] . "'");
							$inhaltea1 = mysql_fetch_array($resulta1, MYSQL_ASSOC);
							if ($inhaltea1["alli"] != 0) {
								$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhaltea1["alli"] . "'");
								$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
								$allitag = "[" . $allitag["tag"] . "] ";
							}
							$qry = "INSERT INTO genesis_deff ";
							$qry .= "(id,alli,name,koords,";
							$qry .= "prod1,prod2,prod3,prod4,prod5,prod6,prod7) VALUES ";
							$qry .= "('$bid','$allitag','" . $inhaltea1["name"] . "','" . $inhaltedeff["basis1"] . "',";
							$qry .= "'" . $medeff[0] . "','" . $medeff[1] . "','" . $medeff[2] . "','" . $medeff[3] . "','" . $medeff[4] . "','" . $medeff[5] . "','" . $medeff[6] . "')";
							mysql_query($qry);
						}

						$allitag2 = "";
						if ($inhalte4["alli"] != 0) {
							$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte4["alli"] . "'");
							$allitag = mysql_fetch_array($resulta, MYSQL_ASSOC);
							$allitag2 = "[" . $allitag["tag"] . "] ";
						}

						$qry = "INSERT INTO genesis_deff ";
						$qry .= "(id,alli,name,koords,";
						$qry .= "prod1,prod2,prod3,prod4,prod5,prod6,prod7,prod8,vert1,vert2,vert3) VALUES ";
						$qry .= "('$bid','$allitag2','" . $inhalte2["name"] . "','" . $inhalte_check["basis2"] . "',";
						$qry .= "'" . $inhalte2["prod1"] . "','" . $inhalte2["prod2"] . "','" . $inhalte2["prod3"] . "','" . $inhalte2["prod4"] . "','" . $inhalte2["prod5"] . "','" . $inhalte2["prod6"] . "','" . $inhalte2["prod7"] . "','" . $inhalte2["prod8"] . "','" . $inhalte2["vert1"] . "','" . $inhalte2["vert2"] . "','" . $inhalte2["vert3"] . "')";
						mysql_query($qry);
						// Osternspecial (zusatz)
						/*
						unset($zs);
						if ($inhalte2["eier"] > 0) $zs = $inhalte2["eier"] . " Ostereier entdeckt";
						*/

						$qry = "INSERT INTO genesis_berichte ";
						$qry .= "(id,typ,zeit,koords,";
						$qry .= "konst1,konst2,konst3,konst4,konst5,konst6,konst7,konst8,konst9,konst10,konst11,konst12,konst13,konst14,konst15,konst16,konst17,";
						$qry .= "forsch1,forsch2,forsch3,forsch4,forsch5,forsch6,forsch7,forsch8,ress1,ress2,ress3,ress4,ress5,filter";
						$qry .= " ) VALUES ";
						$qry .= "('$bid','2','" . $inhalte_check["endzeit"] . "','" . $inhalte_check["basis2"] . "',";
						$qry .= "'" . $inhalte2["konst1"] . "','" . $inhalte2["konst2"] . "','" . $inhalte2["konst3"] . "','" . $inhalte2["konst4"] . "','" . $inhalte2["konst5"] . "','" . $inhalte2["konst6"] . "','" . $inhalte2["konst7"] . "','" . $inhalte2["konst8"] . "','" . $inhalte2["konst9"] . "','" . $inhalte2["konst10"] . "','" . $inhalte2["konst11"] . "','" . $inhalte2["konst12"] . "','" . $inhalte2["konst13"] . "','" . $inhalte2["konst14"] . "','" . $inhalte2["konst15"] . "','" . $inhalte2["konst16"] . "','" . $inhalte2["konst17"] . "',";
						$qry .= "'" . $inhalte4["forsch1"] . "','" . $inhalte4["forsch2"] . "','" . $inhalte4["forsch3"] . "','" . $inhalte4["forsch4"] . "','" . $inhalte4["forsch5"] . "','" . $inhalte4["forsch6"] . "','" . $inhalte4["forsch7"] . "','" . $inhalte4["forsch8"] . "','" . $inhalte2["ress1"] . "','" . $inhalte2["ress2"] . "','" . $inhalte2["ress3"] . "','" . $inhalte2["ress4"] . "','" . $inhalte2["ress5"] . "','" . $filter . "')";
						mysql_query($qry);
						// Osternspecial (zusatz)
					}

					if ($diff > 2 && $entd == false) {
						$msg = "Deine Spionage-Partikel haben <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> unentdeckt infiltriert.<br/>Folgende Informationen wurden erlangt:<br/><a href=\"bericht.php?id=$bid\" target=_blank>Zum Bericht</a>";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
					} elseif ($diff >= 0.8 && ($diff <= 2 || $entd == true)) {
						$msg1 = "Ein Spionageversuch von <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte3["id"] . "&k=$mkx1:$mky1:$mkz1\">" . $inhalte1["name"] . " ($mkx1:$mky1:$mkz1)</a> wurde auf $mkx2:$mky2:$mkz2 entdeckt.<br/>Folgende Informationen wurden erlangt:<br/><a href=\"bericht.php?id=$bid\" target=_blank>Zum Bericht</a>";
						$msg2 = "Deine Spionage-Partikel wurden bei <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> entdeckt.<br/>Sie konnten aber folgende Informationen erlangen:<br/><a href=\"bericht.php?id=$bid\" target=_blank>Zum Bericht</a>";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg1'),('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg2')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
					} elseif ($diff < 0.8) {
						$msg1 = "Ein Spionageversuch von <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte3["id"] . "&k=$mkx1:$mky1:$mkz1\">" . $inhalte1["name"] . " ($mkx1:$mky1:$mkz1)</a> wurde auf $mkx2:$mky2:$mkz2 erfolgreich abgewehrt.<br/>$verl Spionage-Partikel wurden zerstört.";
						$msg2 = "Der Spionageauftrag bei <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> konnte nicht erfolgreich ausgeführt werden.<br/>$verl Spionage-Partikel wurden beim Versuch das Neogen zu infiltrieren zerstört.";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg1'),('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg2')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
					}
				}

				if ($mtc == 4) {
					if ($inhalte_check["zusatz"] >= 1 && $inhalte_check["zusatz"] <= 5) {
						$zusatz = $inhalte_check["endzeit"] - $inhalte_check["startzeit"];
						$en = $inhalte_check["endzeit"] + ($inhalte_check["zusatz"] * 3600);
						mysql_query("UPDATE genesis_aktionen SET aktion='4', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', zusatz='$zusatz' WHERE id='$aid'");
						$msg1 = "Mission hat <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> erreicht und verteidigt nun " . $inhalte_check["zusatz"] . " Stunde(n).";
						$msg2 = "<a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte3["id"] . "&k=$mkx1:$mky1:$mkz1\">" . $inhalte1["name"] . " ($mkx1:$mky1:$mkz1)</a> verteidigt dich nun " . $inhalte_check["zusatz"] . " Stunde(n).";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg1'),('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg2')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
						mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 4 " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2 (" . $inhalte_check["zusatz"] . ")')");
					} else {
						$en = $inhalte_check["endzeit"] + $inhalte_check["zusatz"];
						mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', zusatz='0' WHERE id='$aid'");
						$msg = "Mission hat Verteidigung bei <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "\"&k=$mkx2:$mky2:$mkz2>" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> beendet und kehrt nun zurück.";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 4 - " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2')");
					}
				}

				if ($mtc == 6) {
					if ($inhalte2["name"] = "unbesetzt" && $inhalte2["bname"] = "unbekannt") {
						$zufb = intval(mt_rand(1, 6));
						$mr = explode("||", $inhalte_check["ress"]);
						for ($i = 1; $i <= 5; $i++) {
							eval("\$ress" . $i . "n = \$mr[" . ($i-1) . "];");
							eval("if (\$ress" . $i . "n > resskap(0)) { \$ress" . $i . "n = resskap(0); }");
						}
						mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
						mysql_query("UPDATE genesis_spieler SET basis2='$mkx2:$mky2:$mkz2' WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("UPDATE genesis_basen SET name='" . $inhalte3["name"] . "', bname='Neogen', ress1='$ress1n', ress2='$ress2n', ress3='$ress3n', ress4='$ress4n', ress5='$ress5n', konst1='1', typ='1', bild='$zufb', resszeit='" . time() . "' WHERE koordx='$mkx2' AND koordy='$mky2' AND koordz='$mkz2'");
						$msg = "Die Keimzelle hat $mkx2:$mky2:$mkz2 erreicht und beginnt nun mit der Zellteilung.";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 6 - " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2 (ok)')");
					} else {
						$en = $inhalte_check["endzeit"] + ($inhalte_check["endzeit"] - $inhalte_check["startzeit"]);
						mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', zusatz='0' WHERE id='$aid'");
						$msg = "Die Keimzelle konnte bei <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> keinen geeigneten Nährboden finden und kehrt deshalb zurück.";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
						mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 6 - " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2')");
					}
				}
				// Osternspecial
				if ($mtc == 7) {
					/*
					$zuf = 0;
					if ($inhalte2["eier"] > 0) {
						$me = explode("||", $inhalte_check["einheiten"]);
						mt_srand((double)microtime() * 1000000);
						if ($inhalte2["eier"] > $me[6]) {
							$zuf = intval(mt_rand(1, $me[6]));
						} else {
							$zuf = intval(mt_rand(1, $inhalte2["eier"]));
						}
						mysql_query("UPDATE genesis_basen SET eier=eier-" . $zuf . " WHERE koordx='$mkx2' AND koordy='$mky2' AND koordz='$mkz2'");
						$msg = "Du hast bei <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . "($mkx2:$mky2:$mkz2)</a> " . $zuf . " Ostereier gefunden.";
						$msg2 = "<a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte3["id"] . "&k=$mkx1:$mky1:$mkz1\">" . $inhalte1["name"] . " ($mkx1:$mky1:$mkz1)</a> hat dir " . $zuf . " Ostereier gestohlen.";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg'),('0','" . $inhalte4["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg2')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte4["id"] . "'");
					} else {
						$msg = "Du hast bei <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . "($mkx2:$mky2:$mkz2)</a> leider keine Ostereier gefunden.";
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
					}
					mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
					mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 7 - " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2 ($zuf)')");
					$en = $inhalte_check["endzeit"] + ($inhalte_check["endzeit"] - $inhalte_check["startzeit"]);
					mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', zusatz='$zuf' WHERE id='$aid'");
					*/
					$en = $inhalte_check["endzeit"] + ($inhalte_check["endzeit"] - $inhalte_check["startzeit"]);
					mysql_query("UPDATE genesis_aktionen SET aktion='5', startzeit='" . $inhalte_check["endzeit"] . "', endzeit='$en', zusatz='0' WHERE id='$aid'");
				}
				// Osternspecial
				if ($mtc == 5) {
					$outa = "Rückkehr";
					$mr = explode("||", $inhalte_check["ress"]);
					$me = explode("||", $inhalte_check["einheiten"]);
					for ($i = 1; $i <= 8; $i++) {
						eval("\$men$i = \$me[$i-1] + \$inhalte1[\"prod$i\"];");
					}
					$msg3 = "";
					for ($i = 1; $i <= 5; $i++) {
						eval("\$ress" . $i . "n = \$inhalte1[\"ress$i\"] + \$mr[" . ($i-1) . "];");
						eval("\$ress" . $i . "k = resskap(\$inhalte1[\"konst" . ($i + 8) . "\"]);");
						eval("if (\$ress" . $i . "n > \$ress" . $i . "k) { \$ress" . $i . "n = \$ress" . $i . "k; }");
						eval("if (\$mr[" . ($i-1) . "] > 0) { \$msg3 .= num2typ($i) .\": \". format(\$mr[" . ($i-1) . "]) .\"<br/>\n\"; }");
					}
					$ress5n += $mr[5];
					if ($ress5n > $ress5k) $ress5n = $ress5k;
					mysql_query("UPDATE genesis_basen SET ress1='$ress1n', ress2='$ress2n', ress3='$ress3n', ress4='$ress4n', ress5='$ress5n', prod1='$men1', prod2='$men2', prod3='$men3', prod4='$men4', prod5='$men5', prod6='$men6', prod7='$men7', prod8='$men8' WHERE id='" . $inhalte1["id"] . "'");
					$msg = "Eine Mission ist von <a href=\"game.php?id=#SID#&nav=info&t=spieler" . $inhalte4["id"] . "&k=$mkx2:$mky2:$mkz2\">" . $inhalte2["name"] . " ($mkx2:$mky2:$mkz2)</a> zurückgekehrt!";
					if ($msg3 != "") $msg .= "<br/>Folgende Nährstoffe wurden mitgebracht:<br/>\n$msg3";
					// Osternspecial
					/*
					if ($inhalte_check["zusatz"] > 0) {
						$msg .= "<br/>" . $inhalte_check["zusatz"] . " Ostereier wurden mitgebracht";
						mysql_query("UPDATE genesis_basen SET eier=eier+" . $inhalte_check["zusatz"] . " WHERE id='" . $inhalte1["id"] . "'");
					}
					*/
					// Osternspecial
					if ($inhaltec["missmsg"] == 1 || $msg3 != "" || $inhalte_check["zusatz"] > 0) {
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte3["id"] . "','" . $inhalte_check["endzeit"] . "','ereignis','Mission','$msg')");
						mysql_query("UPDATE genesis_spieler SET ereignisse=ereignisse+1 WHERE id='" . $inhalte3["id"] . "'");
					}
					mysql_query("DELETE FROM genesis_aktionen WHERE id='$aid'");
					if ($me[0] > 0 || $me[1] > 0 || $me[2] > 0 || $me[3] > 0 || $me[4] > 0 || $me[6] > 0 || $me[7] > 0) {
						mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('" . $inhalte3["name"] . "', '0', '" . time() . "', 'ENDE: miss 5 - " . $inhalte_check["basis1"] . " - $mkx2:$mky2:$mkz2 - " . $inhalte_check["einheiten"] . " - " . $inhalte_check["ress"] . "')");
					}
				}
				unset($msg, $msg2, $msg1, $msg3, $kpkta, $kpktd, $kampfpkt, $kampfpkt1, $kpa, $kpd, $bonuspkta, $bonuspktd, $bonuspkt, $filter, $randval, $merr, $qry, $endzeitneu, $bid, $anz, $ein, $e, $r, $aid, $ress1n, $ress2n, $ress3n, $ress4n, $ress5n, $mra, $mrn, $men, $me, $en, $mkx1, $mkx2, $mky1, $mky2, $mkz1, $mkz2, $inhalte1, $inhalte2, $inhalte3, $inhalte4, $result1, $result2, $result3, $result4, $ress1, $ress2, $ress3, $ress4, $ress5);
				break;
		}
	}
}

unset($inhaltepkt, $qry, $endzeitneu, $bid, $anz, $ein, $e, $an, $r, $aid, $ress1n, $ress2n, $ress3n, $ress4n, $ress5n, $mra, $en, $mkx1, $mkx2, $mky1, $mky2, $mkz1, $mkz2, $inhalte1, $inhalte2, $inhalte3, $inhalte4, $result1, $result2, $result3, $result4, $result_prod, $inhalte_prod, $result_forsch, $inhalte_forsch, $stufe_check, $forsch_check, $konst_check, $stufe_check, $inhalte_konst, $result_konst, $ress1, $ress2, $ress3, $ress4, $ress5, $ze, $mr1, $mr2, $mr3, $mr4, $mr5);

?>