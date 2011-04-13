<?php

$result = mysql_query("SELECT typ, bezeichnung FROM genesis_infos");
while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$namen[$inhalte["typ"]] = $inhalte["bezeichnung"];
}

$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
$aktion2 = isset($_REQUEST["aktion2"]) ? $_REQUEST["aktion2"] : NULL;
$bids = isset($_REQUEST["bids"]) ? $_REQUEST["bids"] : NULL;
$proda1 = isset($_REQUEST["proda1"]) ? $_REQUEST["proda1"] : NULL;
$proda2 = isset($_REQUEST["proda2"]) ? $_REQUEST["proda2"] : NULL;
$proda3 = isset($_REQUEST["proda3"]) ? $_REQUEST["proda3"] : NULL;
$proda4 = isset($_REQUEST["proda4"]) ? $_REQUEST["proda4"] : NULL;
$proda5 = isset($_REQUEST["proda5"]) ? $_REQUEST["proda5"] : NULL;
$proda6 = isset($_REQUEST["proda6"]) ? $_REQUEST["proda6"] : NULL;
$proda7 = isset($_REQUEST["proda7"]) ? $_REQUEST["proda7"] : NULL;
$forscha1 = isset($_REQUEST["forscha1"]) ? $_REQUEST["forscha1"] : NULL;
$forscha3 = isset($_REQUEST["forscha3"]) ? $_REQUEST["forscha3"] : NULL;
$forscha4 = isset($_REQUEST["forscha4"]) ? $_REQUEST["forscha4"] : NULL;
$prodv1 = isset($_REQUEST["prodv1"]) ? $_REQUEST["prodv1"] : NULL;
$prodv2 = isset($_REQUEST["prodv2"]) ? $_REQUEST["prodv2"] : NULL;
$prodv3 = isset($_REQUEST["prodv3"]) ? $_REQUEST["prodv3"] : NULL;
$prodv4 = isset($_REQUEST["prodv4"]) ? $_REQUEST["prodv4"] : NULL;
$prodv5 = isset($_REQUEST["prodv5"]) ? $_REQUEST["prodv5"] : NULL;
$prodv6 = isset($_REQUEST["prodv6"]) ? $_REQUEST["prodv6"] : NULL;
$prodv7 = isset($_REQUEST["prodv7"]) ? $_REQUEST["prodv7"] : NULL;
$forschv1 = isset($_REQUEST["forschv1"]) ? $_REQUEST["forschv1"] : NULL;
$forschv3 = isset($_REQUEST["forschv3"]) ? $_REQUEST["forschv3"] : NULL;
$forschv4 = isset($_REQUEST["forschv4"]) ? $_REQUEST["forschv4"] : NULL;
$vert1 = isset($_REQUEST["vert1"]) ? $_REQUEST["vert1"] : NULL;
$vert2 = isset($_REQUEST["vert2"]) ? $_REQUEST["vert2"] : NULL;
$vert3 = isset($_REQUEST["vert3"]) ? $_REQUEST["vert3"] : NULL;
$immu = isset($_REQUEST["immu"]) ? $_REQUEST["immu"] : NULL;
$krds2 = isset($_REQUEST["krds2"]) ? $_REQUEST["krds2"] : NULL;
$debug = false;

if ($aktion == "Angreifer") {
	$proda1 = $inhalte_b["prod1"];
	$proda2 = $inhalte_b["prod2"];
	$proda3 = $inhalte_b["prod3"];
	$proda4 = $inhalte_b["prod4"];
	$proda5 = $inhalte_b["prod5"];
	$proda6 = $inhalte_b["prod6"];
	$proda7 = $inhalte_b["prod7"];
	$forscha1 = $inhalte_s["forsch1"];
	$forscha4 = $inhalte_s["forsch4"];
	$forscha3 = $inhalte_s["forsch3"];
}

if ($aktion == "Verteidiger") {
	$prodv1 = $inhalte_b["prod1"];
	$prodv2 = $inhalte_b["prod2"];
	$prodv3 = $inhalte_b["prod3"];
	$prodv4 = $inhalte_b["prod4"];
	$prodv5 = $inhalte_b["prod5"];
	$prodv6 = $inhalte_b["prod6"];
	$prodv7 = $inhalte_b["prod7"];
	$vert1 = $inhalte_b["vert1"];
	$vert2 = $inhalte_b["vert2"];
	$vert3 = $inhalte_b["vert3"];
	$forschv1 = $inhalte_s["forsch1"];
	$forschv4 = $inhalte_s["forsch4"];
	$forschv3 = $inhalte_s["forsch3"];
	$immu = $inhalte_b["konst15"];
}
if ($bids != "" && $bids != "debug") {
	if (strlen($bids) > 10) {
		$bids = explode("?id=", $bids);
		$bids = explode("&", $bids[1]);
		$bids = $bids[0];
	}
} elseif ($bids == "debug") {
	$debug = true;
	$bids = "";
}

if ($aktion2 == "Angreifer" && $bids != "" && $bids != "debug") {
	$result = mysql_query("SELECT koords,forsch1,forsch3,forsch4,filter FROM genesis_berichte WHERE id='$bids'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$krds2 = $inhalte["koords"];
		$filter = preg_split('//', $inhalte["filter"], -1, PREG_SPLIT_NO_EMPTY);
		if ($filter[28] == 1) {
			$forscha1 = $inhalte["forsch1"];
		} elseif ($krds != $inhalte["koords"]) {
			unset($forscha1);
		}
		if ($filter[28 + 2] == 1) {
			$forscha3 = $inhalte["forsch3"];
		} elseif ($krds != $inhalte["koords"]) {
			unset($forscha3);
		}
		if ($filter[28 + 3] == 1) {
			$forscha4 = $inhalte["forsch4"];
		} elseif ($krds != $inhalte["koords"]) {
			unset($forscha4);
		}
	}
	$result = mysql_query("SELECT koords,prod1,prod2,prod3,prod4,prod5,prod6,prod7 FROM genesis_deff WHERE id='$bids'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		for ($i = 1; $i <= 8; $i++) {
			eval("if (\$filter[\$i-1] == 1) { \$proda$i = \$inhalte[\"prod$i\"]; }");
			eval("if (\$filter[\$i-1] == 0 && \$krds != \$inhalte[\"koords\"]) { unset(\$proda$i); }");
		}
	}
}
if ($aktion2 == "Verteidiger" && $bids != "" && $bids != "debug") {
	$result = mysql_query("SELECT koords,konst15,forsch1,forsch3,forsch4,filter FROM genesis_berichte WHERE id='$bids'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$krds2 = $inhalte["koords"];
		$filter = preg_split('//', $inhalte["filter"], -1, PREG_SPLIT_NO_EMPTY);
		if ($filter[28] == 1) {
			$forschv1 = $inhalte["forsch1"];
		} elseif ($krds != $inhalte["koords"]) {
			unset($forschv1);
		}
		if ($filter[28 + 2] == 1) {
			$forschv3 = $inhalte["forsch3"];
		} elseif ($krds != $inhalte["koords"]) {
			unset($forschv3);
		}
		if ($filter[28 + 3] == 1) {
			$forschv4 = $inhalte["forsch4"];
		} elseif ($krds != $inhalte["koords"]) {
			unset($forschv4);
		}
		if ($filter[10 + 15] == 1) {
			$immu = $inhalte["konst15"];
		} elseif ($krds != $inhalte["koords"]) {
			unset($immu);
		}
	}
	$result = mysql_query("SELECT koords,prod1,prod2,prod3,prod4,prod5,prod6,prod7,vert1,vert2,vert3 FROM genesis_deff WHERE id='$bids'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		for ($i = 1; $i <= 8; $i++) {
			eval("if (\$filter[\$i-1] == 1) { \$prodv$i = \$inhalte[\"prod$i\"]; }");
			eval("if (\$filter[\$i-1] == 0 && \$krds != \$inhalte[\"koords\"]) { unset(\$prodv$i); }");
		}
		for ($i = 1; $i <= 3; $i++) {
			eval("if (\$filter[\$i+8-1] == 1) { \$vert$i = \$inhalte[\"vert$i\"]; }");
			eval("if (\$filter[\$i+8-1] == 0 && \$krds != \$inhalte[\"koords\"]) {unset( \$vert$i); }");
		}
	}
}

if ($aktion == "Berechne") {
	$angr_wert1 = 0;
	$angr_wert2 = 0;
	$vert_wert1 = 0;
	$vert_wert2 = 0;

	for ($i = 1; $i <= 7; $i++) {
		$resultw = mysql_query("SELECT wert3,wert4 FROM genesis_infos WHERE typ='prod$i'");
		$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
		$anz = 0;
		eval("\$anz = \$proda$i;");
		$angr_wert1 += angr("prod$i", $werte["wert3"], $forscha1, $forscha3) * $anz;
		eval("\$anz = \$prodv$i;");
		$angr_wert2 += angr("prod$i", $werte["wert3"], $forschv1, $forschv3) * $anz;
		eval("\$anz = \$proda$i;");
		$vert_wert1 += vert("prod$i", $werte["wert4"], $forscha4, $forscha3) * $anz;
		eval("\$anz = \$prodv$i;");
		$vert_wert2 += vert("prod$i", $werte["wert4"], $forschv4, $forschv3) * $anz;
	}

	for ($i = 1; $i <= 3; $i++) {
		$resultw = mysql_query("SELECT wert3,wert4 FROM genesis_infos WHERE typ='vert$i'");
		$werte = mysql_fetch_array($resultw, MYSQL_ASSOC);
		$anz = 0;
		eval("\$anz = \$vert$i;");
		$angr_wert2 += angr("vert$i", $werte["wert3"], $forschv1, $forschv3) * $anz;
		eval("\$anz = \$vert$i;");
		$vert_wert2 += vert("vert$i", $werte["wert4"], $forschv4, $forschv3) * $anz;
	}

	$angr_wert2 += angr_immu($forschv1, $immu);
	$vert_wert2 += vert_immu($forschv4, $immu);

	if ($debug) {
		echo "angr_immu: " . angr_immu($forschv1, $immu) . "<br>";
		echo "vert_immu: " . vert_immu($forschv4, $immu) . "<br>";
		echo "angr_wert1: $angr_wert1<br>";
		echo "vert_wert1: $vert_wert1<br>";
		echo "angr_wert2: $angr_wert2<br>";
		echo "vert_wert2: $vert_wert2<br>";
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
		$faktor2 = (1 / $verh);
	} else {
		$faktor1 = $verh;
		$faktor2 = 1;
	}
	$angr_wert1 = round($angr_wert1 * $faktor1, 0) + 1;
	$vert_wert1 = round($vert_wert1 * $faktor1, 0) + 1;
	$angr_wert2 = round($angr_wert2 * $faktor2, 0) + 1;
	$vert_wert2 = round($vert_wert2 * $faktor2, 0) + 1;
	$chance3 = round($angr_wert1 / ($vert_wert2 + 1) * 60, 1);
	$diff = $angr_wert1 - (vert_immu($forschv4, $immu) * 3) - $vert_wert1;
	// - ($diff / 10)
	$chance = round(($angr_wert2) / $vert_wert1 * 100, 1);
	$chance2 = round(($angr_wert1 - (vert_immu($forschv4, $immu) * 3)) / $vert_wert2 * 100, 1);

	if ($chance < 0) $chance = 0;
	if ($chance > 100) $chance = 100;
	if ($chance2 < 0) $chance2 = 0;
	if ($chance2 == -0) $chance2 = 0;
	if ($chance2 > 100) $chance2 = 100;
	if ($chance3 < 0) $chance3 = 0;
	if ($chance3 > 100) $chance3 = 100;
	if ($chance == 100) $chance3 = 0;

	if ($debug) {
		echo "verh: $verh<br>";
		echo "faktor1: $faktor1<br>faktor2: $faktor2<br>";
		echo "angr_wert1: $angr_wert1<br>";
		echo "vert_wert1: $vert_wert1<br>";
		echo "angr_wert2: $angr_wert2<br>";
		echo "vert_wert2: $vert_wert2<br>";
		echo "angr_wert1-vert_immu*3: " . ($angr_wert1 - round(vert_immu($forschv4, $immu) * 3)) . "<br>";
		echo "diff: $diff<br>";
		echo ($angr_wert2 - ($diff / 10));
	}

} else {
	$chance = "n/a";
	$chance2 = "n/a";
	$chance3 = "n/a";
}

if ($aktion == "Zurücksetzen") {
	unset($chance, $chance2, $chance3, $proda1,$proda2, $proda3, $proda4, $proda5, $proda6 ,$proda7);
	unset($forscha1, $forscha4, $forscha3, $prodv1, $prodv2, $prodv3, $prodv4, $prodv5 , $prodv6 ,$prodv7);
	unset($vert1, $vert2, $vert3, $forschv1, $forschv4, $forschv3, $immu, $bids, $krds2);
}

if ($debug == true) $bids = "debug";

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= table(500, "bg");
$ausgabe .= tr(td(3, "head", "Simulator" . input("hidden", "krds", $krds2)));
$ausgabe .= tr(
	td(0, "left", "Eigene Werte als:")
	 . td(0, "center", input("submit", "aktion", "Angreifer"))
	 . td(0, "center", input("submit", "aktion", "Verteidiger"))
	);
$ausgabe .= tr(td(0, "left", "Bericht-URL/ID: ") . td(2, "center", input("text", "bids", $bids)));
$ausgabe .= tr(
	td(0, "left", "Werte aus Bericht als:")
	 . td(0, "center", input("submit", "aktion2", "Angreifer"))
	 . td(0, "center", input("submit", "aktion2", "Verteidiger"))
	);
$ausgabe .= tr(td(3, "hr", "<hr>"));
$ausgabe .= tr(
	td(0, "left", "&nbsp;")
	 . td(0, "navi", "Angreifer")
	 . td(0, "navi", "Verteidiger")
	);
for ($i = 1; $i <= 7; $i++) {
	eval("\$anz1 = \$proda$i;");
	eval("\$anz2 = \$prodv$i;");
	$ausgabe .= tr(
		td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod$i", $namen["prod$i"]))
		 . td(0, "center", input("zahl", "proda$i", $anz1))
		 . td(0, "center", input("zahl", "prodv$i", $anz2))
		);
}
for ($i = 1; $i <= 3; $i++) {
	eval("\$anz1 = \$vert$i;");
	$ausgabe .= tr(
		td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=vert$i", $namen["vert$i"]))
		 . td(0, "center", "&nbsp;")
		 . td(0, "center", input("zahl", "vert$i", $anz1))
		);
}
$ausgabe .= tr(
	td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst15", $namen["konst15"]))
	 . td(0, "center", "&nbsp;")
	 . td(0, "center", input("zahl", "immu", $immu))
	);
$ausgabe .= tr(td(3, "hr", "<hr>"));
$ausgabe .= tr(
	td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch1", $namen["forsch1"]))
	 . td(0, "center", input("zahl", "forscha1", $forscha1))
	 . td(0, "center", input("zahl", "forschv1", $forschv1))
	);
$ausgabe .= tr(
	td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch4", $namen["forsch4"]))
	 . td(0, "center", input("zahl", "forscha4", $forscha4))
	 . td(0, "center", input("zahl", "forschv4", $forschv4))
	);
$ausgabe .= tr(
	td(0, "left", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch3", $namen["forsch3"]))
	 . td(0, "center", input("zahl", "forscha3", $forscha3))
	 . td(0, "center", input("zahl", "forschv3", $forschv3))
	);
$ausgabe .= tr(td(3, "hr", "<hr>"));
$ausgabe .= tr(td(3, "center", input("submit", "aktion", "Berechne"))); //  ." ". input("submit", "aktion", "Zurücksetzen")
$ausgabe .= tr(td(3, "hr", "<hr>"));
$ausgabe .= tr(td(2, "left", "Verlustrate Angreifer:") . td(0, "center", "$chance %"));
$ausgabe .= tr(td(2, "left", "Verlustrate Verteidiger:") . td(0, "center", "$chance2 %"));
$ausgabe .= tr(td(2, "left", "Chance auf Nährstoffplünderung:") . td(0, "center", "$chance3 %"));

$ausgabe .= "</table>\n</form>\n";

?>