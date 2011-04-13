<?php

$krx = isset($_REQUEST["krx"]) ? intval($_REQUEST["krx"]) : $bk[0];
$kry = isset($_REQUEST["kry"]) ? intval($_REQUEST["kry"]) : $bk[1];
$krz = isset($_REQUEST["krz"]) ? intval($_REQUEST["krz"]) : $bk[2];

if ($krx < 1) $krx = 1;
if ($krx > 6) $krx = 6;
if ($kry < 1) $kry = 1;
if ($kry > 6) $kry = 6;
if ($krz < 1) $krz = 1;
if ($krz > 6) $krz = 6;

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= "<table id=\"karte\" width=\"480\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bg\">"; //table(600, "bg");
$ausgabe .= tr(td(6, "head", "Karte"));

$out = table(480, "");
$out .= tr(
	td(3, "center", "Koordinaten: <input id=\"krx\" style=\"text-align:right\" size=2 maxlength=2 type=\"text\" name=\"krx\" value=\"$krx\"> <input id=\"kry\" style=\"text-align:right\" size=2 maxlength=2 type=\"text\" name=\"kry\" value=\"$kry\"> <input id=\"krz\" style=\"text-align:right\" size=2 maxlength=2 type=\"text\" name=\"krz\" value=\"$krz\">")
	   . td(0, "center", "<a href=\"#\" onclick=\"document.getElementById('krx').value=parseInt(document.getElementById('krx').value)-1;document.form.submit();\"><img src=\"images/xminus.gif\"/></a>")
	   . td(0, "center", "<a href=\"#\" onclick=\"document.getElementById('krx').value=parseInt(document.getElementById('krx').value)+1;document.form.submit();\"><img src=\"images/xplus.gif\"/></a>")
	   . td(0, "center", input("submit", "goto", "Anzeigen")));
$out .= "</table>";

$ausgabe .= tr(td(6, "center", $out));

$result_karte = mysql_query("SELECT koordx,koordy,koordz,name,bname,punkte FROM genesis_basen WHERE koordx=$krx AND koordy=$kry AND koordz=$krz");
$inhalte_karte = mysql_fetch_array($result_karte, MYSQL_ASSOC);
if ($inhalte_karte["name"] != "") {
	$result_sk = mysql_query("SELECT id,alli,punkte,urlaub FROM genesis_spieler WHERE name='" . $inhalte_karte["name"] . "'");
	$inhalte_sk = mysql_fetch_array($result_sk, MYSQL_ASSOC);
	$outa = "";
	$inhalte_ska = array();
	if ($inhalte_sk["alli"] != 0) {
		$result_ska = mysql_query("SELECT id,tag FROM genesis_allianzen WHERE id='" . $inhalte_sk["alli"] . "'");
		$inhalte_ska = mysql_fetch_array($result_ska, MYSQL_ASSOC);
		$outa = "<a href=game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte_sk["alli"] ." class=nc>[". $inhalte_ska["tag"] ."]</a> ";
		$inhalte_ska["tag"] = "[" . $inhalte_ska["tag"] . "]";
	}
	$outs = "<a href=game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte_sk["id"] . "&k=" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] ." class=nc>". $inhalte_karte["name"] . " - " . $inhalte_karte["bname"] ."</a>";
	$l1 = "<a href=game.php?id=$id&b=$b&nav=mission&mkx=" . $inhalte_karte["koordx"] . "&mky=" . $inhalte_karte["koordy"] . "&mkz=" . $inhalte_karte["koordz"] . "&me6=" . $inhalte_s["spios"] . "&aktion=Auftrag%20erteilen&mt=3 class=nc>S</a>";
	$l2 = "<a href=game.php?id=$id&b=$b&nav=mission&mkx=" . $inhalte_karte["koordx"] . "&mky=" . $inhalte_karte["koordy"] . "&mkz=" . $inhalte_karte["koordz"] . " class=nc>M</a>";
} else {
	$outa = "";
	$outs = "frei";
	$l1 = "&nbsp;";
	$l2 = "&nbsp;";
}

$out = table(480, "");
$out .= tr(td(6, "center", "<b><font id=\"kkoords\">" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] . "</font></b> <font id=\"kalli\">$outa</font> <font id=\"kname\">$outs</font> (<font id=\"kpunkte\">" . format($inhalte_karte["punkte"]) . "</font> Punkte) <b><font id=\"kl1\">" . $l1 . "</font></b> <b><font id=\"kl2\">" . $l2 . "</font></b>"));
$out .= "</table>";
unset($inhalte_karte, $inhalte_sk, $inhalte_ska);

$ausgabe .= tr(td(6, "center", $out));

$result_karte = mysql_query("SELECT koordx,koordy,koordz,name,bname,punkte FROM genesis_basen WHERE koordx=$krx AND koordy>0 AND koordy<7 AND koordz>0 AND koordz<7 order by koordy, koordz");

$zeile = "";
$oldy = "";

$result_max = mysql_query("SELECT max(punkte) as pkts FROM genesis_basen");
$inhalte_max = mysql_fetch_array($result_max, MYSQL_ASSOC);
$pf = false;

while ($inhalte_karte = mysql_fetch_array($result_karte, MYSQL_ASSOC)) {
	unset($inhalte_p, $outs, $inhalte_sk, $inhalte_ska, $out, $noob, $inhalte1, $result1, $punkte2);
	$outa = "";
	$inhalte_p = array("typ" => NULL);
	$inhalte_ska = array("tag" => NULL);
	if (!$oldy) $oldy = $inhalte_karte["koordy"];
	if ($oldy < $inhalte_karte["koordy"]) {
		$ausgabe .= tr($zeile);
		$zeile = "";
		$oldy = $inhalte_karte["koordy"];
	}
	if ($kry == $inhalte_karte["koordy"] && $krz == $inhalte_karte["koordz"]) {
		$bg = "background-color:#000000;";
	} else {
		$bg = "";
	}
	if ($inhalte_karte["name"] != "") {
		$result_sk = mysql_query("SELECT id,alli,punkte,urlaub FROM genesis_spieler WHERE name='" . $inhalte_karte["name"] . "'");
		$inhalte_sk = mysql_fetch_array($result_sk, MYSQL_ASSOC);
		if ($inhalte_sk["alli"] != 0) {
			$result_ska = mysql_query("SELECT id,tag FROM genesis_allianzen WHERE id='" . $inhalte_sk["alli"] . "'");
			$inhalte_ska = mysql_fetch_array($result_ska, MYSQL_ASSOC);
			$outa = "<a href=game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte_sk["alli"] ." class=nc>[". $inhalte_ska["tag"] ."]</a> ";
			$inhalte_ska["tag"] = "[". $inhalte_ska["tag"] ."]";
			$result_p = mysql_query("SELECT alli1,typ FROM genesis_politik WHERE ((alli1='" . $inhalte_sk["alli"] . "' and alli2='" . $inhalte_s["alli"] . "') or (alli2='" . $inhalte_sk["alli"] . "'and alli1='" . $inhalte_s["alli"] . "'))");
			$inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC);
		}
		$outs = "<a href=game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte_sk["id"] . "&k=" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] ." class=nc>". $inhalte_karte["name"] . " - " . $inhalte_karte["bname"] ."</a>";
		$noob = isnoob($sid, $inhalte_sk["id"]);
		if ($inhalte_karte["name"] == $name) {
			$cl = "White";
		} elseif (($noob == true && ($inhalte_sk["alli"] != $inhalte_s["alli"] || $inhalte_s["alli"] == 0)) || $inhalte_sk["urlaub"] > time()) {
			$cl = "#DAA520";
		} elseif ($inhalte_sk["alli"] == $inhalte_s["alli"] && $inhalte_s["alli"] != 0) {
			$cl = "#00C5C8";
		} elseif ($inhalte_p["typ"] == 5 || ($inhalte_p["typ"] == 3 && $inhalte_p["alli1"] == $inhalte_s["alli"])) {
			$cl = "Red";
		} elseif ($inhalte_p["typ"] == 7 || ($inhalte_p["typ"] == 1 && $inhalte_p["alli1"] == $inhalte_s["alli"])) {
			$cl = "Lime";
		} else {
			$cl = "#93A2B6";
		}
		$size = round((68 / ($inhalte_max["pkts"]+1)) * $inhalte_karte["punkte"]) + 12;
		$l1 = "<a href=game.php?id=$id&b=$b&nav=mission&mkx=" . $inhalte_karte["koordx"] . "&mky=" . $inhalte_karte["koordy"] . "&mkz=" . $inhalte_karte["koordz"] . "&me6=" . $inhalte_s["spios"] . "&aktion=Auftrag%20erteilen&mt=3 class=nc>S</a>";
		$l2 = "<a href=game.php?id=$id&b=$b&nav=mission&mkx=" . $inhalte_karte["koordx"] . "&mky=" . $inhalte_karte["koordy"] . "&mkz=" . $inhalte_karte["koordz"] . " class=nc>M</a>";
		$zeile .= "		<td name=\"zelle\" id=\"" . $inhalte_karte["koordy"] . "k" . $inhalte_karte["koordz"] . "\" class=\"center\" style=\"vertical-align:middle;width:80px;height:80px;border:1px dashed $cl;padding:0px;$bg\" onMouseOver=\"uncolor('" . $inhalte_karte["koordy"] . "k" . $inhalte_karte["koordz"] . "');getElementById('krx').value='" . $inhalte_karte["koordx"] . "';getElementById('kry').value='" . $inhalte_karte["koordy"] . "';getElementById('krz').value='" . $inhalte_karte["koordz"] . "';getElementById('kname').innerHTML='" . str_replace("'", "\'", $outs) . "';getElementById('kpunkte').innerHTML='" . format($inhalte_karte["punkte"]) . "';getElementById('kalli').innerHTML='$outa';getElementById('kl1').innerHTML='$l1';getElementById('kl2').innerHTML='$l2';getElementById('kkoords').innerHTML='" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] . "';\"><a href=\"game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte_sk["id"] . "&k=" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] ."\"><img alt=\"" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] . " - " . $inhalte_ska["tag"] . " " . $inhalte_karte["name"] . " - " . $inhalte_karte["bname"] . " (" . format($inhalte_karte["punkte"]) . " Punkte)\" title=\"" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] . " - " . $inhalte_ska["tag"] . " " . $inhalte_karte["name"] . " - " . $inhalte_karte["bname"] . " (" . format($inhalte_karte["punkte"]) . " Punkte)\" src=\"images/cell.gif\" width=\"$size\" height=\"$size\"/></a></td>\n";
	} else {
		$zeile .= "		<td name=\"zelle\" id=\"zelle" . $inhalte_karte["koordy"] . "k" . $inhalte_karte["koordz"] . "\" class=\"center\" style=\"vertical-align:middle;width:80px;height:80px;border:1px hidden #556177;padding:0px;$bg\">&nbsp;</td>\n";
	}
}
unset($inhalte_p, $outa, $outs, $inhalte_sk, $inhalte_ska, $out, $noob, $inhalte1, $result1, $punkte2);
$ausgabe .= tr($zeile);
$ausgabe .= "</table>\n";
//<img alt=\"" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] . " - " . $inhalte_ska["tag"] . " " . $inhalte_karte["name"] . " - " . $inhalte_karte["bname"] . " (" . format($inhalte_karte["punkte"]) . " Punkte)\" title=\"" . $inhalte_karte["koordx"] . ":" . $inhalte_karte["koordy"] . ":" . $inhalte_karte["koordz"] . " - " . $inhalte_ska["tag"] . " " . $inhalte_karte["name"] . " - " . $inhalte_karte["bname"] . " (" . format($inhalte_karte["punkte"]) . " Punkte)\" src=\"images/cell.gif\" width=\"$size\" height=\"$size\" style=\"width:". $size ."px;height:". $size ."px;vertical-align:middle;\"/>
?>