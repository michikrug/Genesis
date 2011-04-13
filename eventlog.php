<?php

function repl_act($aktion) {
	global $namen;
	$new = $aktion;
	$new = str_replace("login", "LOGIN", $new);
	$new = str_replace("login ()", "LOGIN", $new);
	$new = str_replace("login (1)", "LOGIN: Cookie neu gesetzt", $new);
	$new = str_replace("logout", "LOGOUT", $new);
	if (strpos($aktion, "login ip") !== false) {
		$new = "LOGIN: gleiche IP wie";
		$new .= str_replace("login ip", "", $aktion);
	}
	if (strpos($aktion, "konst") !== false) {
		$t = explode(" ", $aktion);
		$new = $t[0]. " Ausbau von ";
		$new .= $namen["konst". $t[2]]. " auf Stufe ". $t[4];
	}
	if (strpos($aktion, "forsch") !== false) {
		$t = explode(" ", $aktion);
		$new = $t[0]. " Evolution von ";
		$new .= $namen["forsch". $t[2]]. " auf Stufe ". $t[4];
	}
	if (strpos($aktion, "prod") !== false) {
		$t = explode(" ", $aktion);
		$new = $t[0]. " Produktion von ". $t[4] ." ";
		$new .= $namen["prod". $t[2]];
	}
	if (strpos($aktion, "vert") !== false) {
		$t = explode(" ", $aktion);
		$new = $t[0]. " Entwicklung von ". $t[4] ." ";
		$new .= $namen["vert". $t[2]];
	}
	if (strpos($aktion, "miss") !== false) {
		$t = explode(" ", $aktion);
		$new = $t[0] ." ";
		if ($t[2] == 1) {
			if ($t[12] == 1) {
				$new .= "gemeinsamer ";
			}
			$new .= "Angriff";
		} elseif ($t[2] == 2) {
			if ($t[12] == 1) {
				$new .= "Stationierung";
			} else {
				$new .= "Transport";
			}
		} elseif ($t[2] == 3) {
			$new .= "Spionage";
		} elseif ($t[2] == 4) {
			$new .= "Verteidigung";
		} elseif ($t[2] == 5) {
			$new .= "Rückkehr";
		} elseif ($t[2] == 6) {
			$new .= "Zellteilung";
		}
		$new .= " von ". $t[4] ." nach " . $t[6];
		if ($t[0] == "ENDE:" && $t[2] == 1) {
			$new .= " - ". hlink("new", "http://speed.vbfreak.de/bericht.php?id=". $t[8], "Bericht");
		}
	}
	$new = str_replace("()", "", $new);
	$new = str_replace("(1)", "", $new);
	return $new;
}

$result2 = mysql_query("SELECT typ, bezeichnung FROM genesis_infos");
while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
	$namen[$inhalte2["typ"]] = $inhalte2["bezeichnung"];
}

unset($f1, $f2, $f3, $f4, $f0);
if ($f <= 0) {
	$f0 = " selected";
	$fil = "";
} elseif ($f == 1) {
	$f1 = " selected";
	$fil = " and aktion like '%konst%'";
} elseif ($f == 2) {
	$f2 = " selected";
	$fil = " and aktion like '%forsch%'";
} elseif ($f == 3) {
	$f3 = " selected";
	$fil = " and aktion like '%prod%'";
} elseif ($f == 4) {
	$f4 = " selected";
	$fil = " and aktion like '%vert%'";
}elseif ($f == 5) {
	$f5 = " selected";
	$fil = " and aktion like '%miss%'";
}
$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= table(600, "bg");
$ausgabe .= tr(td(3, "head", "Eventlog der letzten 3 Tage"));
$ausgabe .= tr(td(3, "center", "<select name=f><option value=0$f0>Alle</option><option value=1$f1>Ausbau</option><option value=2$f2>Evolution</option><option value=3$f3>Produktion</option><option value=4$f4>Verteidigung</option><option value=5$f5>Mission</option></select>&nbsp;&nbsp;&nbsp;" . input("submit", "aktion", "Filter anwenden")));
$ausgabe .= tr(td(0, "head", "Zeit") . td(0, "head", "Aktion") . td(0, "head", "IP"));
$result = mysql_query("SELECT * FROM genesis_log WHERE name='mic' and zeit>'". (time()-(86400*3)) ."' and zeit<'". time() ."'$fil order by zeit desc");
while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	 $ausgabe .= tr(td(0, "center", date("H:i:s (d.m.Y)", $inhalte["zeit"])) . td(0, "left", repl_act($inhalte["aktion"])) . td(0, "right", $inhalte["ip"]));
}
mysql_free_result($result);

$ausgabe .= "</table>\n</form>\n";

?>