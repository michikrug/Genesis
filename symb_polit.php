<?php

include_once "parser.inc.php";

$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
$palli = isset($_REQUEST["palli"]) ? $_REQUEST["palli"] : NULL;
$ptyp = isset($_REQUEST["ptyp"]) ? $_REQUEST["ptyp"] : NULL;
$pdauer = isset($_REQUEST["pdauer"]) ? $_REQUEST["pdauer"] : NULL;
$pbis = isset($_REQUEST["pbis"]) ? $_REQUEST["pbis"] : NULL;
$pende = isset($_REQUEST["pende"]) ? $_REQUEST["pende"] : NULL;
$pid = isset($_REQUEST["pid"]) ? $_REQUEST["pid"] : NULL;

$aid = $inhalte_s["alli"];
// 1 - Start Freund
// 2 - Ende Freund
// 3 - Start Feind
// 4 - Ende Feind
// 5 - Start Krieg
// 6 - Ende Krieg
// 7 - Start Bündnis
// 8 - Ende Bündnis
if ($aid > 0) {
	$ausgabe .= form("game.php?id=$id&nav=$nav");
	$ausgabe .= table(600, "bg");
	$ausgabe .= tr(td(7, "head", "Symbiosen Politik"));

	$result_symb = mysql_query("SELECT * FROM genesis_allianzen WHERE id='$aid'");
	$inhalte_symb = mysql_fetch_array($result_symb, MYSQL_ASSOC);
	$symb = $inhalte_symb["tag"];

	if ($aktion == "Erstellen" && $palli > 0 && $ptyp > 0 && $pdauer != "") {
		if (($ptyp == 5 && $pdauer > 6 && $pdauer < 29 && $inhalte_symb["anz"] > 2) || $ptyp != 5) {
			$result_a = mysql_query("SELECT id, tag, punktem FROM genesis_allianzen WHERE id='$palli'");
			if ($inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC)) {
				if (($ptyp == 5 && ($inhalte_symb["punktem"] / 5) < $inhalte_a["punktem"] && $inhalte_symb["punktem"] > 0) || $ptyp != 5) {
					$result_a = mysql_query("SELECT id FROM genesis_politik WHERE ((alli1='$aid' AND alli2='$palli') OR (alli2='$aid' AND alli1='$palli' and (typ='5' or typ='7'))) AND (bis>'" . time() . "' OR bis='0')");
					if (mysql_fetch_array($result_a, MYSQL_ASSOC)) {
						$fehler = "Verhältnis konnte nicht erstellt werden, da bereits ein Verhältnis zu dieser Symbiose vorhanden ist.";
					} else {
						$pdauer = intval($pdauer);
						if ($pdauer > 0) {
							$pbis = (time() + ($pdauer * 86400));
						} else {
							$pbis = 0;
						}
						mysql_query("INSERT INTO genesis_politik (alli1,alli2,typ,von,bis,accept) VALUES ('$aid', '$palli', '$ptyp', '" . time() . "', '$pbis', '0')");
						$result_a1 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='$aid'");
						$inhalte_a1 = mysql_fetch_array($result_a1, MYSQL_ASSOC);
						$result_a2 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='$palli'");
						$inhalte_a2 = mysql_fetch_array($result_a2, MYSQL_ASSOC);
						$result_as = mysql_query("SELECT id FROM genesis_spieler WHERE alli='$palli' and alli_rang='0'");
						$inhalte_as = mysql_fetch_array($result_as, MYSQL_ASSOC);
						if ($ptyp == 7) {
							mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news,newsalt,meldung) VALUES ('$sid','" . $inhalte_as["id"] . "','" . time() . "','news','Bündnisanfrage','Ihr habt eine Bündnisanfrage von [" . $inhalte_a1["tag"] . "] erhalten.','0','0')");
							mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news,newsalt,meldung) VALUES ('$sid','$palli','" . time() . "','alli_news','Ihr habt eine Bündnisanfrage von [" . $inhalte_a1["tag"] . "] erhalten.','1','0')");
						} else {
							if ($ptyp == 5) {
								mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news,newsalt,meldung) VALUES ('$sid','" . $inhalte_as["id"] . "','" . time() . "','news','Kriegserklärung','[" . $inhalte_a1["tag"] . "] hat euch Krieg erklärt.','0','0')");
								mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news,newsalt,meldung) VALUES ('$sid','$palli','" . time() . "','alli_news','[" . $inhalte_a1["tag"] . "] hat euch Krieg erklärt.','1','0')");
							}
							mysql_query("INSERT INTO genesis_history (alli1,alli2,typ,zeit,zusatz) VALUES ('" . $inhalte_a1["tag"] . "','" . $inhalte_a2["tag"] . "','$ptyp','" . time() . "','0')");
						}
					}
				} else {
					$fehler = "Deine Symbiose ist zu stark um [" . $inhalte_a["tag"] . "] den Krieg zu erklären!<br>(oder ihr habt keinerlei Zellpunkte)";
				}
			} else {
				$fehler = "Verhältnis konnte nicht erstellt werden, da diese Symbiose nicht vorhanden ist.";
			}
		} else {
			$fehler = "Ein Krieg muss mindestens 7 Tage und maximal 28 Tage dauern.<br>Desweiteren muss deine Symbiose min. 3 Mitglieder haben.";
		}
	} elseif ($aktion == "end" && $pende > 0) {
		$result_p = mysql_query("SELECT * FROM genesis_politik WHERE id='$pende' and (alli1='$aid' OR (alli2='$aid' and (typ='5' or (typ='7' and accept='1'))))");
		if ($inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC)) {
			if (($inhalte_p["typ"] == 5 && time() > $inhalte_p["bis"]) || ($inhalte_p["typ"] != 5)) {
				$zusatz = 0;
				$result_a1 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli1"] . "'");
				$inhalte_a1 = mysql_fetch_array($result_a1, MYSQL_ASSOC);
				$result_a2 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli2"] . "'");
				$inhalte_a2 = mysql_fetch_array($result_a2, MYSQL_ASSOC);
				if ($inhalte_p["typ"] == 5) {
					unset($kp1, $kp2, $kp1a, $kp2a, $ress11, $ress21, $ress31, $ress41, $ress51, $ress12, $ress22, $ress32, $ress42, $ress52);
$qry = "SELECT t1.id,t1.name,t3.name,t1.kp,t3.kp,
t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t1.prodv8,
t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.prodv8,t3.vertv1,t3.vertv2,t3.vertv3,
t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5
FROM genesis_att t1
LEFT JOIN genesis_berichte t2 USING(id)
LEFT JOIN genesis_deff t3 USING(id)
WHERE t1.alli='[" . $inhalte_a1["tag"] . "]' AND t3.alli='[" . $inhalte_a2["tag"] . "]'
AND t2.typ='1' AND t2.zeit>'" . $inhalte_p["von"] . "' AND t2.zeit<'" . time() . "'
ORDER BY t1.id, t1.name";
					$result = mysql_query($qry);
					unset($namea, $nameb, $id, $anz1);
					while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
						$anz1++;
						if ($id != $berichte[0]) {
							for ($i = 1; $i <= 8; $i++) {
								$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
								$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
								$kp2a += $berichte[($i + 4)] * $inhalte[0];
							}
							$kp1a += $berichte[3];
						} elseif ($id == $berichte[0] && $namea != $berichte[1]) {
							for ($i = 1; $i <= 8; $i++) {
								$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
								$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
								$kp2a += $berichte[($i + 4)] * $inhalte[0];
							}
							$kp1a += $berichte[3];
							$ress11 += $berichte[24];
							$ress21 += $berichte[25];
							$ress31 += $berichte[26];
							$ress41 += $berichte[27];
							$ress51 += $berichte[28];
						}
						$id = $berichte[0];
						$namea = $berichte[1];
						$nameb = $berichte[2];
					}
$qry = "SELECT t1.id,t1.name,t3.name,t1.kp,t3.kp,
t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t1.prodv8,
t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.prodv8,t3.vertv1,t3.vertv2,t3.vertv3,
t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5
FROM genesis_att t1
LEFT JOIN genesis_berichte t2 USING(id)
LEFT JOIN genesis_deff t3 USING(id)
WHERE t1.alli='[" . $inhalte_a2["tag"] . "]' AND t3.alli='[" . $inhalte_a1["tag"] . "]'
AND t2.typ='1' AND t2.zeit>'" . $inhalte_p["von"] . "' AND t2.zeit<'" . time() . "'
ORDER BY t1.id, t1.name";
					$result = mysql_query($qry);
					unset($namea, $nameb, $id, $anz2);
					while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
						$anz2++;
						if ($id != $berichte[0]) {
							for ($i = 1; $i <= 8; $i++) {
								$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
								$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
								$kp1a += $berichte[($i + 4)] * $inhalte[0];
							}
							$kp2a += $berichte[3];
						} elseif ($id == $berichte[0] && $namea != $berichte[1]) {
							for ($i = 1; $i <= 8; $i++) {
								$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
								$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
								$kp1a += $berichte[($i + 4)] * $inhalte[0];
							}
							$kp2a += $berichte[3];
							$ress12 += $berichte[24];
							$ress22 += $berichte[25];
							$ress32 += $berichte[26];
							$ress42 += $berichte[27];
							$ress52 += $berichte[28];
						}
						$id = $berichte[0];
						$namea = $berichte[1];
						$nameb = $berichte[2];
					}
					$kp1a += round(($ress11 + $ress21 + $ress31 + $ress41 + $ress51) / 10000);
					$kp2a += round(($ress12 + $ress22 + $ress32 + $ress42 + $ress52) / 10000);
					if (($kp1a - $kp2a > ($kp1a / 100 * 5)) || ($kp1a > $kp2a && $anz2 == 0)) {
						$zusatz = 1;
					} elseif (($kp2a - $kp1a > ($kp2a / 100 * 5)) || ($kp2a > $k1pa && $anz1 == 0)) {
						$zusatz = 2;
					} else {
						$zusatz = 3;
					}
					unset($kp1, $kp2, $kp1a, $kp2a);
				}
				mysql_query("DELETE FROM genesis_politik WHERE id='$pende'");
				if (($inhalte_p["typ"] == 7 && $inhalte_p["accept"] == 1) || $inhalte_p["typ"] != 7) {
					mysql_query("INSERT INTO genesis_history (alli1,alli2,typ,zeit,zusatz) VALUES ('" . $inhalte_a1["tag"] . "','" . $inhalte_a2["tag"] . "','" . ($inhalte_p["typ"] + 1) . "','" . time() . "','$zusatz')");
				}
			} else {
				$fehler = "Ein Krieg kann erst nach Ablauf der angesetzen Dauer beendet werden!";
			}
		}
	} elseif ($aktion == "accept" && $pid > 0) {
		$result_p = mysql_query("SELECT * FROM genesis_politik WHERE id='$pid' and alli2='$aid' and accept='0'");
		if ($inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC)) {
			$result_a1 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli1"] . "'");
			$inhalte_a1 = mysql_fetch_array($result_a1, MYSQL_ASSOC);
			$result_a2 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli2"] . "'");
			$inhalte_a2 = mysql_fetch_array($result_a2, MYSQL_ASSOC);
			if ($inhalte_p["bis"] > 0) {
				$neubis = ($inhalte_p["bis"] + (time() - $inhalte_p["von"]));
			} else {
				$neubis = 0;
			}
			mysql_query("UPDATE genesis_politik SET von='" . time() . "', bis='$neubis', accept='1' WHERE id='$pid'");
			mysql_query("INSERT INTO genesis_history (alli1,alli2,typ,zeit,zusatz) VALUES ('" . $inhalte_a1["tag"] . "','" . $inhalte_a2["tag"] . "','" . $inhalte_p["typ"] . "','" . time() . "','0')");
			mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news,newsalt,meldung) VALUES ('$sid','" . $inhalte_p["alli1"] . "','" . time() . "','alli_news','Eure Bündnisanfrage an [" . $inhalte_a2["tag"] . "] wurde akzeptiert.','1','0')");
		}
	} elseif ($aktion == "deny" && $pid > 0) {
		$result_p = mysql_query("SELECT * FROM genesis_politik WHERE id='$pid' and alli2='$aid' and accept='0'");
		if ($inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC)) {
			$result_a1 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli1"] . "'");
			$inhalte_a1 = mysql_fetch_array($result_a1, MYSQL_ASSOC);
			$result_a2 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli2"] . "'");
			$inhalte_a2 = mysql_fetch_array($result_a2, MYSQL_ASSOC);
			mysql_query("DELETE FROM genesis_politik WHERE id='$pid' and alli2='$aid' and accept='0'");
			mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news,newsalt,meldung) VALUES ('$sid','" . $inhalte_p["alli1"] . "','" . time() . "','alli_news','Eure Bündnisanfrage an [" . $inhalte_a2["tag"] . "] wurde abgelehnt.','1','0')");
		}
	}

	$ausgabe .= tr(td(5, "center", "<br>"));
	if (isset($fehler)) {
		$ausgabe .= tr(td(5, "center", $fehler));
		$ausgabe .= tr(td(5, "center", "<br>"));
	}

	$ausgabe .= tr(td(5, "head", "Neues Verhältnis erstellen"));
	$ausgabe .= tr(td(0, "navi", "Symbiose") . td(0, "navi", "Verhältnis") . td(2, "navi", "Dauer (in Tagen, 0 für unbegrenzt)") . td(0, "navi", "&nbsp;"));
	$result_a = mysql_query("SELECT id,tag FROM genesis_allianzen WHERE id<>'$aid' ORDER BY tag");
	$out = "<select name=\"palli\">\n";
	while ($inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC)) {
		$result_a2 = mysql_query("SELECT id FROM genesis_politik WHERE ((alli1='$aid' AND alli2='" . $inhalte_a["id"] . "') OR (alli2='$aid' AND alli1='" . $inhalte_a["id"] . "'  and (typ='5' or typ='7'))) AND (bis>'" . time() . "' OR bis='0')");
		if (!mysql_fetch_array($result_a2, MYSQL_ASSOC)) {
			$out .= "<option value=\"" . $inhalte_a["id"] . "\">[" . $inhalte_a["tag"] . "]\n";
		}
	}
	$out .= "</select>";
	$ausgabe .= tr(td(0, "center", $out) . td(0, "center", "<select name=\"ptyp\"><option value=1>Freund</option><option value=3>Feind</option><option value=5>Krieg</option><option value=7>Bündnis</option></select>") . td(2, "center", input("zahl", "pdauer", "0")) . td(0, "center", input("submit", "aktion", "Erstellen")));

	$ausgabe .= tr(td(5, "center", "<br>"));

	$ausgabe1 = tr(td(5, "head", "Aktive Verhältnisse")) . tr(td(0, "head", "Symbiose") . td(0, "head", "Verhältnis") . td(0, "head", "Von") . td(0, "head", "Bis") . td(0, "head", "&nbsp;"));
	$result_polit = mysql_query("SELECT * FROM genesis_politik WHERE alli1='$aid' OR (alli2='$aid' and (typ='5' or (typ='7' and accept='1'))) ORDER BY typ,von DESC");
	while ($inhalte_polit = mysql_fetch_array($result_polit, MYSQL_ASSOC)) {
		$ausgabe .= $ausgabe1;
		$ausgabe1 = "";
		if ($inhalte_polit["alli1"] == $aid) {
			$result_a = mysql_query("SELECT id,tag FROM genesis_allianzen where id='" . $inhalte_polit["alli2"] . "'");
			$inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC);
		} else {
			$result_a = mysql_query("SELECT id,tag FROM genesis_allianzen where id='" . $inhalte_polit["alli1"] . "'");
			$inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC);
		}
		if ($inhalte_polit["bis"] > 0) {
			$pbis = date("d.m.Y (H:i:s)", $inhalte_polit["bis"]);
		} else {
			$pbis = "-";
		}
		if ($inhalte_polit["typ"] == 1) {
			$ptyp = "<p class=ja>Freund</p>";
		} elseif ($inhalte_polit["typ"] == 3) {
			$ptyp = "<p style=\"color:orange;\">Feind</p>";
		} elseif ($inhalte_polit["typ"] == 5) {
			$ptyp = "<p class=nein>Krieg</p>";
		} elseif ($inhalte_polit["typ"] == 7 && $inhalte_polit["accept"] == 1) {
			$ptyp = "<p class=ja>Bündnis</p>";
		} elseif ($inhalte_polit["typ"] == 7 && $inhalte_polit["accept"] == 0) {
			$ptyp = "<p class=nan>Bündnis (nicht bestätigt)</p>";
		}
		$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&nav=info&t=alli" . $inhalte_a["id"], "[" . $inhalte_a["tag"] . "]")) . td(0, "center", $ptyp) . td(0, "center", date("d.m.Y (H:i:s)", $inhalte_polit["von"])) . td(0, "center", $pbis) . td(0, "center", hlink("", "game.php?id=$id&nav=$nav&aktion=end&pende=" . $inhalte_polit["id"], "Beenden")));
	}

	if ($ausgabe1 == "") {
		$ausgabe .= tr(td(5, "center", "<br>"));
	}

	$ausgabe1 = tr(td(5, "head", "Bündnisanfragen")) . tr(td(0, "head", "Symbiose") . td(0, "head", "&nbsp;") . td(0, "head", "Von") . td(0, "head", "Bis") . td(0, "head", "&nbsp;"));
	$result_polit = mysql_query("SELECT * FROM genesis_politik WHERE alli2='$aid' and typ='7' and accept='0' ORDER BY von DESC");
	while ($inhalte_polit = mysql_fetch_array($result_polit, MYSQL_ASSOC)) {
		$ausgabe .= $ausgabe1;
		$ausgabe1 = "";
		$result_a = mysql_query("SELECT id,tag FROM genesis_allianzen where id='" . $inhalte_polit["alli1"] . "'");
		$inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC);
		if ($inhalte_polit["bis"] > 0) {
			$pbis = date("d.m.Y (H:i:s)", $inhalte_polit["bis"]);
		} else {
			$pbis = "-";
		}
		$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&nav=info&t=alli" . $inhalte_a["id"], "[" . $inhalte_a["tag"] . "]")) . td(0, "center", "&nbsp;") . td(0, "center", date("d.m.Y (H:i:s)", $inhalte_polit["von"])) . td(0, "center", $pbis) . td(2, "center", hlink("", "game.php?id=$id&nav=$nav&aktion=deny&pid=" . $inhalte_polit["id"], "Ablehnen") . "<br>" . hlink("", "game.php?id=$id&nav=$nav&aktion=accept&pid=" . $inhalte_polit["id"], "Akzeptieren")));
	}

	if ($ausgabe1 == "") {
		$ausgabe .= tr(td(5, "center", "<br>"));
	}

	$ausgabe1 = tr(td(5, "head", "Symbiosen History"));
	$result_polit = mysql_query("SELECT * FROM genesis_history WHERE alli1='$symb' OR (alli2='$symb' and (typ='5' or typ='6' or typ='7' or typ='8')) ORDER BY zeit DESC");
	while ($inhalte_polit = mysql_fetch_array($result_polit, MYSQL_ASSOC)) {
		$ausgabe .= $ausgabe1;
		$ausgabe1 = "";
		$ausgabe2 = "";
		$ausgabe3 = "";
		if ($symb == $inhalte_polit["alli2"]) {
			$inhalte_polit["alli2"] = $inhalte_polit["alli1"];
		}
		if ($inhalte_polit["typ"] == 1) {
			$ausgabe2 = "Beginn der Freundschaft";
		}
		if ($inhalte_polit["typ"] == 2) {
			$ausgabe2 = "Ende der Freundschaft";
		}
		if ($inhalte_polit["typ"] == 3) {
			$ausgabe2 = "Beginn der Feindschaft";
		}
		if ($inhalte_polit["typ"] == 4) {
			$ausgabe2 = "Ende der Feindschaft";
		}
		if ($inhalte_polit["typ"] == 7) {
			$ausgabe2 = "Beginn des Bündnisses";
		}
		if ($inhalte_polit["typ"] == 8) {
			$ausgabe2 = "Ende des Bündnisses";
		}
		if ($inhalte_polit["typ"] != 5 && $inhalte_polit["typ"] != 6) {
			$ausgabe3 = "$ausgabe2 mit [" . $inhalte_polit["alli2"] . "]";
		}
		if ($inhalte_polit["typ"] == 5) {
			if ($inhalte_polit["alli2"] == $inhalte_polit["alli1"]) {
				$ausgabe2 = "von";
			} else {
				$ausgabe2 = "an";
			}
			$ausgabe3 = "Kriegserklärung $ausgabe2 [" . $inhalte_polit["alli2"] . "]";
		}
		if ($inhalte_polit["typ"] == 6) {
			if (($inhalte_polit["zusatz"] == 1 && $inhalte_polit["alli2"] == $inhalte_polit["alli1"]) || ($inhalte_polit["zusatz"] == 2 && $inhalte_polit["alli2"] != $inhalte_polit["alli1"])) {
				$ausgabe2 = "Niederlage";
			}
			if (($inhalte_polit["zusatz"] == 1 && $inhalte_polit["alli2"] != $inhalte_polit["alli1"]) || ($inhalte_polit["zusatz"] == 2 && $inhalte_polit["alli2"] == $inhalte_polit["alli1"])) {
				$ausgabe2 = "Sieg";
			}
			if ($inhalte_polit["zusatz"] == 3) {
				$ausgabe2 = "Unentschieden";
			}
			$ausgabe3 = "Kriegsende gegen [" . $inhalte_polit["alli2"] . "] - Ausgang: $ausgabe2";
		}
		$ausgabe .= tr(td(2, "center", date("d.m.Y (H:i:s)", $inhalte_polit["zeit"])) . td(3, "center", $ausgabe3));
	}
	if ($ausgabe1 == "") {
		$ausgabe .= tr(td(5, "center", "<br>"));
	}
	$ausgabe .= tr(td(5, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin", "zurück zur Administration")));
}

$ausgabe .= "</table>\n</form>\n";

?>