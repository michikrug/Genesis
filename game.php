<?php
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : NULL;
session_id($id);
session_start();

include_once "ctracker.php";

mt_srand((double)microtime()*1000000);
require_once "sicher/config.inc.php";
$connection = @mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$connection) die("Es konnte keine Datenbankverbindung hergestellt werden.");
mysql_select_db($mysql_db, $connection);

$nav = isset($_REQUEST["nav"]) ? $_REQUEST["nav"] : NULL;

if ($_SESSION["next"] < time()) {
	if (isset($_REQUEST["code_x"]) && isset($_REQUEST["code_x"])) {
		$daten = mysql_query("SELECT code FROM genesis_codes WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "'");
		$da = mysql_fetch_array($daten, MYSQL_ASSOC);
		mysql_free_result($daten);
		if ($da["code"] - 15 < $_REQUEST["code_x"] && $da["code"] + 15 > $_REQUEST["code_x"] && $_REQUEST["code_y"] > 15 && $_REQUEST["code_y"] < 60) {
			//mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('$name', '" . $_SERVER['REMOTE_ADDR'] . "', '" . time() . "', 'code richtig')");
			$_SESSION["klicks"] = mt_rand(80, 120);
			$_SESSION["next"] = time() + (mt_rand(45, 75) * 60);
		} else {
			//mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('$name', '" . $_SERVER['REMOTE_ADDR'] . "', '" . time() . "', 'logout (code falsch)')");
			unset($_SESSION["name"]);
			unset($_SESSION["sid"]);
			unset($_SESSION["ip"]);
			unset($_SESSION["klicks"]);
			unset($_SESSION["next"]);
			unset($_SESSION["delay"]);
			mysql_query("UPDATE genesis_spieler SET sessid='0' WHERE sessid='$id' OR id='$sid'");
			setcookie ("gencookie", "", 315532800);
			session_destroy();
			header("location: index.php?nav=login");
		}
	}
}

require_once "header.inc.php";

require_once "check_id.inc.php";

if ($check_id == true) {
	include_once "whoisonline.inc.php";
	require_once "functions.inc.php";

	$_SESSION["klicks"]--;

	$result_game = mysql_query("SELECT * FROM genesis_check WHERE id='1'");
	$inhalte_game = mysql_fetch_array($result_game, MYSQL_ASSOC);
	if (($inhalte_game["aktiv"] == 0 && time() - $inhalte_game["zeit"] > 4) || time() - $inhalte_game["zeit"] > 30) {
		mysql_query("UPDATE genesis_check SET zeit='" . time() . "', aktiv='1' WHERE id='1'");
		include_once "check.inc.php";
		mysql_query("UPDATE genesis_check SET zeit='" . time() . "', aktiv='0' WHERE id='1'");
	}
	if ($inhalte_game["aktiv"] == 0 && ($nav == "ausbau" || $nav == "evolution" || $nav == "produktion" || $nav == "verteidigung")) {
		include "get_data.inc.php";
		include_once $nav . "_check.inc.php";
	}

	include "get_data.inc.php";
	for ($i = 1; $i <= 5; $i++) {
		eval("\$ressk = resskap(\$inhalte_b[\"konst" . ($i + 8) . "\"]);");
		eval("\$ressl$i = round(\$inhalte_b[\"ress$i\"] / \$ressk * 100, 0);");
		eval("\$r$i = dechex(round(70+\$ressl$i*1.8,0));");
		eval("if (strlen(\$r$i) < 2) { \$r$i = \"0\". \$r$i; }");
		eval("\$g$i = dechex(round(220-(\$ressl$i*1.5),0)) .\"10\";");
	}

	$ausgabe .= "<table width=\"809\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:100%;table-layout:fixed\">\n";
	$ausgabe .= "<tr><td colspan=\"5\" valign=\"top\" height=\"102\"><img src=\"images/genesis_01.jpg\" width=\"809\" height=\"102\"></td></tr>";
	$ausgabe .= tr(
		td(0, "nav", "<font style=\"color:#$r1$g1\"> | </font>Adenin: " . format($inhalte_b["ress1"]))
		. td(0, "nav", "<font style=\"color:#$r2$g2\"> | </font>Thymin: " . format($inhalte_b["ress2"]))
		. td(0, "nav", "<font style=\"color:#$r3$g3\"> | </font>Guanin: " . format($inhalte_b["ress3"]))
		. td(0, "nav", "<font style=\"color:#$r4$g4\"> | </font>Cytosin: " . format($inhalte_b["ress4"]))
		. td(0, "nav", "<font style=\"color:#$r5$g5\"> | </font>ATP: " . format($inhalte_b["ress5"])));
	$ausgabe .= "
	<tr>
		<td colspan=\"5\" valign=\"top\">
			<table name=\"main\" id=\"main\" width=\"809\" class=\"main\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
					<td valign=\"top\" width=\"160\" align=\"right\">
						<br/>\n";
	include_once "navi.inc.php";
	$ausgabe .= "
					</td>
					<td valign=\"top\" width=\"649\" align=\"center\">
						<center>
						<br/>\n";

	if ($_SESSION["next"] < time() && ($nav == "ausbau" || $nav == "evolution" || $nav == "produktion" || $nav == "verteidigung" || $nav == "handel" || $nav == "karte")) {
		include "code.inc.php";
		$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
		$ausgabe .= table(300, "bg");
		$ausgabe .= tr(td(0, "head", "Sicherheitsabfrage"));
		$ausgabe .= tr(td(0, "center", input("image", "code", "src=\"images/codes/code" . $codezeit . ".png\" style=\"border:none\"")));
		$ausgabe .= tr(td(0, "center", $out));
		$ausgabe .= "</table>\n</form>\n<br/>";
		$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav'\", 300000)</script>\n";
	} else {
		if ($nav) {
			include_once "$nav.php";
		} else {
			include_once "uebersicht.php";
		}
	}

	$ausgabe .= "
						<br/>
						</center>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>\n";
}

include "footer.inc.php";

mysql_close($connection);

$ausgabe = str_replace("<title>Genesis</title>", "<title>Genesis :: ". ucfirst($nav) ."</title>", $ausgabe);

echo $ausgabe;

?>