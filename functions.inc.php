<?php

define("SPEEDUP", 8);

function getpunkte($typ, $stufe, $var)
{
	$result_info = mysql_query("SELECT ress1,ress2,ress3,ress4 FROM genesis_infos WHERE typ='" . $typ . $var . "'");
	$inhalte_info = mysql_fetch_array($result_info, MYSQL_ASSOC);
	if ($typ == "konst") {
		$pkt = kostaus($inhalte_info["ress1"], $stufe) / 1000;
		$pkt += kostaus($inhalte_info["ress2"], $stufe) / 1000;
		$pkt += kostaus($inhalte_info["ress3"], $stufe) / 1000;
		$pkt += kostaus($inhalte_info["ress4"], $stufe) / 1000;
	} elseif ($typ == "forsch") {
		$pkt = kostevo($inhalte_info["ress1"], $stufe) / 1000;
		$pkt += kostevo($inhalte_info["ress2"], $stufe) / 1000;
		$pkt += kostevo($inhalte_info["ress3"], $stufe) / 1000;
		$pkt += kostevo($inhalte_info["ress4"], $stufe) / 1000;
	}
	$pkt = round($pkt, 0);
	if ($pkt == 0) $pkt = 1;
	return $pkt;
}

function isnoob($spieler1, $spieler2)
{
	$result_noob1 = mysql_query("SELECT log,alli,punkte,punktem,urlaub FROM genesis_spieler WHERE id='$spieler1'");
	$inhalte_noob1 = mysql_fetch_array($result_noob1, MYSQL_ASSOC);
	$result_noob2 = mysql_query("SELECT log,alli,punkte,punktem,urlaub,attcount FROM genesis_spieler WHERE id='$spieler2'");
	$inhalte_noob2 = mysql_fetch_array($result_noob2, MYSQL_ASSOC);
	$result_noob3 = mysql_query("SELECT ROUND(sum(punktem) / count(*),0) FROM genesis_spieler");
	$inhalte_noob3 = mysql_fetch_array($result_noob3, MYSQL_NUM);
	$result_noob4 = mysql_query("SELECT id FROM genesis_politik WHERE typ='5' and ((alli1='" . $inhalte_noob1["alli"] . "' and alli2='" . $inhalte_noob2["alli"] . "') or (alli1='" . $inhalte_noob2["alli"] . "' and alli2='" . $inhalte_noob1["alli"] . "'))");
	if (mysql_fetch_array($result_noob4, MYSQL_ASSOC)) {
		$krieg = true;
	} else {
		$krieg = false;
	}
	mysql_free_result($result_noob1);
	mysql_free_result($result_noob2);
	mysql_free_result($result_noob3);
	mysql_free_result($result_noob4);
	unset($result_noob1, $result_noob2, $result_noob3, $result_noob4);

	if ($inhalte_noob2["urlaub"] > $inhalte_noob2["log"]) $inhalte_noob2["log"] = $inhalte_noob2["urlaub"];

	if ($krieg || ($inhalte_noob1["punktem"] == 0 && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob2["punktem"] > ($inhalte_noob3[0] + $inhalte_noob2["attcount"]) && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob1["punkte"] <= $inhalte_noob2["punkte"] && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob2["punktem"] / ($inhalte_noob1["punktem"] + 1) > 0.1 && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob2["log"] < (time() - 3600 * 24 * 7))) {
// if ($krieg || ($inhalte_noob1["punktem"] == 0 && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob2["punktem"] > $inhalte_noob3[0] && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob1["punkte"] <= $inhalte_noob2["punkte"] && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob2["punktem"] / ($inhalte_noob1["punktem"]+1) > 0.1 && $inhalte_noob2["punkte"] > 400) || ($inhalte_noob2["log"] < (time() - 3600 * 24 * 7))) {
		return false;
	} else {
		return true;
	}
}

function islocked($spieler1, $spieler2)
{
	$result_lock = mysql_query("SELECT * FROM genesis_sitter WHERE (spieler1='$spieler1' and spieler2='$spieler2') OR (spieler2='$spieler1' and spieler1='$spieler2')");
	if (mysql_fetch_array($result_lock, MYSQL_ASSOC)) {
		mysql_free_result($result_lock);
		unset($result_lock);
		return true;
	} else {
		mysql_free_result($result_lock);
		unset($result_lock);
		return false;
	}
	return false;
}

function verbrauch($grundw)
{
	return round($grundw / 2, 0) / SPEEDUP * 2;
}

function maxstufe($bausst)
{
	return round(pow($bausst + 6, 2) / 9 + 11, 0);
}

function maxvert($immu)
{
	return round(pow(($immu + 3) * 2, 2) / 50, 0) * 20 + 0;
}

function missanz($stufe)
{
	return round(pow($stufe, 1.1) + 1, 0);
}

function ladekap($grundw, $vesi)
{
	return round($grundw * (sqrt($vesi + 1) / 5 + 1), 0);
}

function geschw($grundw, $loko)
{
	return round(round($grundw * (sqrt($loko + 1) / 5 + 1), 0) * SPEEDUP);
}

function angr($typ, $grundw, $mut, $det)
{
	if ($typ == "prod5") {
		return round($grundw * ((0.075 * $det + 1) + (0.075 * $mut + 1)), 0);
	} else {
		return round($grundw * (0.075 * $mut + 1), 0);
	}
}

function vert($typ, $grundw, $immu, $det)
{
	if ($typ == "prod5") {
		return round($grundw * ((0.075 * $det + 1) + (0.075 * $immu + 1)), 0);
	} else {
		return round($grundw * (0.075 * $immu + 1), 0);
	}
}

function angr_immu($stufe1, $stufe2)
{
	return round((pow(intval($stufe2), 1.85) * 200) * (0.075 * $stufe1 + 1) / 9, 0) * 10 + 300;
}

function vert_immu($stufe1, $stufe2)
{
	return round((pow(intval($stufe2), 1.85) * 400) * (0.075 * $stufe1 + 1) / 9, 0) * 10 + 600;
}

function ressprod($grundw, $stufe)
{
	return round((round($grundw * pow($stufe, 2) / 2, 0) + $grundw) * SPEEDUP);
}

function resskap($stufe)
{
	return round(pow(($stufe + 1) * 2, 3) * 0.08, 0) * 1000 + 50000;
}

function dauervert($grundw, $stufe)
{
	return round((round($grundw * 2 / pow($stufe + 2, 1.65), 2) * 600 + 10) / SPEEDUP) + 5;
}

function dauerprod($grundw, $stufe)
{
	return round((round($grundw * 2 / pow($stufe + 2, 1.7), 2) * 600 + 10) / SPEEDUP) + 5;
}

function daueraus($grundw, $stufe, $stufe1)
{
	return round((round((pow($grundw * pow($stufe + 1, 1.5), 2)) / pow(($stufe1 + 2) * 5, 2), 0) * 50 + 120) / SPEEDUP) + 60;
}

function dauerevo($grundw, $stufe, $stufe1)
{
	return round((round((pow($grundw * ($stufe + 1) / 4, 1.9)) / pow(($stufe1 + 1), 2.1), 0) * 60 + 120) / SPEEDUP) + 60;
}

function kostaus($grundw, $stufe)
{
	return round(pow($grundw * pow($stufe + 1, 1.25) / 45, 1.8) / 10, 0) * 10;
}

function kostevo($grundw, $stufe)
{
	return round(pow($grundw * ($stufe + 1) / 100, 1.79) / 14, 0) * 40;
}

function id2name($id)
{
	$result = mysql_query("SELECT name FROM genesis_spieler WHERE id='$id'");
	$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
	return $inhalte["name"];
}

function hlink($cla, $href, $inh)
{
	$new = "";
	if ($cla == "new") {
		$new = " target=_blank";
		$cla = "";
	}
	return "<a href=\"$href\" class=\"$cla\"$new>$inh</a>";
}

function input($typ, $nam, $val)
{
	if ($typ == "image") {
		return "<input type=\"$typ\" name=\"$nam\" $val>";
	} elseif ($typ == "koord") {
		return "<input style=\"text-align:right\" size=\"2\" maxlength=\"2\" type=\"text\" name=\"$nam\" value=\"$val\">";
	} elseif ($typ == "mkd") {
		return "<input style=\"text-align:right\" size=\"2\" maxlength=\"2\" type=\"text\" name=\"$nam\" value=\"$val\" onkeyup=\"daucalc()\">";
	} elseif ($typ == "zahl") {
		return "<input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"$nam\" value=\"$val\">";
	} elseif ($typ == "radioc") {
		return "<input style=\"border:none\" type=\"radio\" name=\"$nam\" value=\"$val\" checked=\"checked\">";
	} elseif ($typ == "radio") {
		return "<input style=\"border:none\" type=\"radio\" name=\"$nam\" value=\"$val\">";
	} elseif ($typ == "checkbox") {
		return "<input style=\"border:none\" type=\"checkbox\" name=\"$nam\" value=\"$val\">";
	} elseif ($typ == "checkedbox") {
		return "<input style=\"border:none\" type=\"checkbox\" name=\"$nam\" value=\"$val\" checked=\"checked\">";
	} elseif ($typ == "shout") {
		return "<input onFocus=\"this.value=''\" type=\"text\" name=\"$nam\" value=\"$val\" maxlength=\"50\">";
	} elseif ($typ == "shoutl") {
		return "<input readonly type=\"text\" name=\"$nam\" value=\"$val\">";
	} elseif ($typ == "symbtext") {
		return "<input type=\"text\" name=\"$nam\" value=\"$val\" size=\"50\">";
	} elseif ($typ == "readonly") {
		return "<input type=\"text\" name=\"$nam\" value=\"$val\" readonly=\"readonly\">";
	} elseif ($typ == "back") {
		return "<input type=\"button\" name=\"$nam\" value=\"$val\" onClick=\"history.back();\">";
	} else {
		return "<input type=\"$typ\" name=\"$nam\" value=\"$val\" size=\"20\">";
	}
}

function form($act)
{
	if (strpos($act, "einstellungen") > 0 || strpos($act, "symbiose") > 0 || strpos($act, "symb_admin") > 0) {
		return "<form name=\"form\" action=\"$act\" method=\"post\" enctype=\"multipart/form-data\">\n";
	} else {
		return "<form name=\"form\" action=\"$act\" method=\"post\">\n";
	}
}

function table($wid, $cla)
{
	return "<table width=\"$wid\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"$cla\">\n";
}

function tr($inh)
{
	return "	<tr>
$inh	</tr>\n";
}

function td($col, $cla, $inh)
{
	if ($col > 1) {
		$col = " colspan=\"$col\"";
	} else {
		$col = "";
	}
	if ($cla) {
		$cla = " class=\"$cla\"";
	} else {
		$cla = "";
	}
	return "		<td valign=\"top\"$cla$col>$inh</td>\n";
}

function num2typ($vari)
{
	if ($vari == 1) {
		return "Adenin";
	}
	if ($vari == 2) {
		return "Thymin";
	}
	if ($vari == 3) {
		return "Guanin";
	}
	if ($vari == 4) {
		return "Cytosin";
	}
	if ($vari == 5) {
		return "ATP";
	}
}

function sauber($vari)
{
	return mysql_real_escape_string(strip_tags($vari));
}

function format($zahl)
{
	return number_format($zahl, 0, "", ".");
}

function strgen($n)
{
	mt_srand((double)microtime() * 1000000);
	$str = "";
	for($i = 1;$i <= $n;$i++) {
		$z = rand(1, 3);
		if ($z == 1) {
			$zufall = chr(mt_rand(0, 25) + ord("A"));
		}
		if ($z == 2) {
			$zufall = chr(mt_rand(0, 25) + ord("a"));
		}
		if ($z == 3) {
			$zufall = chr(mt_rand(0, 9) + ord("0"));
		}
		$str .= $zufall;
	}
	return $str;
}

function check_koord($koords)
{
	$koordsneu = $koords;
	$koord = explode(":", $koords);
	$koords1 = $koord[0];
	$koords2 = $koord[1];
	$koords3 = $koord[2];
	$resultf = mysql_query("SELECT name FROM genesis_basen WHERE koordx='$koords1' AND koordy='$koords2' AND koordz='$koords3'");
	$inhaltef = mysql_fetch_array($resultf, MYSQL_ASSOC);
	if ($inhaltef["name"] != "") $koordsneu = "";
	return $koordsneu;
}

?>