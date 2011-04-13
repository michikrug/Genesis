<?php

$ausgabe .= table(150, "bg");
$ausgabe .= tr(td(0, "head", "<a class=nc href=# onClick=\"showhide('neogen')\">Neogen</a>"));
$ausgabe .= "	<tr name=neogen id=neogen>\n";

$neoout = hlink("", "game.php?id=$id&b=$b&nav=uebersicht", "&Uuml;bersicht") . "<br/><br/>\n";
$neoout .= hlink("", "game.php?id=$id&b=$b&nav=ausbau", "Ausbau") . "<br/><br/>\n";
if ($inhalte_s["basis2"] == "" || $b == 1) {
	$neoout .= hlink("", "game.php?id=$id&b=$b&nav=evolution", "Evolution") . "<br/><br/>\n";
}
$neoout .= hlink("", "game.php?id=$id&b=$b&nav=produktion", "Produktion") . "<br/><br/>\n";
$neoout .= hlink("", "game.php?id=$id&b=$b&nav=verteidigung", "Verteidigung") . "<br/><br/>\n";
$neoout .= hlink("", "game.php?id=$id&b=$b&nav=mission", "Mission") . "<br/><br/>\n";
if ($inhalte_s["basis2"] == "" || $b == 1) {
	$neoout .= hlink("", "game.php?id=$id&b=$b&nav=handel", "Handel") . "<br/><br/>\n";
}
$neoout .= hlink("", "game.php?id=$id&b=$b&nav=naehrstoffe", "N&auml;hrstoffe") . "<br/><br/>\n";
$neoout .= hlink("", "game.php?id=$id&b=$b&nav=karte", "Karte") . "<br/>\n";
if ($inhalte_s["basis2"] != "" && $b == 1) {
	$neoout .= "<br/>\n" . hlink("", "game.php?id=$id&b=2&nav=$nav", "Wechseln") . "<br/>\n";
}
if ($inhalte_s["basis2"] != "" && $b == 2) {
	$neoout .= "<br/>\n" . hlink("", "game.php?id=$id&b=1&nav=$nav", "Wechseln") . "<br/>\n";
}
$ausgabe .= td(0, "navi", $neoout);
$ausgabe .= "</tr>\n</table>\n<br/>\n";
unset($neoout);
$ausgabe .= table(150, "bg");
$ausgabe .= tr(td(0, "head", "<a class=nc href=# onClick=\"showhide('spieler')\">Spieler</a>"));
$ausgabe .= "	<tr name=spieler id=spieler>\n";
$ausgabe .= td(0, "navi",
	hlink("", "game.php?id=$id&b=$b&nav=symbiose", "Symbiose") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=nachrichten", "Nachrichten") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=topscores", "Top-Scores") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=simulator", "Simulator") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=techtree", "Tech-Tree") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=persstat", "Pers. Statistik") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=einstellungen", "Einstellungen") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=nachrichten&w=new&empf=Administrator&betreff=Bug%20entdeckt", "Bug-Bericht") . "<br/><br/>\n" .
	hlink("", "game.php?id=$id&b=$b&nav=logout", "Logout") . "<br/>\n");

$ausgabe .= "</tr>\n</table>\n";

?>