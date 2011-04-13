<?php

$ausgabe .= table(550, "bg");
$ausgabe .= tr(td(5, "head", "Nährstoffe pro <a href=# onclick=\"shown('nh')\">Stunde</a> / <a href=# onclick=\"shown('nt')\">Tick</a> / <a href=# onclick=\"shown('nd')\">Tag</a>"));

$ausgabe .= tr(
	td(0, "boldl", "Nährstoff")
	 . td(0, "boldc", "Förderung")
	 . td(0, "boldc", "Speicher")
	 . td(0, "boldc", "Füllstand")
	 . td(0, "boldc", "nicht plünderbar")
	);

for ($c = 1; $c <= 5; $c++) {
	$cw = $c + 1;
	$s = $inhalte_b["konst$cw"];
	$result_info = mysql_query("SELECT bezeichnung, wert1 FROM genesis_infos WHERE typ='konst$cw'");
	$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
	$wert = $inhalte_info["wert1"];
	$diff = round(300 / 3600, 6);
	if ($inhalte_s["urlaub"] > time()) {
		$ress = round(ressprod($wert, $s) / 10 / 12, 0) * 12;
		if ($c == 5) $ress -= round($wert / 10 / 12, 0) * 12;
		$ress2 = round(ressprod($wert, $s) / 10 * $diff, 0);
		if ($c == 5) $ress2 -= round($wert / 10 * $diff, 0);
	} else {
		$ress = round(ressprod($wert, $s) / 12, 0) * 12;
		if ($c == 5) $ress -= round($wert / 12, 0) * 12;
		$ress2 = round(ressprod($wert, $s) * $diff, 0);
		if ($c == 5) $ress2 -= round($wert * $diff, 0);
	}
	$ressk = resskap($inhalte_b["konst" . ($c + 8)]);
	$ressl = round($inhalte_b["ress$c"] / $ressk * 100, 0);
	$r = dechex(round(70 + $ressl * 1.8, 0));
	if (strlen($r) < 2) $r = "0$r";
	$g = dechex(round(220 - ($ressl * 1.5), 0)) . "10";

	$ausgabe .= "	<tr>\n";
	$ausgabe .= td(0, "left", num2typ($c));

	if ($ress > 0 && (($inhalte_b["verbrauch"] < $ress && $c == 5) || $c < 5)) {
		$dauer = round($ressk / $ress * 3600, 0);
		include "dauer.inc.php";
		$out = "in " . $h . ":" . $m . ":00";
	} else {
		$out = "nie";
	}
	$ausgabe .= "<td class=\"center\" id=\"nh\" name=\"nh\" title=\"Füllt Speicher $out\">" . format($ress) . "</td>\n";
	$ausgabe .= "<td class=\"center\" id=\"nt\" name=\"nt\" title=\"Füllt Speicher $out\" style=\"display:none\">" . format($ress2) . "</td>\n";
	$ausgabe .= "<td class=\"center\" id=\"nd\" name=\"nd\" title=\"Füllt Speicher $out\" style=\"display:none\">" . format($ress * 24) . "</td>\n";

	if ($ress > 0 && (($inhalte_b["verbrauch"] < $ress && $c == 5) || $c < 5)) {
		$dauer = round(($ressk - $inhalte_b["ress$c"]) / $ress * 3600, 0);
		include "dauer.inc.php";
		$out = "in " . $h . ":" . $m . ":00";
	} else {
		$out = "nie";
	}
	$ausgabe .= "<td class=\"center\" title=\"Voll $out\">" . format($ressk) . "</td>\n";
	$ausgabe .= td(0, "center", "<font color=\"#$r$g\">$ressl %</font>");
	$ausgabe .= td(0, "center", format(round($ressk / 10, 0)));
	$ausgabe .= "	</tr>\n";
	unset($ress2, $ressl, $r, $g, $diff);
}

$ausgabe .= tr(td(5, "center", "&nbsp;"));

if ($inhalte_s["urlaub"] > time()) $inhalte_b["verbrauch"] = $inhalte_b["verbrauch"] / 10;
$ressa = round($inhalte_b["verbrauch"] / ($ress + 1) * 50, 0);
if ($ressa > 100) $ressa = 100;
if ($ressa < 0) $ressa = 0;
$r = dechex(round(70 + $ressa * 1.8, 0));
if (strlen($r) < 2) $r = "0" . $r;
$g = dechex(round(220 - ($ressa * 1.5), 0)) . "10";

$ausgabe .= "\t<tr>\n";
$ausgabe .= td(0, "left", "Exo-Zellen (ATP)");

if ($inhalte_b["verbrauch"] > $ress) {
	$dauer = round($ressk / ($inhalte_b["verbrauch"] - $ress) * 3600, 0);
	include "dauer.inc.php";
	$out = "in " . $h . ":" . $m . ":00";
} else {
	$out = "nie";
}

$ausgabe .= "<td class=\"center\" id=\"nh\" name=\"nh\" title=\"Leert Speicher $out\"><font color=\"#$r$g\">" . format(round(- $inhalte_b["verbrauch"] / 12, 0) * 12) . "</font></td>\n";
$ausgabe .= "<td class=\"center\" id=\"nt\" name=\"nt\" title=\"Leert Speicher $out\" style=\"display:none\"><font color=\"#$r$g\">" . format(round(- $inhalte_b["verbrauch"] / 12, 0)) . "</font></td>\n";
$ausgabe .= "<td class=\"center\" id=\"nd\" name=\"nd\" title=\"Leert Speicher $out\" style=\"display:none\"><font color=\"#$r$g\">" . format(round(- $inhalte_b["verbrauch"] / 12, 0) * 12 * 24) . "</font></td>\n";

if ($inhalte_b["verbrauch"] > $ress) {
	$dauer = round(($inhalte_b["ress5"]) / ($inhalte_b["verbrauch"] - $ress) * 3600, 0);
	include "dauer.inc.php";
	$out = "in " . $h . ":" . $m . ":00";
} else {
	$out = "nie";
}

$ausgabe .= "<td class=\"center\" title=\"Leer $out\">-</td>\n";
$ausgabe .= "<td class=\"center\">-</td>\n";
$ausgabe .= "<td class=\"center\">-</td>\n";
$ausgabe .= "\t</tr>\n";
$ausgabe .= tr(td(5, "center", "&nbsp;"));
$ausgabe .= tr(td(5, "center", hlink("new", "wiki/index.php/Nährstoffe", "Infos über die Angaben findest du im Genesis-Wiki")));
$ausgabe .= "</table>";

unset($ress, $ress2, $ressl, $ressk, $r, $g, $diff);

?>