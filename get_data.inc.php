<?php

$b = isset($_REQUEST["b"]) ? intval($_REQUEST["b"]) : 1;
$result_s = mysql_query("SELECT * FROM genesis_spieler WHERE id='$sid'");
if ($inhalte_s = mysql_fetch_array($result_s, MYSQL_ASSOC)) {
	if (!$b || $b < 1) $b = 1;
	if ($b > 2) $b = 2;
	$bk = explode(":", $inhalte_s["basis$b"]);
	$result_b = mysql_query("SELECT * FROM genesis_basen WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
	if (!$inhalte_b = mysql_fetch_array($result_b, MYSQL_ASSOC)) echo "Nicht dein Neogen!<br>\n";
	if ($b == 1 && $inhalte_s["basis2"]) {
		$bk2 = explode(":", $inhalte_s["basis2"]);
		$result_b2 = mysql_query("SELECT konst16 FROM genesis_basen WHERE name='$name' and koordx='" . $bk2[0] . "' and koordy='" . $bk2[1] . "' and koordz='" . $bk2[2] . "'");
		$inhalte_b2 = mysql_fetch_array($result_b2, MYSQL_ASSOC);
	} elseif ($b == 2 && $inhalte_s["basis1"]) {
		$bk2 = explode(":", $inhalte_s["basis1"]);
		$result_b2 = mysql_query("SELECT konst16 FROM genesis_basen WHERE name='$name' and koordx='" . $bk2[0] . "' and koordy='" . $bk2[1] . "' and koordz='" . $bk2[2] . "'");
		$inhalte_b2 = mysql_fetch_array($result_b2, MYSQL_ASSOC);
	}
	$inhalte_b2["konst16"] = $inhalte_b["konst16"];
}

?>