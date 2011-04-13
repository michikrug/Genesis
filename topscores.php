<?php

$seite = isset($_REQUEST["seite"]) ? $_REQUEST["seite"] : NULL;
$t = isset($_REQUEST["t"]) ? $_REQUEST["t"] : NULL;
$such = isset($_REQUEST["such"]) ? $_REQUEST["such"] : NULL;
$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;

$result = mysql_query("SELECT count(*) FROM genesis_spieler");
$inhalte = mysql_fetch_array($result, MYSQL_NUM);
$sanz = $inhalte[0];
if (bcdiv($sanz, 50) * 50 < $sanz) {
	$seiten = bcdiv($sanz, 50) + 1;
} else {
	$seiten = bcdiv($sanz, 50);
}

if ($seite == 0 || $seite == "") $seite = 1;
if (!isset($spieler)) $spieler = " ";
if ($t == "") $t = 0;

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav&t=$t");
$ausgabe .= table(500, "bg");
$ausgabe .= tr(td(6, "head", "Top-Scores"));

if ($t == 0 || $t == 1) {
	$cla = "";
} else {
	$cla = "nc";
}
$out = hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=0", "Spieler") . "&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;";
if ($t == 2) {
	$cla = "";
} else {
	$cla = "nc";
}
$out .= hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=2", "Kampfpunkte") . "&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;";
if ($t == 3 || $t == 4 || $t == 5 || $t == 6) {
	$cla = "";
} else {
	$cla = "nc";
}
$out .= hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=3", "Symbiosen") . "&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;";
if ($t == 7) {
	$cla = "";
} else {
	$cla = "nc";
}
$out .= hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=7", "Stufen") . "&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;";
if ($t == 8) {
	$cla = "";
} else {
	$cla = "nc";
}
$out .= hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=8", "Suchen");

$ausgabe .= tr(td(6, "navi", $out));

if ($t >= 0 && $t <= 2) {
	$out = "Plätze <select name=\"seite\">";
	for ($opt = 1; $opt <= $seiten; $opt++) {
		$out .= "<option value=\"$opt\"";
		if ($opt == $seite) {
			$out .= " selected";
		}
		$out .= ">" . ($opt * 50 - 49) . "-" . ($opt * 50) . "</option>\n";
	}
	$out .= "</select>";
	$ausgabe .= tr(td(6, "navi", "$out&nbsp;&nbsp;" . input("submit", "aktion", "Anzeigen")));
}

if ($t == 0 || $t == 1) {
	$out = td(0, "head", "Rang");
	$out .= td(0, "head", "Name");
	$out .= td(0, "head", "Symbiose");
	if ($t == 1) {
		$cla = "";
	} else {
		$cla = "nc";
	}
	$out .= td(0, "head", hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=1&seite=$seite", "Ausbau"));
	if ($t == 0) {
		$cla = "";
	} else {
		$cla = "nc";
	}
	$out .= td(0, "head", hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=0&seite=$seite", "Gesamt"));
	$ausgabe .= tr($out);

	$i = ($seite - 1) * 50;

	if ($t == 0) $result = mysql_query("SELECT id,name,alli,punkte,punktek,punktem,attcount,log FROM genesis_spieler WHERE id>1 ORDER BY punkte DESC,id LIMIT $i,50");
	if ($t == 1) $result = mysql_query("SELECT id,name,alli,punkte,punktek,punktem,attcount,log FROM genesis_spieler WHERE id>1 ORDER BY punktek DESC,id LIMIT $i,50");

	while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$i++;
		if ($inhalte["alli"] != 0) {
			$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte["alli"] . "'");
			$inhaltea = mysql_fetch_array($resulta, MYSQL_ASSOC);
			$alli = "[" . hlink("nc", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte["alli"], $inhaltea["tag"]) . "]";
			unset($resulta, $inhaltea);
		} else {
			$alli = "-";
		}
		if ($inhalte["name"] == $name) {
			$cla = "hl";
		} else {
			$cla = "center";
		}
		$ausgabe .= tr(td(0, $cla, $i)
			 . td(0, $cla, hlink("nc", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte["id"], $inhalte["name"]))
			 . td(0, $cla, $alli)
			 . td(0, $cla, format($inhalte["punktek"]))
			 . td(0, $cla, format($inhalte["punkte"])));
	}

} elseif ($t == 2) {

	$ausgabe .= tr(td(0, "head", "Rang") . td(0, "head", "Name") . td(0, "head", "Symbiose") . td(0, "head", "Kampfpunkte"));

	$i = ($seite - 1) * 50;
	$result = mysql_query("SELECT id,name,alli,kampfpkt FROM genesis_spieler WHERE id>1 ORDER BY kampfpkt DESC,id LIMIT $i,50");
	while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$i++;
		if ($inhalte["alli"] != 0) {
			$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte["alli"] . "'");
			$inhaltea = mysql_fetch_array($resulta, MYSQL_ASSOC);
			$alli = "[" . hlink("nc", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte["alli"], $inhaltea["tag"]) . "]";
			unset($resulta, $inhaltea);
		} else {
			$alli = "-";
		}
		if ($inhalte["name"] == $name) {
			$cla = "hl";
		} else {
			$cla = "center";
		}
		$ausgabe .= tr(td(0, $cla, $i)
			 . td(0, $cla, hlink("nc", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte["id"], $inhalte["name"]))
			 . td(0, $cla, $alli)
			 . td(0, $cla, format($inhalte["kampfpkt"])));
	}

} elseif ($t == 3 || $t == 4 || $t == 5 || $t == 6) {
	$out = td(0, "head", "Rang");
	$out .= td(0, "head", "Name");
	if ($t == 6) {
		$cla = "";
	} else {
		$cla = "nc";
	}
	$out .= td(0, "head", hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=6&seite=$seite", "Mitglieder"));
	if ($t == 5) {
		$cla = "";
	} else {
		$cla = "nc";
	}
	$out .= td(0, "head", hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=5&seite=$seite", "Durchschnitt"));
	if ($t == 4) {
		$cla = "";
	} else {
		$cla = "nc";
	}
	$out .= td(0, "head", hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=4&seite=$seite", "Kampfpkt"));
	if ($t == 3) {
		$cla = "";
	} else {
		$cla = "nc";
	}
	$out .= td(0, "head", hlink($cla, "game.php?id=$id&b=$b&nav=$nav&t=3&seite=$seite", "Gesamt"));

	$ausgabe .= tr($out);

	if ($t == 3) $result = mysql_query("SELECT id,tag,name,anz,punkte,kampfpkt,punkted FROM genesis_allianzen where anz>2 ORDER BY punkte DESC,id");
	if ($t == 4) $result = mysql_query("SELECT id,tag,name,anz,punkte,kampfpkt,punkted FROM genesis_allianzen where anz>2 ORDER BY kampfpkt DESC,id");
	if ($t == 5) $result = mysql_query("SELECT id,tag,name,anz,punkte,kampfpkt,punkted FROM genesis_allianzen where anz>2 ORDER BY punkted DESC,id");
	if ($t == 6) $result = mysql_query("SELECT id,tag,name,anz,punkte,kampfpkt,punkted FROM genesis_allianzen where anz>2 ORDER BY anz DESC,id");

	$i = 0;
	while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$i++;
		if ($inhalte["id"] == $inhalte_s["alli"]) {
			$cla = "hl";
		} else {
			$cla = "center";
		}
		$ausgabe .= tr(td(0, $cla, $i)
			 . td(0, $cla, "[" . hlink("nc", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte["id"], $inhalte["tag"]) . "]")
			 . td(0, $cla, $inhalte["anz"])
			 . td(0, $cla, format($inhalte["punkted"]))
			 . td(0, $cla, format($inhalte["kampfpkt"]))
			 . td(0, $cla, format($inhalte["punkte"])));
	}
	$ausgabe .= tr(td(6, "center", "(Symbiosenranking erst ab 3 Mitgliedern)"));

} elseif ($t == 7) {

	$result = mysql_query("SELECT typ, bezeichnung FROM genesis_infos");
	while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) $namen[$inhalte["typ"]] = $inhalte["bezeichnung"];

	$patt = "";
	for ($i = 1; $i <= 17; $i++) $patt .= "max(konst$i) as konst$i, ";
	$patt = substr($patt,0,strlen($patt)-2);
	$result = mysql_query("SELECT $patt FROM genesis_basen WHERE id<>315");
	$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);

	$ausgabe .= tr(td(0, "head", "Ausbauten") . td(0, "head", "Stufe"));
	for ($i = 1; $i <= 17; $i++) {
		if ($inhalte_b["konst$i"] == $inhalte["konst$i"] && $inhalte["konst$i"] > 0) {
			$cla = "hl";
			$out = $inhalte["konst$i"];
		} else {
			$cla = "center";
			$out = $inhalte["konst$i"] . " (" . $inhalte_b["konst$i"] . ")";
		}
		$ausgabe .= tr(td(0, $cla, hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst$i", $namen["konst$i"])) . td(0, $cla, $out));
	}

	$patt = "";
	for ($i = 1; $i <= 8; $i++) $patt .= "max(forsch$i) as forsch$i, ";
	$patt = substr($patt,0,strlen($patt)-2);
	$result = mysql_query("SELECT $patt FROM genesis_spieler WHERE id>1");
	$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);

	$ausgabe .= tr(td(0, "head", "Evolutionen") . td(0, "head", "Stufe"));
	for ($i = 1; $i <= 8; $i++) {
		if ($inhalte_s["forsch$i"] == $inhalte["forsch$i"] && $inhalte["forsch$i"] > 0) {
			$cla = "hl";
			$out = $inhalte["forsch$i"];
		} else {
			$cla = "center";
			$out = $inhalte["forsch$i"] . " (" . $inhalte_s["forsch$i"] . ")";
		}
		$ausgabe .= tr(td(0, $cla, hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch$i", $namen["forsch$i"])) . td(0, $cla, $out));
	}

} elseif ($t == 8) {
	if ($such != "" && $aktion == "Suchen") {
		$ausgabe .= tr(td(0, "head", "Name") . td(0, "head", "Symbiose") . td(0, "head", "Koordinaten"));
		$result = mysql_query("SELECT id,name,alli,basis1 FROM genesis_spieler where name like '%$such%' ORDER BY name");
		while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($inhalte["alli"] != 0) {
				$resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte["alli"] . "'");
				$inhaltea = mysql_fetch_array($resulta, MYSQL_ASSOC);
				$alli = "[" . hlink("nc", "game.php?id=$id&nav=info&t=alli" . $inhalte["alli"], $inhaltea["tag"]) . "]";
				unset($resulta, $inhaltea);
			} else {
				$alli = "-";
			}
			$krd = explode(":", $inhalte["basis1"]);
			$ausgabe .= tr(
				td(0, "center", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte["id"], $inhalte["name"]))
				 . td(0, "center", $alli)
				 . td(0, "center", hlink("nc", "game.php?id=$id&b=$b&nav=karte&krx=" . $krd[0] . "&kry=" . $krd[1], $inhalte["basis1"])));
		}
	} else {
		$ausgabe .= tr(td(0, "center", "Name oder Teil des Namen") . td(0, "center", input("text", "such", "")) . td(0, "center", input("submit", "aktion", "Suchen")));
	}
}

$ausgabe .= "</table>\n";

?>