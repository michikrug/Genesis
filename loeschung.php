<?php

include_once "ctracker.php";

include_once "sicher/config.inc.php";
$connection = @mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$connection) die("Es konnte keine Datenbankverbindung hergestellt werden.");
mysql_select_db($mysql_db, $connection);

$ausgabe = "<html>
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\">
<title>Genesis</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\">
</head>
<body>
<center>
";

include_once "functions.inc.php";

$ausgabe .= table(600, "bg");

$result = mysql_query("SELECT * FROM genesis_spieler WHERE id='$sid'");
if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if ($aktion == "cancel" && $inhalte["loesch"] == $lid) {
		$zeit = time();
		mysql_query("UPDATE genesis_spieler SET loesch='0' WHERE id='$sid'");
		$ausgabe .= tr(td(0, "center", "Die Anforderung der Löschung des Accounts " . $inhalte["login"] . " wurde zurückgezogen.<br>Du kannst ohne Einschränkungen weiterspielen."));
	} elseif ($aktion == "delete" && $inhalte["loesch"] == 4294967295) {
		$resultm = mysql_query("SELECT id FROM genesis_aktionen WHERE basis2='" . $inhalte["basis1"] . "' and aktion='1'");
		if (!mysql_fetch_array($resultm, MYSQL_ASSOC)) {
			if ($inhalte["alli"] > 0) {
				$resulta = mysql_query("SELECT * FROM genesis_allianzen WHERE id='" . $inhalte["alli"] . "'");
				if ($inhaltea = mysql_fetch_array($resulta, MYSQL_ASSOC)) {
					if ($inhaltea["anz"] == 1) {
						$result_p = mysql_query("SELECT * FROM genesis_politik WHERE alli1='" . $inhalte["alli"] . "' OR (alli2='" . $inhalte["alli"] . "' and (typ='1' or typ='3' or typ='5' or (typ='7' and accept='1')))");
						if ($inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC)) {
							$zusatz = 0;
							$result_a1 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli1"] . "'");
							$inhalte_a1 = mysql_fetch_array($result_a1, MYSQL_ASSOC);
							$result_a2 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli2"] . "'");
							$inhalte_a2 = mysql_fetch_array($result_a2, MYSQL_ASSOC);
							if ($inhalte_p["typ"] == 5) {
								for ($i = 1; $i <= 7; $i++) {
									eval("unset(\$prod" . $i . "1g,\$prod" . $i . "1v,\$prod" . $i . "2g,\$prod" . $i . "2v);");
								}
								unset($ress11, $ress21, $ress31, $ress41, $ress51, $ress12, $ress22, $ress32, $ress42, $ress52, $kp1, $kp2, $kp1a, $kp2a);
								$qry = "SELECT t1.id,t1.name,t1.prod1,t1.prod2,t1.prod3,t1.prod4,t1.prod5,t1.prod6,t1.prod7,t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t3.prod1,t3.prod2,t3.prod3,t3.prod4,t3.prod5,t3.prod6,t3.prod7,t3.vert1,t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.vertv1,t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5,t3.name,t1.kp,t3.kp FROM genesis_att t1 LEFT JOIN genesis_berichte t2 USING(id) LEFT JOIN genesis_deff t3 ON t3.id=t1.id WHERE t1.alli='[" . $inhalte_a1["tag"] . "]' and t3.alli='[" . $inhalte_a2["tag"] . "]' and t2.typ='1' and t2.zeit>'" . $inhalte_p["von"] . "' and t2.zeit<'" . time() . "' order by t1.id, t1.name";
								$result = mysql_query($qry);
								unset($namea, $nameb, $id, $anz1);
								while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
									$anz1++;
									if ($id != $berichte[0]) {
										for ($i = 1; $i <= 7; $i++) {
											$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
											$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
											$kp2a += $berichte[($i + 8)] * $inhalte[0];
										}
										$kp1a += $berichte[38];
									} elseif ($id == $berichte[0] && $namea != $berichte[1]) {
										for ($i = 1; $i <= 7; $i++) {
											$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
											$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
											$kp2a += $berichte[($i + 8)] * $inhalte[0];
										}
										$kp1a += $berichte[38];
									}
									$id = $berichte[0];
									$namea = $berichte[1];
									$nameb = $berichte[37];
								}
								$qry = "SELECT t1.id,t1.name,t1.prod1,t1.prod2,t1.prod3,t1.prod4,t1.prod5,t1.prod6,t1.prod7,t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t3.prod1,t3.prod2,t3.prod3,t3.prod4,t3.prod5,t3.prod6,t3.prod7,t3.vert1,t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.vertv1,t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5,t3.name,t1.kp,t3.kp FROM genesis_att t1 LEFT JOIN genesis_berichte t2 USING(id) LEFT JOIN genesis_deff t3 ON t3.id=t1.id WHERE t2.typ='1' and t2.zeit>'" . $inhalte_p["von"] . "' and t2.zeit<'" . time() . "' and t3.alli='[" . $inhalte_a1["tag"] . "]' and t1.alli='[" . $inhalte_a2["tag"] . "]' order by t1.id, t1.name";
								$result = mysql_query($qry);
								unset($namea, $nameb, $id, $anz2);
								while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
									$anz2++;
									if ($id != $berichte[0]) {
										for ($i = 1; $i <= 7; $i++) {
											$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
											$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
											$kp1a += $berichte[($i + 8)] * $inhalte[0];
										}
										$kp2a += $berichte[38];
									} elseif ($id == $berichte[0] && $namea != $berichte[1]) {
										for ($i = 1; $i <= 7; $i++) {
											$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
											$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
											$kp1a += $berichte[($i + 8)] * $inhalte[0];
										}
										$kp2a += $berichte[38];
									}
									$id = $berichte[0];
									$namea = $berichte[1];
									$nameb = $berichte[37];
								}
								if (($kp1a - $kp2a > ($kp1a / 100 * 5)) || ($kp1a > $kp2a && $anz2 == 0)) {
									$zusatz = 1;
								} elseif (($kp2a - $kp1a > ($kp2a / 100 * 5)) || ($kp2a > $k1pa && $anz1 == 0)) {
									$zusatz = 2;
								} else {
									$zusatz = 3;
								}
								for ($i = 1; $i <= 7; $i++) {
									eval("unset(\$prod" . $i . "1g,\$prod" . $i . "1v,\$prod" . $i . "2g,\$prod" . $i . "2v);");
								}
								unset($ress11, $ress21, $ress31, $ress41, $ress51, $ress12, $ress22, $ress32, $ress42, $ress52, $kp1, $kp2, $kp1a, $kp2a);
							}
							mysql_query("DELETE FROM genesis_politik WHERE id='" . $inhalte_p["id"] . "'");
							mysql_query("INSERT INTO genesis_history (alli1,alli2,typ,zeit,zusatz) VALUES ('" . $inhalte_a1["tag"] . "','" . $inhalte_a2["tag"] . "','" . ($inhalte_p["typ"] + 1) . "','" . time() . "','$zusatz')");
						}
						mysql_query("DELETE FROM genesis_allianzen WHERE id='" . $inhalte["alli"] . "'");
						mysql_query("DELETE FROM genesis_raenge WHERE alli='" . $inhalte["alli"] . "'");
						mysql_query("DELETE FROM genesis_news WHERE an='" . $inhalte["alli"] . "' AND typ='alli_news'");
						mysql_query("DELETE FROM genesis_politik WHERE alli2='" . $inhalte["alli"] . "' AND typ='7' AND accept='0'");
						$ausgabe .= tr(td(0, "center", "Symbiose [" . $inhaltea["tag"] . "] aufgelöst."));
					} else {
						mysql_query("UPDATE genesis_allianzen SET anz=(anz-1) WHERE alli='" . $inhalte["alli"] . "'");
						$resulta = mysql_query("SELECT * FROM genesis_spieler WHERE alli='" . $inhalte["alli"] . "' and alli_rang='0' and id<>'" . $inhalte["id"] . "'");
						if (!mysql_fetch_array($resulta, MYSQL_ASSOC)) {
							mysql_query("UPDATE genesis_spieler SET alli_rang='0' WHERE alli='" . $inhalte["alli"] . "' LIMIT 1");
							$ausgabe .= tr(td(0, "center", "Neuer Gründer für Symbiose [" . $inhaltea["tag"] . "] bestimmt."));
						}
					}
				}
			}
			mysql_query("DELETE FROM genesis_spieler WHERE id='" . $inhalte["id"] . "'");
			mysql_query("DELETE FROM genesis_aktionen WHERE basis1='" . $inhalte["basis1"] . "' OR basis1='" . $inhalte["basis2"] . "'");
			mysql_query("DELETE FROM genesis_news WHERE (an='" . $inhalte["id"] . "' AND (typ='news' OR typ='ereignis')) OR (von='" . $inhalte["id"] . "' AND (typ='bewerb' OR typ='alli_news'))");
			mysql_query("UPDATE genesis_basen SET name='', bname='', ress1='0', ress2='0', ress3='0', ress4='0', ress5='0', konst1='0', konst2='0', konst3='0', konst4='0', konst5='0', konst6='0', konst7='0', konst8='0', konst9='0', konst10='0', konst11='0', konst12='0', konst13='0', konst14='0', konst15='0', konst16='0', konst17='0', prod1='0', prod2='0', prod3='0', prod4='0', prod5='0', prod6='0', prod7='0', prod8='0', vert1='0', vert2='0', vert3='0', punkte='0', resszeit='0', verbrauch='0', typ='0', bonus='0', bild='1' WHERE name='" . $inhalte["name"] . "'");
			$ausgabe .= tr(td(0, "center", "Löschung des Accounts " . $inhalte["login"] . " (" . $inhalte["name"] . ") erfolgreich."));
		} else {
			$ausgabe .= tr(td(0, "center", "Fehler! Mission zu Ziel unterwegs."));
		}
	} else {
		$ausgabe .= tr(td(0, "center", "Fehler!"));
	}
}

unset ($result_p, $inhalte_p, $resulta, $inhaltea, $result, $inhalte);

mysql_close($connection);

$ausgabe .= "</table>\n";

$ausgabe .= "
</center>
</body>
</html>";

echo $ausgabe;

?>