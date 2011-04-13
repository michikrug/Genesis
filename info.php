<?php

$t = isset($_REQUEST["t"]) ? $_REQUEST["t"] : NULL;
$a = isset($_REQUEST["a"]) ? $_REQUEST["a"] : NULL;
$k = isset($_REQUEST["k"]) ? $_REQUEST["k"] : NULL;
$newstxt = isset($_REQUEST["newstxt"]) ? $_REQUEST["newstxt"] : NULL;
$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;

include_once "parser.inc.php";

if (strpos($t, "konst") !== false || strpos($t, "forsch") !== false || strpos($t, "prod") !== false || strpos($t, "vert") !== false) {
	$result = mysql_query("SELECT bezeichnung,beschreibung,ress1,ress2,ress3,ress4,wert1,wert2,wert3,wert4,wert5,wert6 FROM genesis_infos WHERE typ='$t'");
	$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);

	$ausgabe .= table(400, "bg");
	$ausgabe .= tr(td(2, "head", $inhalte["bezeichnung"]));
	$ausgabe .= tr(td(2, "center", "<img src=\"bilder/$t.jpg\" height=320 width=320>"));
	$ausgabe .= tr(td(2, "center", nl2br($inhalte["beschreibung"]) . "<br><br>"));

	if (strpos($t, "konst") !== false) {
		$pkt = getpunkte("konst", $inhalte_b["$t"], substr($t, 5));
	} elseif (strpos($t, "forsch") !== false) {
		$pkt = getpunkte("forsch", $inhalte_s["$t"], substr($t, 6));
	} else {
		$pkt = $inhalte["wert2"];
	}
	$ausgabe .= tr(td(0, "center", "Punkte") . td(0, "center", format($pkt)));

	if ($t == "konst2" || $t == "konst3" || $t == "konst4" || $t == "konst5" || $t == "konst6") {
		$ausgabe .= tr(td(2, "head", "Rohstoffproduktion"));
		if ($inhalte_b["$t"] == 0) $inhalte_b["$t"] = 1;
		for ($i = $inhalte_b["$t"]; $i <= $inhalte_b["$t"] + 5; $i++) {
			$ress = ressprod($inhalte["wert1"], $i);
			if ($t == "konst6") $ress -= $inhalte["wert1"];
			$ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", format(round($ress / 6, 0) * 6)));
		}
	}
	if ($t == "konst9" || $t == "konst10" || $t == "konst11" || $t == "konst12" || $t == "konst13") {
		$ausgabe .= tr(td(2, "head", "Lagerkapazität"));
		for ($i = $inhalte_b["$t"]; $i <= $inhalte_b["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", format(resskap($i))));
	}
	if ($t == "konst14") {
		$ausgabe .= tr(td(2, "head", "Missionsanzahl"));
		if ($inhalte_b["$t"] == 0) $inhalte_b["$t"] = 1;
		for ($i = $inhalte_b["$t"]; $i <= $inhalte_b["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", missanz($i)));
	}
	if ($t == "konst15") {
		$ausgabe .= tr(td(2, "head", "Angriffs-/Vert.-wert"));
		for ($i = $inhalte_b["$t"]; $i <= $inhalte_b["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", format(angr_immu($inhalte_s["forsch1"], $i)) . "/" . format(vert_immu($inhalte_s["forsch4"], $i))));
		$ausgabe .= tr(td(2, "head", "max. Anzahl Antikörper"));
		for ($i = $inhalte_b["$t"]; $i <= $inhalte_b["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", format(maxvert($i))));
	}
	if ($t == "konst16") {
		$ausgabe .= tr(td(2, "head", "Sensor-Reichweite"));
		for ($i = $inhalte_b["$t"]; $i <= $inhalte_b["$t"] + 5; $i++) {
			$dauer = round(pow($inhalte_s["forsch5"], 1.2) * 300 / 60, 0) * 60 + round(pow($i, 1.2) * 180 / 60, 0) * 60 + 300;
			include("dauer.inc.php");
			$ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", "$h:$m:$s"));
		}
	}
	if ($t == "forsch1") {
		$ausgabe .= tr(td(2, "head", "Verbesserung des Angriffswertes"));
		$angr0 = angr("prod1", 1000, 0, $inhalte_s["forsch3"]);
		for ($i = $inhalte_s["$t"]; $i <= $inhalte_s["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", round(angr("prod1", 1000, $i, $inhalte_s["forsch3"]) / $angr0 * 100 - 100, 1) . "%"));
	}
	if ($t == "forsch2") {
		$ausgabe .= tr(td(2, "head", "Verbesserung der Geschwindigkeit"));
		$geschw0 = geschw(500, 0);
		for ($i = $inhalte_s["$t"]; $i <= $inhalte_s["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", round(geschw(500, $i) / $geschw0 * 100 - 100, 1) . "%"));
	}
	if ($t == "forsch3") {
		$ausgabe .= tr(td(2, "head", "Verbesserung der Determintatorwerte"));
		$angr0 = angr("prod5", 1000, $inhalte_s["forsch1"], 0) + vert("prod5", 1000, $inhalte_s["forsch4"], 0);
		for ($i = $inhalte_s["$t"]; $i <= $inhalte_s["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", round((angr("prod5", 1000, $inhalte_s["forsch1"], $i) + vert("prod5", 1000, $inhalte_s["forsch4"], $i)) / $angr0 * 100 - 100, 1) . "%"));
	}
	if ($t == "forsch4") {
		$ausgabe .= tr(td(2, "head", "Verbesserung des Verteidigungswertes"));
		$vert0 = vert("prod1", 1000, 0, $inhalte_s["forsch3"]);
		for ($i = $inhalte_s["$t"]; $i <= $inhalte_s["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", round(vert("prod1", 1000, $i, $inhalte_s["forsch3"]) / $vert0 * 100 - 100, 1) . "%"));
	}
	if ($t == "forsch5") {
		$ausgabe .= tr(td(2, "head", "Sensor-Reichweite"));
		for ($i = $inhalte_s["$t"]; $i <= $inhalte_s["$t"] + 5; $i++) {
			$dauer = round(pow($i, 1.2) * 300 / 60, 0) * 60 + round(pow($inhalte_b["konst16"], 1.2) * 180 / 60, 0) * 60 + 300;
			include("dauer.inc.php");
			$ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", "$h:$m:$s"));
		}
	}
	if ($t == "forsch7") {
		$ausgabe .= tr(td(2, "head", "ermöglicht max. Ausbaustufe"));
		for ($i = $inhalte_s["$t"]; $i <= $inhalte_s["$t"] + 5; $i++) $ausgabe .= tr(td(0, "center", "Stufe $i") . td(0, "center", maxstufe($i)));
	}

	if (strpos($t, "prod") !== false || strpos($t, "vert") !== false) {
		$ausgabe .= tr(td(0, "center", "Angriffswert") . td(0, "center", format(angr($t, $inhalte["wert3"], $inhalte_s["forsch1"], $inhalte_s["forsch3"])) . " (" . format(angr($t, $inhalte["wert3"], 0, 0)) . ")"));
		$ausgabe .= tr(td(0, "center", "Verteidigungswert") . td(0, "center", format(vert($t, $inhalte["wert4"], $inhalte_s["forsch4"], $inhalte_s["forsch3"])) . " (" . format(vert($t, $inhalte["wert4"], 0, 0)) . ")"));
		if (strpos($t, "prod") !== false) {
			$ausgabe .= tr(td(0, "center", "Ladekapazität") . td(0, "center", format(ladekap($inhalte["wert5"], $inhalte_s["forsch6"])) . " (" . format(ladekap($inhalte["wert5"], 0)) . ")"));
			$ausgabe .= tr(td(0, "center", "Geschwindigkeit") . td(0, "center", format(geschw($inhalte["wert6"], $inhalte_s["forsch2"])) . " (" . format(geschw($inhalte["wert6"], 0)) . ")"));
			$ausgabe .= tr(td(2, "navi", "Energieverbrauch"));
			$ausgabe .= tr(td(0, "center", "pro Stunde stationiert") . td(0, "center", format(round(verbrauch($inhalte["wert1"]) / 8), 0)));
			$ausgabe .= tr(td(0, "center", "pro Entfernungseinheit") . td(0, "center", format(round(363.64 * verbrauch($inhalte["wert1"]) / 650, 0) + 1)));
		}
		$ausgabe .= tr(td(2, "head", "Kosten"));
		$ausgabe .= tr(td(0, "center", "Adenin") . td(0, "center", format($inhalte["ress1"])));
		$ausgabe .= tr(td(0, "center", "Thymin") . td(0, "center", format($inhalte["ress2"])));
		$ausgabe .= tr(td(0, "center", "Guanin") . td(0, "center", format($inhalte["ress3"])));
		$ausgabe .= tr(td(0, "center", "Cytosin") . td(0, "center", format($inhalte["ress4"])));
	}
	$ausgabe .= tr(td(2, "center", hlink("new", "http://genesis.vbfreak.de/wiki/index.php/" . str_replace(" ", "%20", $inhalte["bezeichnung"]), "Informationen im Wiki")));
	$ausgabe .= "</table>\n";
} elseif (strpos($t, "alli") !== false) {
	$t = substr($t, 4);
	$ausgabe .= table(600, "bg");
	$ausgabe .= tr(td(2, "head", "SymbiosenInfo"));
	$result = mysql_query("SELECT tag,name,bild,punkte,kampfpkt,punkted,anz,beschreibung,url,forum FROM genesis_allianzen WHERE id='$t'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($inhalte["bild"] != "") {
			if (strpos($inhalte["bild"], "http://") === false) {
				list($width, $height, $type, $attr) = getimagesize($inhalte["bild"]);
				if ($width > 590) $out = " width=\"590\"";
			}
			$ausgabe .= tr(td(2, "center", "<img src=\"" . $inhalte["bild"] . "\"$out>"));
		}
		$ausgabe .= tr(td(0, "left", "Tag") . td(0, "right", "[" . $inhalte["tag"] . "]"));
		$ausgabe .= tr(td(0, "left", "Name") . td(0, "right", $inhalte["name"]));
		$ausgabe .= tr(td(0, "left", "Mitglieder") . td(0, "right", $inhalte["anz"]));
		$ausgabe .= tr(td(0, "left", "Punkte") . td(0, "right", format($inhalte["punkte"])));
		$ausgabe .= tr(td(0, "left", "Punkte Durchschnitt") . td(0, "right", format($inhalte["punkted"])));
		$ausgabe .= tr(td(0, "left", "Kampfpunkte") . td(0, "right", format($inhalte["kampfpkt"])));
		if ($inhalte["url"] != "") $ausgabe .= tr(td(0, "left", "Homepage") . td(0, "right", hlink("new", $inhalte["url"], $inhalte["url"])));
		if ($inhalte["forum"] != "") $ausgabe .= tr(td(0, "left", "Forum") . td(0, "right", hlink("new", $inhalte["forum"], $inhalte["forum"])));
		$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=info&a=news&t=$t", "Nachricht an Symbiose")));
		if ($inhalte_s["alli"] == 0) $ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symbiose&a=bewerb&symb=$t", "Bei Symbiose Bewerben")));

		$result_p = mysql_query("SELECT alli1,typ FROM genesis_politik WHERE ((alli1='$t' and alli2='" . $inhalte_s["alli"] . "') or (alli2='$t'and alli1='" . $inhalte_s["alli"] . "'))");
		$inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC);
		if ($inhalte_p["typ"] == 7) $ausgabe .= tr(td(2, "center", "<font color=\"lime\">Bündnis-Partner</font>"));
		if ($inhalte_p["typ"] == 5) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Kriegs-Gegner</font>"));
		if ($inhalte_p["typ"] == 3 && $inhalte_p["alli1"] == $inhalte_s["alli"]) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Feind</font>"));
		if ($inhalte_p["typ"] == 1 && $inhalte_p["alli1"] == $inhalte_s["alli"]) $ausgabe .= tr(td(2, "center", "<font color=\"lime\">Freund</font>"));

		$ausgabe1 = tr(td(2, "head", "Symbiosen History"));
		$result_polit = mysql_query("SELECT * FROM genesis_history WHERE (alli1='" . $inhalte["tag"] . "' OR alli2='" . $inhalte["tag"] . "') and (typ='5' or typ='6' or typ='7' or typ='8') ORDER BY zeit DESC");
		while ($inhalte_polit = mysql_fetch_array($result_polit, MYSQL_ASSOC)) {
			$ausgabe .= $ausgabe1;
			$ausgabe1 = "";
			$ausgabe2 = "";
			$ausgabe3 = "";
			if ($inhalte["tag"] == $inhalte_polit["alli2"]) $inhalte_polit["alli2"] = $inhalte_polit["alli1"];
			if ($inhalte_polit["typ"] == 7) $ausgabe2 = "Beginn des Bündnisses";
			if ($inhalte_polit["typ"] == 8) $ausgabe2 = "Ende des Bündnisses";
			if ($inhalte_polit["typ"] != 5 && $inhalte_polit["typ"] != 6) $ausgabe3 = "$ausgabe2 mit [" . $inhalte_polit["alli2"] . "]";
			if ($inhalte_polit["typ"] == 5) {
				if ($inhalte_polit["alli2"] == $inhalte_polit["alli1"]) {
					$ausgabe2 = "von";
				} else {
					$ausgabe2 = "an";
				}
				$ausgabe3 = "Kriegserklärung $ausgabe2 [" . $inhalte_polit["alli2"] . "]";
			}
			if ($inhalte_polit["typ"] == 6) {
				if (($inhalte_polit["zusatz"] == 1 && $inhalte_polit["alli2"] == $inhalte_polit["alli1"]) || ($inhalte_polit["zusatz"] == 2 && $inhalte_polit["alli2"] != $inhalte_polit["alli1"])) $ausgabe2 = "Niederlage";
				if (($inhalte_polit["zusatz"] == 1 && $inhalte_polit["alli2"] != $inhalte_polit["alli1"]) || ($inhalte_polit["zusatz"] == 2 && $inhalte_polit["alli2"] == $inhalte_polit["alli1"])) $ausgabe2 = "Sieg";
				if ($inhalte_polit["zusatz"] == 3) $ausgabe2 = "Unentschieden";
				$ausgabe3 = "Kriegsende gegen [" . $inhalte_polit["alli2"] . "] - Ausgang: $ausgabe2";
			}
			$ausgabe .= tr(td(0, "center", date("d.m.Y (H:i:s)", $inhalte_polit["zeit"])) . td(0, "center", $ausgabe3));
		}
		$ausgabe .= tr(td(2, "head", "Beschreibung"));
		$ausgabe .= tr(td(2, "center", parsetxt($inhalte["beschreibung"])));
	} else {
		$ausgabe .= tr(td(2, "center", "unbekannt"));
	}
	$ausgabe .= "</table>\n";
} elseif (strpos($t, "spieler") !== false) {
	$t = substr($t, 7);
	$result = mysql_query("SELECT * FROM genesis_spieler WHERE id='$t'");
	$ausgabe .= table(400, "bg");
	$ausgabe .= tr(td(2, "head", "SpielerInfo"));
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($t == $sid || ($inhalte["alli"] == $inhalte_s["alli"] && $inhalte["alli"] > 0)) {
			$handle = opendir("images/graphs");
			while (false !== ($file = readdir($handle))) if (filetype("images/graphs/$file") == "file" && filemtime("images/graphs/$file") < (time()-60)) unlink("images/graphs/$file");
			if ($inhalte["punktek"] > 0) {
				include("class_eq_pie.inc.php");
				$eq_pie = new eq_pie;
				$data[0][0] = "Ausbau";
				$data[0][1] = $inhalte["punktek"];
				$data[0][2] = "#0000B6";
				if ($inhalte["punktef"] > 0) {
					$data[1][0] = "Evolution";
					$data[1][1] = $inhalte["punktef"];
					$data[1][2] = "#009000";
				}
				if ($inhalte["punktem"] > 0) {
					$data[2][0] = "Zellen";
					$data[2][1] = $inhalte["punktem"];
					$data[2][2] = "#919100";
				}
				if ($inhalte["kampfpkt"] > 0) {
					$data[3][0] = "Kampfpkt.";
					$data[3][1] = round($inhalte["kampfpkt"] * 0.3, 0);
					$data[3][2] = "#D2691E";
				}
				$zeit = time();
				$eq_pie -> MakePie('images/graphs/' . $zeit . '.png', '150', '110', '10', '#000000' , $data, '1');
			}
		}
		if ($k == "") $k = $inhalte["basis1"];
		$kds = explode(":", $k);
		$kx = $kds[0];
		$ky = $kds[1];
		$kz = $kds[2];
		$result3 = mysql_query("SELECT bild FROM genesis_basen where name='" . $inhalte["name"] . "' and koordx='$kx' and koordy='$ky' and koordz='$kz'");
		$inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);

		$result_noob = mysql_query("SELECT ROUND(sum(punktem) / count(*),0) FROM genesis_spieler");
		$inhalte_noob = mysql_fetch_array($result_noob, MYSQL_NUM);

		$ausgabe .= tr(td(2, "center", "<img src=\"bilder/neogen" . $inhalte3["bild"] . ".jpg\" height=320 width=320>"));
		$ausgabe .= tr(td(0, "left", "Name") . td(0, "right", $inhalte["name"]));
		$ausgabe .= tr(td(0, "left", "Koordinaten") . td(0, "right", $k));
		if ($inhalte["alli"] != 0) {
			$result2 = mysql_query("SELECT tag FROM genesis_allianzen where id='" . $inhalte["alli"] . "'");
			$inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
			$ausgabe .= tr(td(0, "left", "Symbiose") . td(0, "right", "[" . hlink("", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte["alli"], $inhalte2["tag"]) . "]"));
		}
		$ausgabe .= tr(td(0, "left", "Ausbaupunkte") . td(0, "right", format($inhalte["punktek"])));
		if ($t == $sid) {
			$ausgabe .= tr(td(0, "left", "Evolutionspunkte") . td(0, "right", format($inhalte["punktef"])));
			$ausgabe .= tr(td(0, "left", "Zellpunkte") . td(0, "right", format($inhalte["punktem"])));
		}
		$ausgabe .= tr(td(0, "left", "Gesamtpunkte") . td(0, "right", format($inhalte["punkte"])));
		$ausgabe .= tr(td(0, "left", "Kampfpunkte") . td(0, "right", format($inhalte["kampfpkt"])));
		/*
		if ($t == $sid) {
			if ($inhalte["punkte"] <= 400) {
				$ausgabe .= tr(td(0, "left", "Noobschutz") . td(0, "right", "immer , da weniger als 400 Punkte"));
			} elseif (($inhalte_noob[0] + $inhalte["attcount"]) > 0) {
				$ausgabe .= tr(td(0, "left", "Noobschutz bis") . td(0, "right", format($inhalte_noob[0] + $inhalte["attcount"]) . " Zellpunkte"));
			} else {

			}
		}
		*/

		$ausgabe .= tr(td(0, "left", "Noobschutz") . td(0, "right", "nicht aktiv"));
		$dist = round(sqrt(pow(abs($bk[2] - $kz) * 100, 2) + pow(abs($bk[1] - $ky) * 100, 2) + pow(abs($bk[0] - $kx) * 100, 2)), 2);
		if ($t != $sid) $ausgabe .= tr(td(0, "left", "Entfernung") . td(0, "right", format($dist)));

		if ($t != $sid) {
			if ($inhalte["urlaub"] > time()) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Der Spieler befindet sich im Urlaubsmodus</font>"));
			if ($inhalte["gesperrt"] > time()) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Der Spieler ist gesperrt</font>"));
			if (isnoob($sid, $t) == true) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Der Spieler ist im Noobschutz</font>"));

			if (isnoob($t, $sid) == true) {
				$ausgabe .= tr(td(2, "center", "<font color=\"lime\">Du bist geschützt vor diesem Spieler</font>"));
			} else {
				$ausgabe .= tr(td(2, "center", "<font color=\"red\">Du bist nicht geschützt vor diesem Spieler</font>"));
			}

			$result_p = mysql_query("SELECT alli1,typ FROM genesis_politik WHERE ((alli1='" . $inhalte["alli"] . "' and alli2='" . $inhalte_s["alli"] . "') or (alli2='" . $inhalte["alli"] . "'and alli1='" . $inhalte_s["alli"] . "'))");
			$inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC);
			if ($inhalte_p["typ"] == 7) $ausgabe .= tr(td(2, "center", "<font color=\"lime\">Bündnis-Partner</font>"));
			if ($inhalte_p["typ"] == 5) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Kriegs-Gegner</font>"));
			if ($inhalte_p["typ"] == 3 && $inhalte_p["alli1"] == $inhalte_s["alli"]) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Feind</font>"));
			if ($inhalte_p["typ"] == 1 && $inhalte_p["alli1"] == $inhalte_s["alli"]) $ausgabe .= tr(td(2, "center", "<font color=\"lime\">Freund</font>"));

			$ausgabe .= tr(td(2, "head", "Aktionen"));
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=nachrichten&w=new&empf=" . str_replace(" ", "%20", $inhalte["name"]), "Nachricht schreiben")));
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=mission&mkx=$kx&mky=$ky&mkz=$kz&me6=" . $inhalte_s["spios"] . "&aktion=Auftrag%20erteilen&mt=3", "Scannen")
					. " - " . hlink("", "game.php?id=$id&b=$b&nav=mission&mkx=$kx&mky=$ky&mkz=$kz", "Mission")
					. " - " . hlink("", "game.php?id=$id&b=$b&nav=karte&krx=$kx&kry=$ky&krz=$kz", "Karte")));
		}
		if (is_file($inhalte["avatar"])) {
			$ausgabe .= tr(td(2, "head", "Avatar"));
			$ausgabe .= tr(td(2, "center", "<img src=\"" . $inhalte["avatar"] . "\">"));
		}
		if ($inhalte["profil"]) {
			$ausgabe .= tr(td(2, "head", "Profil"));
			$ausgabe .= tr(td(2, "center", parsetxt($inhalte["profil"])));
		}
		if ($inhalte["name"] == "Mac A. Roni") {
			$ausgabe .= tr(td(2, "head", "Auszeichnungen"));
			$ausgabe .= tr(td(2, "center", "Gewinner des Osterquests"));
		}
		if ($inhalte["punktek"] > 0) {
			if ($t == $sid || ($inhalte["alli"] == $inhalte_s["alli"] && $inhalte["alli"] > 0)) {
				$ausgabe .= tr(td(2, "head", "Punkteverteilung"));
				$ausgabe .= tr(td(2, "center", "<img src=\"images/graphs/$zeit.png\" border=\"0\">"));
			}
		}
	} else {
		$ausgabe .= tr(td(2, "center", "unbekannt"));
	}
	$ausgabe .= "</table>\n";
} elseif ($a == "news" && $t > 0) {
	if ($aktion != "Eintragen") {
		$ausgabe .= form("game.php?id=$id&b=$b&nav=info&a=news&t=$t");
		$ausgabe .= table(400, "bg");
		$ausgabe .= tr(td(0, "head", "Neue Nachricht"));
		$ausgabe .= tr(td(0, "center", "<textarea name=\"newstxt\" cols=60 rows=10></textarea>"));
		$ausgabe .= tr(td(0, "center", input("submit", "aktion", "Eintragen")));
		$ausgabe .= "</table>\n</form>\n";
	} elseif ($aktion == "Eintragen" && $newstxt != "") {
		mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news,newsalt) VALUES ('$sid','$t','". time() ."','alli_news','". sauber($newstxt) ."','1')");
		$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=info&t=alli$t'\",1000)</script>\n";
	}
}

unset ($result, $inhalte, $results, $inhaltes);

?>