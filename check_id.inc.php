<?php

$check_id = false;

$name = $_SESSION["name"];
$sid = $_SESSION["sid"];
$ip = $_SESSION["ip"];

$fid = 0;

if (time() > 1230134400) {
	if (isset($_COOKIE['gencookie'])) {
		if ($_COOKIE['gencookie'] == $name && $sid != "") {
			$resultci = mysql_query("SELECT name,sessid,gesperrt FROM genesis_spieler WHERE id='$sid'");
			$inhalteci = mysql_fetch_array($resultci, MYSQL_ASSOC);
			if ($inhalteci) {
				if ($inhalteci["sessid"] == $id) {
					if ($inhalteci["gesperrt"] < time()) {
						if ($ip == $_SERVER['REMOTE_ADDR']) {
							$check_id = true;
						} else {
							$fid = "2<br>IP geändert";
						}
					} else {
						$fid = "3<br>Spieler gesperrt";
					}
				} else {
					$fid = "6<br>Session-ID geändert";
				}
			} else {
				$fid = "4<br>Spieler nicht vorhanden";
			}
		} else {
			$fid = "7<br>Session abgelaufen / Cookie nicht gefunden";
		}
	} else {
		$fid = "8<br>Session abgelaufen / Cookie nicht gefunden";
	}
/*
} else {
	$fid = "9<br>Serverwartung. Bitte ein paar Minuten gedulden.";
}*/
} else {
	$fid = "10<br>Runde beendet.";
}

if ($check_id == false) {
	$_SESSION["name"] = "";
	$_SESSION["sid"] = "";
	$_SESSION["ip"] = "";
	mysql_query("UPDATE genesis_spieler SET sessid='0' WHERE sessid='$id'");
	unset($name, $sid, $loginzeit, $ip, $id);
	setcookie("gencookie", "", 315532800);
	if (session_name() != '') {
		session_destroy();
	}
	$ausgabe .= "<br>Fehler!<br>
FEHLER-ID: $fid<br><br>
<a href=index.php?nav=login>Zum Login</a>";
}

unset($resultci, $inhalteci, $resultna, $inhaltena);

?>