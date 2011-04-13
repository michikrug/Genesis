<?php

include_once "ctracker.php";
include_once "functions.inc.php";
include_once "header.inc.php";

include_once "sicher/config.inc.php";

$conn = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db);

if ($id != "") {
	$result = mysql_query("SELECT * FROM genesis_berichte WHERE id='$id'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$result2 = mysql_query("SELECT * FROM genesis_att WHERE id='$id'");
		while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {

			// Deffer
			$result_s = mysql_query("SELECT id,name FROM genesis_spieler WHERE basis1='" . $inhalte["koords"] . "' or basis2='" . $inhalte["koords"] . "'");
			$inhalte_s = mysql_fetch_array($result_s, MYSQL_ASSOC);
			$koord = explode(":", $inhalte["koords"]);
			// Deffer Ress zurück geben
			mysql_query("UPDATE genesis_basen SET ress1=ress1+" . $inhalte2["ress1"] . ", ress2=ress2+" . $inhalte2["ress2"] . ", ress3=ress3+" . $inhalte2["ress3"] . ", ress4=ress4+" . $inhalte2["ress4"] . ", ress5=ress5+" . $inhalte2["ress5"] . " WHERE name='" . $inhalte_s["name"] . "' and koordx='" . $koord[0] . "' and koordy='" . $koord[1] . "' and koordz='" . $koord[2] . "'");

			// Atter
			$result_s = mysql_query("SELECT id,bonus FROM genesis_spieler WHERE name='" . $inhalte2["name"] . "'");
			$inhalte_s = mysql_fetch_array($result_s, MYSQL_ASSOC);
			$koord2 = explode(":", $inhalte2["koords"]);
			// Atter Ress/2 wieder abziehen
			mysql_query("UPDATE genesis_basen SET ress1=ress1-" . round($inhalte2["ress1"] / 2, 0) . ", ress2=ress2-" . round($inhalte2["ress2"] / 2, 0) . ", ress3=ress3-" . round($inhalte2["ress3"] / 2, 0) . ", ress4=ress4-" . round($inhalte2["ress4"] / 2, 0) . ", ress5=ress5-" . round($inhalte2["ress5"] / 2, 0) . " WHERE name='" . $inhalte2["name"] . "' and koordx='" . $koord2[0] . "' and koordy='" . $koord2[1] . "' and koordz='" . $koord2[2] . "'");
			// Atter Verluste zurückerstatten
			mysql_query("UPDATE genesis_basen SET prod1=prod1+" . $inhalte2["prodv1"] . ", prod2=prod2+" . $inhalte2["prodv2"] . ", prod3=prod3+" . $inhalte2["prodv3"] . ", prod4=prod4+" . $inhalte2["prodv4"] . ", prod5=prod5+" . $inhalte2["prodv5"] . ", prod6=prod6+" . $inhalte2["prodv6"] . ", prod7=prod7+" . $inhalte2["prodv7"] . " WHERE name='" . $inhalte2["name"] . "' and koordx='" . $koord2[0] . "' and koordy='" . $koord2[1] . "' and koordz='" . $koord2[2] . "'");
			// Atter Punkte abziehen
			mysql_query("UPDATE genesis_spieler SET kampfpkt=kampfpkt-" . $inhalte2["kp"] . ", bonus=" . ($inhalte_s["bonus"] - $inhalte2["bonus"]) . " WHERE name='" . $inhalte2["name"] . "'");

			mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte_s["id"] . "','" . time() . "','news','Angriff zurückgesetzt','Der Angriff auf " . $inhalte["koords"] . " wurde zum Ausgleich eines Fehlers zurückgesetzt.')");
			mysql_query("UPDATE genesis_att SET name='UNDO" . $inhalte2["name"] . "', alli='UNDO" . $inhalte2["alli"] . "', id='UNDO$id' WHERE id='$id'");
		}
		$result2 = mysql_query("SELECT * FROM genesis_deff WHERE id='$id'");
		while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$koord2 = explode(":", $inhalte2["koords"]);
			// Deffer Verluste zurückerstatten
			mysql_query("UPDATE genesis_basen SET prod1=prod1+" . $inhalte2["prodv1"] . ", prod2=prod2+" . $inhalte2["prodv2"] . ", prod3=prod3+" . $inhalte2["prodv3"] . ", prod4=prod4+" . $inhalte2["prodv4"] . ", prod5=prod5+" . $inhalte2["prodv5"] . ", prod6=prod6+" . $inhalte2["prodv6"] . ", prod7=prod7+" . $inhalte2["prodv7"] . " WHERE name='" . $inhalte2["name"] . "' and koordx='" . $koord2[0] . "' and koordy='" . $koord2[1] . "' and koordz='" . $koord2[2] . "'");

			$result_s = mysql_query("SELECT id,bonus FROM genesis_spieler WHERE name='" . $inhalte2["name"] . "'");
			$inhalte_s = mysql_fetch_array($result_s, MYSQL_ASSOC);
			// Deffer Punkte abziehen
			mysql_query("UPDATE genesis_spieler SET kampfpkt=kampfpkt-" . $inhalte2["kp"] . ", bonus=" . ($inhalte_s["bonus"] - $inhalte2["bonus"]) . " WHERE name='" . $inhalte2["name"] . "'");

			mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('0','" . $inhalte_s["id"] . "','" . time() . "','news','Angriff zurückgesetzt','Der Angriff um " . date("H:i:s (d.m.Y)", $inhalte["zeit"]) . " wurde zum Ausgleich eines Fehlers zurückgesetzt.')");
			mysql_query("UPDATE genesis_deff SET name='UNDO" . $inhalte2["name"] . "', alli='UNDO" . $inhalte2["alli"] . "', id='UNDO$id' WHERE id='$id'");
		}
		mysql_query("UPDATE genesis_berichte SET id='UNDO$id' WHERE id='$id'");
	}
}
mysql_close($conn);

$ausgabe .= "\n</table>\n";

include_once "footer.inc.php";

echo $ausgabe;

?>