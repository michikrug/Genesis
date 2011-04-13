<?php

	if ($start == "" || $start == 0) {
		$start = date("d.m.Y", time());
	}
	if ($ende == "" || $ende == 0) {
		$ende = date("d.m.Y", time());
	}

	$ausgabe .= input("hidden", "a", "symbstats");
	$ausgabe .= tr(td(5, "head", "Symbiosenstatistik"));
	$ausgabe .= tr(td(2, "left", "Gegner (Tag)") . td(3, "center", input("text", "tag2", $tag2)));
	$ausgabe .= tr(td(2, "left", "Start (TT.MM.JJJJ)") . td(3, "center", input("text", "start", $start)));
	$ausgabe .= tr(td(2, "left", "Ende (TT.MM.JJJJ)") . td(3, "center", input("text", "ende", $ende)));
	$ausgabe .= tr(td(5, "center", input("submit", "aktion", "Erstellen")));

	$s = explode(".", $start);
	$start = mktime(0, 0, 0, $s[1], $s[0], $s[2]);
	$e = explode(".", $ende);
	$ende = mktime(0, 0, 0, $e[1], $e[0] + 1, $e[2]);

	if ($symb != "" && $aktion == "Erstellen") {
		$ausgabe .= tr(td(0, "head", "&nbsp;") . td(2, "head", "[$symb]") . td(2, "head", "[$tag2]"));
		$ausgabe .= tr(td(0, "left", "<b>Einheit</b>") . td(0, "navi", "Beteiligt") . td(0, "navi", "Verloren") . td(0, "navi", "Beteiligt") . td(0, "navi", "Verloren"));

		for ($i = 1; $i <= 8; $i++) {
			eval("unset(\$vert" . $i . "2v,\$vert" . $i . "2g,\$vert" . $i . "1v,\$vert" . $i . "1g,\$prod" . $i . "1g,\$prod" . $i . "1v,\$prod" . $i . "2g,\$prod" . $i . "2v);");
		}
		unset($ress11, $ress21, $ress31, $ress41, $ress51, $ress12, $ress22, $ress32, $ress42, $ress52, $kp1, $kp2, $kp1a, $kp2a);


		$qry = "SELECT t1.id,t1.name,t3.name,t1.kp,t3.kp,
		t1.prod1,t1.prod2,t1.prod3,t1.prod4,t1.prod5,t1.prod6,t1.prod7,t1.prod8,
		t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t1.prodv8,
		t3.prod1,t3.prod2,t3.prod3,t3.prod4,t3.prod5,t3.prod6,t3.prod7,t3.prod8,t3.vert1,t3.vert2,t3.vert3,
		t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.prodv8,t3.vertv1,t3.vertv2,t3.vertv3,
		t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5
		FROM genesis_att t1
		LEFT JOIN genesis_berichte t2 USING(id)
		LEFT JOIN genesis_deff t3 USING(id)
		WHERE t1.alli='[$symb]'";
		if ($tag2 != "") {
			$qry .= " AND t3.alli='[$tag2]'";
		}
		$qry .= " AND t2.typ='1' AND t2.zeit>'$start' AND t2.zeit<'$ende' ORDER BY t1.id, t1.name";

		$result = mysql_query($qry);
		$anz1 = 0;

		unset($namea, $nameb, $bid);
		while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
			$anz1++;
			if ($bid != $berichte[0]) {
				for ($i = 1; $i <= 8; $i++) {
					$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
					$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
					eval("\$prod" . $i . "1g += \$berichte[" . ($i + 4) . "];");
					eval("\$prod" . $i . "1v += \$berichte[" . ($i + 12) . "];");
					eval("\$prod" . $i . "2g += \$berichte[" . ($i + 20) . "];");
					eval("\$prod" . $i . "2v += \$berichte[" . ($i + 31) . "];");
					$kp2a += $berichte[($i + 12)] * $inhalte[0];
				}
				for ($i = 1; $i <= 3; $i++) {
					eval("\$vert" . $i . "2g += \$berichte[" . ($i + 28) . "];");
					eval("\$vert" . $i . "2v += \$berichte[" . ($i + 39) . "];");
				}
				$ress11 += $berichte[43];
				$ress21 += $berichte[44];
				$ress31 += $berichte[45];
				$ress41 += $berichte[46];
				$ress51 += $berichte[47];
				$kp1a += $berichte[3];
			} elseif ($bid == $berichte[0] && $namea != $berichte[1]) {
				for ($i = 1; $i <= 8; $i++) {
					$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
					$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
					eval("\$prod" . $i . "1g += \$berichte[" . ($i + 4) . "];");
					eval("\$prod" . $i . "1v += \$berichte[" . ($i + 12) . "];");
					$kp2a += $berichte[($i + 12)] * $inhalte[0];
				}
				$ress11 += $berichte[43];
				$ress21 += $berichte[44];
				$ress31 += $berichte[45];
				$ress41 += $berichte[46];
				$ress51 += $berichte[47];
				$kp1a += $berichte[3];
			} elseif ($bid == $berichte[0] && $nameb != $berichte[2]) {
				for ($i = 1; $i <= 8; $i++) {
					eval("\$prod" . $i . "2g += \$berichte[" . ($i + 20) . "];");
					eval("\$prod" . $i . "2v += \$berichte[" . ($i + 31) . "];");
				}
			}
			$bid = $berichte[0];
			$namea = $berichte[1];
			$nameb = $berichte[2];
		}


		$qry = "SELECT t1.id,t1.name,t3.name,t1.kp,t3.kp,
		t1.prod1,t1.prod2,t1.prod3,t1.prod4,t1.prod5,t1.prod6,t1.prod7,t1.prod8,
		t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t1.prodv8,
		t3.prod1,t3.prod2,t3.prod3,t3.prod4,t3.prod5,t3.prod6,t3.prod7,t3.prod8,t3.vert1,t3.vert2,t3.vert3,
		t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.prodv8,t3.vertv1,t3.vertv2,t3.vertv3,
		t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5
		FROM genesis_att t1
		LEFT JOIN genesis_berichte t2 USING(id)
		LEFT JOIN genesis_deff t3 USING(id)
		WHERE";
		if ($tag2 != "") {
			$qry .= " t1.alli='[$tag2]' AND";
		}
		$qry .= " t3.alli='[$symb]' AND t2.typ='1' AND t2.zeit>'$start' AND t2.zeit<'$ende' ORDER BY t1.id, t1.name";

		$result = mysql_query($qry);
		unset($namea, $nameb, $bid);
		$anz2 = 0;
		while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
			$anz2++;
			if ($bid != $berichte[0]) {
				for ($i = 1; $i <= 8; $i++) {
					$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
					$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
					eval("\$prod" . $i . "2g += \$berichte[" . ($i + 4) . "];");
					eval("\$prod" . $i . "2v += \$berichte[" . ($i + 12) . "];");
					eval("\$prod" . $i . "1g += \$berichte[" . ($i + 20) . "];");
					eval("\$prod" . $i . "1v += \$berichte[" . ($i + 31) . "];");
					$kp1a += $berichte[($i + 12)] * $inhalte[0];
				}
				for ($i = 1; $i <= 3; $i++) {
					eval("\$vert" . $i . "1g += \$berichte[" . ($i + 28) . "];");
					eval("\$vert" . $i . "1v += \$berichte[" . ($i + 39) . "];");
				}
				$ress12 += $berichte[43];
				$ress22 += $berichte[44];
				$ress32 += $berichte[45];
				$ress42 += $berichte[46];
				$ress52 += $berichte[47];
				$kp2a += $berichte[3];
			} elseif ($bid == $berichte[0] && $namea != $berichte[1]) {
				for ($i = 1; $i <= 8; $i++) {
					$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
					$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
					eval("\$prod" . $i . "2g += \$berichte[" . ($i + 4) . "];");
					eval("\$prod" . $i . "2v += \$berichte[" . ($i + 12) . "];");
					$kp1a += $berichte[($i + 12)] * $inhalte[0];
				}
				$ress12 += $berichte[43];
				$ress22 += $berichte[44];
				$ress32 += $berichte[45];
				$ress42 += $berichte[46];
				$ress52 += $berichte[47];
				$kp2a += $berichte[3];
			} elseif ($bid == $berichte[0] && $nameb != $berichte[2]) {
				for ($i = 1; $i <= 8; $i++) {
					eval("\$prod" . $i . "1g += \$berichte[" . ($i + 20) . "];");
					eval("\$prod" . $i . "1v += \$berichte[" . ($i + 31) . "];");
				}
			}
			$bid = $berichte[0];
			$namea = $berichte[1];
			$nameb = $berichte[2];
		}

		for ($i = 1; $i <= 8; $i++) {
			$result = mysql_query("SELECT bezeichnung,wert2 FROM genesis_infos WHERE typ='prod$i'");
			$inhalte = mysql_fetch_array($result, MYSQL_NUM);
			eval("\$out1 = \$prod" . $i . "1g;");
			eval("\$out2 = \$prod" . $i . "1v;");
			eval("\$out3 = \$prod" . $i . "2g;");
			eval("\$out4 = \$prod" . $i . "2v;");
			$ausgabe .= tr(
				td(0, "left", $inhalte[0])
				 . td(0, "center", format($out1))
				 . td(0, "center", format($out2))
				 . td(0, "center", format($out3))
				 . td(0, "center", format($out4))
				);
			eval("\$kp2 += \$inhalte[1] * \$prod" . $i . "1v;");
			eval("\$kp1 += \$inhalte[1] * \$prod" . $i . "2v;");
		}

		for ($i = 1; $i <= 3; $i++) {
			$result = mysql_query("SELECT bezeichnung,wert2 FROM genesis_infos WHERE typ='vert$i'");
			$inhalte = mysql_fetch_array($result, MYSQL_NUM);
			eval("\$out1 = \$vert" . $i . "1g;");
			eval("\$out2 = \$vert" . $i . "1v;");
			eval("\$out3 = \$vert" . $i . "2g;");
			eval("\$out4 = \$vert" . $i . "2v;");
			$ausgabe .= tr(
				td(0, "left", $inhalte[0])
				 . td(0, "center", format($out1))
				 . td(0, "center", format($out2))
				 . td(0, "center", format($out3))
				 . td(0, "center", format($out4))
				);
			eval("\$kp2 += \$inhalte[1] * \$vert" . $i . "1v;");
			eval("\$kp1 += \$inhalte[1] * \$vert" . $i . "2v;");
		}

		$ausgabe .= tr(td(5, "hr", "<hr>"));
		$ausgabe .= tr(td(0, "left", "<b>Angriffe</b>") . td(2, "center", format($anz1)) . td(2, "center", format($anz2)));
		$ausgabe .= tr(td(5, "hr", "<hr>"));
		$ausgabe .= tr(td(0, "left", "<b>Nährstoff</b>") . td(4, "navi", "Erbeutet"));
		$ausgabe .= tr(td(0, "left", "Adenin") . td(2, "center", format($ress11)) . td(2, "center", format($ress12)));
		$ausgabe .= tr(td(0, "left", "Thymin") . td(2, "center", format($ress21)) . td(2, "center", format($ress22)));
		$ausgabe .= tr(td(0, "left", "Guanin") . td(2, "center", format($ress31)) . td(2, "center", format($ress32)));
		$ausgabe .= tr(td(0, "left", "Cytosin") . td(2, "center", format($ress41)) . td(2, "center", format($ress42)));
		$ausgabe .= tr(td(0, "left", "ATP") . td(2, "center", format($ress51)) . td(2, "center", format($ress52)));
		$ausgabe .= tr(td(5, "hr", "<hr>"));
		$ausgabe .= tr(td(0, "left", "<b>Kampfpunkte</b>") . td(2, "center", format($kp1)) . td(2, "center", format($kp2)));
		$ausgabe .= tr(td(0, "left", "<b>Kampfpunkte (direkt)</b>") . td(2, "center", format($kp1a)) . td(2, "center", format($kp2a)));

		for ($i = 1; $i <= 8; $i++) {
			eval("unset(\$vert" . $i . "2v,\$vert" . $i . "2g,\$vert" . $i . "1v,\$vert" . $i . "1g,\$prod" . $i . "1g,\$prod" . $i . "1v,\$prod" . $i . "2g,\$prod" . $i . "2v);");
		}
		unset($ress11, $ress21, $ress31, $ress41, $ress12, $ress22, $ress32, $ress42, $kp1, $kp2);

		mysql_free_result($result);
	}

?>