<?php

$passwort_alt = isset($_REQUEST["passwort_alt"]) ? $_REQUEST["passwort_alt"] : NULL;
$passwort_neu1 = isset($_REQUEST["passwort_neu1"]) ? $_REQUEST["passwort_neu1"] : NULL;
$passwort_neu2 = isset($_REQUEST["passwort_neu2"]) ? $_REQUEST["passwort_neu2"] : NULL;
$bnameneu = isset($_REQUEST["bnameneu"]) ? $_REQUEST["bnameneu"] : NULL;
$bnamekill = isset($_REQUEST["bnamekill"]) ? $_REQUEST["bnamekill"] : NULL;
$profil = isset($_REQUEST["profil"]) ? $_REQUEST["profil"] : NULL;
$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
$styleneu = isset($_REQUEST["styleneu"]) ? $_REQUEST["styleneu"] : NULL;
$avatar = isset($_REQUEST["avatar"]) ? $_REQUEST["avatar"] : NULL;
$delava = isset($_REQUEST["delava"]) ? $_REQUEST["delava"] : NULL;
$endmsg = isset($_REQUEST["endmsg"]) ? $_REQUEST["endmsg"] : NULL;
$missmsg = isset($_REQUEST["missmsg"]) ? $_REQUEST["missmsg"] : NULL;
$shownew = isset($_REQUEST["shownew"]) ? $_REQUEST["shownew"] : NULL;
$layout = isset($_REQUEST["layout"]) ? $_REQUEST["layout"] : NULL;
$special = isset($_REQUEST["special"]) ? $_REQUEST["special"] : NULL;
$showava = isset($_REQUEST["showava"]) ? $_REQUEST["showava"] : NULL;
$anzspios = isset($_REQUEST["anzspios"]) ? $_REQUEST["anzspios"] : NULL;

$fehler = "";

if ($passwort_alt != "" && $passwort_neu1 != "" && $passwort_neu2 != "") {
	if (md5($passwort_alt) == $inhalte_s["passwort"] && $passwort_neu1 == $passwort_neu2) {
		$passwort_neu = md5($passwort_neu1);
		mysql_query("UPDATE genesis_spieler SET passwort='$passwort_neu' WHERE id='$sid'");

		$message = "Das Passwort für den Account " . $inhalte_s["login"] . " wurde geändert!
IP-Adresse zur Zeit der Änderung: " . $_SERVER['REMOTE_ADDR'] . "

http://genesis.vbfreak.de";
		$header = "From: Genesis <vbfreak@freakmail.de>\n";
		$header .= "Date: " . date("D, d M Y H:i:s") . " UT\n";
		$header .= "Reply-To: VBFrEaK <vbfreak@freakmail.de>\n";
		$header .= "X-Mailer: PHP/" . phpversion() . "\n";
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: text/plain; charset=iso-8859-1\n";
		$mail_gesendet = mail($inhalte_s["email"], "Passwortänderung im Account " . $inhalte_s["login"], preg_replace("#(?<!\r)\n#s", "\n", $message), $header);

		$fehler .= "<p color=\"lime\">Passwort erfolgreich geändert!</p><br/>";
	} else {
		$fehler .= "<p color=\"red\">Fehler! Bitte überprüfe deine Angaben.</p><br/>";
	}
}
if ($bnameneu != "") {
	$bnameneu = sauber($bnameneu);
	mysql_query("UPDATE genesis_basen SET bname='$bnameneu' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
}

if ($bnamekill != "" && $aktion == "Zerstören" && $b == 2) {
	if ($bnamekill == $inhalte_b["bname"]) {
		mysql_query("UPDATE genesis_basen SET name='', bname='', ress1='0', ress2='0', ress3='0', ress4='0', ress5='0', konst1='0', konst2='0', konst3='0', konst4='0', konst5='0', konst6='0', konst7='0', konst8='0', konst9='0', konst10='0', konst11='0', konst12='0', konst13='0', konst14='0', konst15='0', konst16='0', konst17='0', prod1='0', prod2='0', prod3='0', prod4='0', prod5='0', prod6='0', prod7='0', prod8='0', vert1='0', vert2='0', vert3='0', punkte='0', resszeit='0', verbrauch='0', typ='0', bonus='0', bild='1' WHERE name='$name' and bname='$bnamekill' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
		mysql_query("UPDATE genesis_spieler SET basis2=NULL, keimzelle='0' WHERE id='$sid'");
		$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(window.location.href='game.php?id=$id&b=1&nav=$nav&aktion=deleted',1000)</script>\n";
	}
}

if ($aktion == "deleted") {
	$fehler .= "<p color=\"lime\">Das Neogen wurde zerstört.</p><br/>";
}

if ($aktion == "Ändern") {
	if ($endmsg != $inhalte_s["endmsg"]) mysql_query("UPDATE genesis_spieler SET endmsg='$endmsg' WHERE id='$sid'");
	if ($missmsg != $inhalte_s["missmsg"]) mysql_query("UPDATE genesis_spieler SET missmsg='$missmsg' WHERE id='$sid'");
	if ($shownew != $inhalte_s["shownew"]) mysql_query("UPDATE genesis_spieler SET shownew='$shownew' WHERE id='$sid'");
	if ($layout != $inhalte_s["layout"]) mysql_query("UPDATE genesis_spieler SET layout='$layout' WHERE id='$sid'");
	if ($special != $inhalte_s["special"]) mysql_query("UPDATE genesis_spieler SET special='$special' WHERE id='$sid'");
	if ($showava != $inhalte_s["showava"]) mysql_query("UPDATE genesis_spieler SET showava='$showava' WHERE id='$sid'");
	if ($anzspios != $inhalte_s["spios"] && $anzspios > 0) mysql_query("UPDATE genesis_spieler SET spios='". intval($anzspios) ."' WHERE id='$sid'");
}

if ($umode == 1 && $urlaub >= 3 && $urlaub <= 20 && $inhalte_s["urlaub"] < time()) {
	if ($inhalte_s["urlaub"] + 432000 < time()) {
		$urlaub = intval($urlaub) * 86400 + time();
		$result2 = mysql_query("SELECT id FROM genesis_aktionen WHERE (basis1='" . $inhalte_s["basis1"] . "' or basis1='" . $inhalte_s["basis2"] . "') and typ='miss'");
		if (!mysql_fetch_array($result2, MYSQL_ASSOC)) {
			mysql_query("UPDATE genesis_spieler SET urlaub='$urlaub' WHERE id='$sid'");
			$fehler .= "<p color=\"lime\">Urlaubsmodus wurde aktiviert.</p><br/>";
		} else {
			$fehler .= "<p color=\"red\">Solange Missionen unterwegs sind kann der Umode nicht aktiviert werden.</p><br/>";
		}
	} else {
		$fehler .= "<p color=\"red\">Du kannst den Urlaubsmodus erst 5 Tage nach dem letzten wieder aktivieren.</p><br/>";
	}
}

if (($profil != "" || $profil != $inhalte_s["profil"]) && $aktion == "Speichern") {
	$profil = sauber($profil);
	mysql_query("UPDATE genesis_spieler SET profil='$profil' WHERE id='$sid'");
}

if ($styleneu != "" && $styleneu != $inhalte_s["style"] && $aktion == "Übernehmen") {
	$styleneu = sauber($styleneu);
	$styleneu = str_replace(chr(92), "/", $styleneu);
	if (substr($styleneu, strlen($styleneu)-1, 1) != "/") $styleneu .= "/";
	if (substr($styleneu, 0, 7) != "file://" && substr($styleneu, 0, 7) != "http://") $styleneu = "file://" . $styleneu;
	$_SESSION["style"] = $styleneu;
	mysql_query("UPDATE genesis_spieler SET style='$styleneu' WHERE id='$sid'");
} elseif ($styleneu == "" && $aktion == "Übernehmen") {
	$_SESSION["style"] = $styleneu;
	mysql_query("UPDATE genesis_spieler SET style='$styleneu' WHERE id='$sid'");
}

if ($avatar != "") {
	$filesize = filesize($avatar) / 1024;
	if ($filesize > 100) $fehler .= "<font color=\"red\">Datei ist zu groß!</font><br/>";
	list($width, $height, $type, $attr) = getimagesize($avatar);
	if ($width > 200 || $height > 200 || ($type != 1 && $type != 2 && $type != 3)) $fehler .= "<font color=\"red\">Datei hat ungültiges Format!</font><br/>";
	if ($fehler == "") {
		$ext = substr($_FILES['avatar']['type'], strrpos($_FILES['avatar']['type'], "/") + 1);
		if ($ext == "pjpeg") $ext = "jpg";
		if ($ext == "x-png") $ext = "png";
		$random = time();
		if (copy($avatar, "images/avatare/$random.$ext")) {
			if ($inhalte_s["avatar"] != "") unlink($inhalte_s["avatar"]);
			mysql_query("UPDATE genesis_spieler SET avatar='images/avatare/$random.$ext' WHERE id='$sid'");
		}
	}
}
if ($delava != "") {
	if ($inhalte_s["avatar"] != "") unlink($inhalte_s["avatar"]);
	mysql_query("UPDATE genesis_spieler SET avatar='' WHERE id='$sid'");
}

if ($passwort1 != "" && $ok == 1 && $aktion == "Absenden") {
	if (md5($passwort1) == $inhalte_s["passwort"]) {
		$zeit = time();
		mysql_query("UPDATE genesis_spieler SET loesch='$zeit' WHERE id='$sid'");
		$message = "Es wurde die Löschung des Accounts " . $inhalte_s["login"] . " angefordert!
IP-Adresse zur Zeit der Anforderung: " . $_SERVER['REMOTE_ADDR'] . "

Du hast nun 7 Tage Zeit um die Anforderung mit diesem Link zurückzuziehen:
http://genesis.vbfreak.de/loeschung.php?sid=$sid&lid=$zeit&aktion=cancel

http://genesis.vbfreak.de";
		$header = "From: Genesis <vbfreak@freakmail.de>\n";
		$header .= "Date: " . date("D, d M Y H:i:s") . " UT\n";
		$header .= "Reply-To: VBFrEaK <vbfreak@freakmail.de>\n";
		$header .= "X-Mailer: PHP/" . phpversion() . "\n";
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: text/plain; charset=iso-8859-1\n";
		$mail_gesendet = mail($inhalte_s["email"], "Löschung des Accounts " . $inhalte_s["login"], preg_replace("#(?<!\r)\n#s", "\n", $message), $header);
		$fehler = "<p color=\"lime\">Accountlöschung erfolgreich angefordert.<br/>Du hast eine Email erhalten mit der du diese innerhalb von 7 Tagen zurückziehen kannst.</p>";
	} else {
		$fehler = "<p color=\"red\">Fehler! Passwort stimmt nicht überein.</p>";
	}
}

unset ($result, $inhalte);

include "get_data.inc.php";

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= table(600, "bg");
if ($fehler) $ausgabe .= tr(td(2, "center", $fehler));

$ausgabe .= tr(td(2, "head", "Neogen umbenennen (" . $inhalte_s["basis$b"] . ")"));
$ausgabe .= tr(td(0, "left", "Alter Name") . td(0, "right", $inhalte_b["bname"]));
$ausgabe .= tr(td(0, "left", "Neuer Name") . td(0, "right", input("text", "bnameneu", "")));
$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Umbenennen")));

if ($b == 2) {
	$ausgabe .= tr(td(2, "center", "&nbsp;"));
	$ausgabe .= tr(td(2, "head", "Neogen zerstören (" . $inhalte_s["basis$b"] . ")"));
	$ausgabe .= tr(td(0, "left", "Name des Neogens") . td(0, "right", input("text", "bnamekill", "")));
	$ausgabe .= tr(td(2, "center", "Auchtung! Damit zerstörst du unwiderruflich jedes Leben auf diesem Neogen."));
	$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Zerstören")));
}

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Ereignismeldungen aktivieren für"));
if ($inhalte_s["endmsg"] == 1) {
	$ausgabe .= tr(td(0, "left", "Ausbau/Evo/Prod/Vert") . td(0, "right", input("checkedbox", "endmsg", "1")));
} else {
	$ausgabe .= tr(td(0, "left", "Ausbau/Evo/Prod/Vert") . td(0, "right", input("checkbox", "endmsg", "1")));
}
if ($inhalte_s["missmsg"] == 1) {
	$ausgabe .= tr(td(0, "left", "leere Rückkehr-Mission") . td(0, "right", input("checkedbox", "missmsg", "1")));
} else {
	$ausgabe .= tr(td(0, "left", "leere Rückkehr-Mission") . td(0, "right", input("checkbox", "missmsg", "1")));
}
$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Ändern")));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Layout / Zusätzliches"));
if ($inhalte_s["layout"] == 1) {
	$ausgabe .= tr(td(0, "left", input("radioc", "layout", "1") . " Altes Layout (ohne Bilder, Kompakt)") . td(0, "right", input("radio", "layout", "0") . " Neues Layout (mit Bildern)"));
} else {
	$ausgabe .= tr(td(0, "left", input("radio", "layout", "1") . " Altes Layout (ohne Bilder, Kompakt)") . td(0, "right", input("radioc", "layout", "0") . " Neues Layout (mit Bildern)"));
}
/*if ($inhalte_s["special"] == 1) {
	$ausgabe .= tr(td(0, "left", input("radioc", "special", "1") . " Spezial (Weihnacht/Schnee)") . td(0, "right", input("radio", "special", "0") . " Normal (ohne Schnee o.Ä.)"));
} else {
	$ausgabe .= tr(td(0, "left", input("radio", "special", "1") . " Spezial (Weihnacht/Schnee)") . td(0, "right", input("radioc", "special", "0") . " Normal (ohne Schnee o.Ä.)"));
}*/
if ($inhalte_s["shownew"] == 1) {
	$ausgabe .= tr(td(0, "left", "&nbsp;&nbsp;Anzeige von neuen News/Changelog-Einträgen") . td(0, "right", input("checkedbox", "shownew", "1")));
} else {
	$ausgabe .= tr(td(0, "left", "&nbsp;&nbsp;Anzeige von neuen News/Changelog-Einträgen") . td(0, "right", input("checkbox", "shownew", "1")));
}
$out = "<select name=showava size=1><option value=0";
if ($inhalte_s["showava"] == 0) { $out .= " selected"; }
$out .= "> Neogen-Bild</option><option value=1";
if ($inhalte_s["showava"] == 1) { $out .= " selected"; }
$out .= "> Avatar</option><option value=2";
if ($inhalte_s["showava"] == 2) { $out .= " selected"; }
$out .= "> keins</option></select>";
$ausgabe .= tr(td(0, "left", "Bild in Übersicht (nur bei neuem Layout)") . td(0, "right", $out));
$ausgabe .= tr(td(0, "left", "Anzahl Spios bei Scan-Link") . td(0, "right", input("zahl", "anzspios", $inhalte_s["spios"])));
$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Ändern")));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Urlaubsmodus"));
if ($inhalte_s["urlaub"] < time()) {
	if ($inhalte_s["urlaub"] + 432000 < time()) {
		$ausgabe .= tr(td(0, "left", "Dauer") . td(0, "right", "<select type=text name=urlaub size=1>
<option value=0> <option value=3>3 Tage <option value=4>4 Tage <option value=5>5 Tage
<option value=6>6 Tage <option value=7>7 Tage <option value=8>8 Tage
<option value=9>9 Tage <option value=10>10 Tage <option value=11>11 Tage <option value=12>12 Tage
<option value=13>13 Tage <option value=14>14 Tage <option value=15>15 Tage <option value=16>16 Tage
<option value=17>17 Tage <option value=18>18 Tage <option value=19>19 Tage <option value=20>20 Tage
</select>"));
		$ausgabe .= tr(td(0, "left", "Aktivieren") . td(0, "right", input("checkbox", "umode", "1")));
		$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Übernehmen")));
	} else {
		$ausgabe .= tr(td(2, "center", "Du kannst den Urlaubsmodus erst 5 Tage nach dem letzten wieder aktivieren!"));
	}
} else {
	$ausgabe .= tr(td(2, "center", "Urlaubsmodus ist aktiv bis " . date("d.m.Y, H:i", $inhalte_s["urlaub"])));
}

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Passwort ändern"));
$ausgabe .= tr(td(0, "left", "Aktuelles Passwort") . td(0, "right", input("password", "passwort_alt", "")));
$ausgabe .= tr(td(0, "left", "Neues Passwort") . td(0, "right", input("password", "passwort_neu1", "")));
$ausgabe .= tr(td(0, "left", "Passwort wiederholen") . td(0, "right", input("password", "passwort_neu2", "")));
$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Ändern")));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "1. Email-Adresse"));
$ausgabe .= tr(td(0, "left", "Aktuelle Email-Adresse") . td(0, "right", $inhalte_s["email"]));
$ausgabe .= tr(td(0, "left", "Neue Email-Adresse") . td(0, "right", input("text", "email_neu", "")));
$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Ändern")));

$ausgabe .= tr(td(2, "head", "2. Email-Adresse (nur 1x änderbar)"));
if (!$inhalte_s["email2"]) {
	$ausgabe .= tr(td(0, "left", "Neue Email-Adresse") . td(0, "right", input("text", "email2_neu", "")));
	$ausgabe .= tr(td(2, "center", "Diese Email-Adresse ist nur zur Sicherung, damit niemand dein Passwort und deine Email ändern kann und du dadurch nicht mehr dein Passwort anfordern kannst.<br/>Kann also auch die gleiche Adresse wie die 1. sein."));
	$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Ändern")));
} else {
	$ausgabe .= tr(td(0, "left", "Aktuelle Email-Adresse") . td(0, "right", $inhalte_s["email2"]));
}

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Anforderung von Accountlöschung"));
if ($inhalte_s["loesch"] == 0) {
	$ausgabe .= tr(td(0, "left", "Passwort") . td(0, "right", input("password", "passwort1", "")));
	$ausgabe .= tr(td(0, "left", "Ja, ich bin mir sicher") . td(0, "right", input("checkbox", "ok", "1")));
	$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Absenden")));
} else {
	$dauer = (86400 * 7) - time() + $inhalte_s["loesch"];
	include "dauer.inc.php";
	$ausgabe .= tr(td(2, "center", "<font class=\"nein\">Account wird gelöscht in: $h Stunde(n)</font>"));
	$ausgabe .= tr(td(2, "center", hlink("new", "http://genesis.vbfreak.de/loeschung.php?sid=$sid&lid=" . $inhalte_s["loesch"] . "&aktion=cancel", "Anforderung zurückziehen")));
}

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Style-Pfad/Lokales Grafikpack"));
$ausgabe .= tr(td(2, "center", "Hier kannst du den Pfad zu einem eigenen Grafikpack angeben."));
$ausgabe .= tr(td(2, "center", "verfügbare Grafikpacks:  " . hlink("", "grafikpack.zip", "Standard") . ", " . hlink("", "pink.rar", "Pink") . ", " . hlink("", "green.rar", "Green") . ", " . hlink("", "hellsing.rar", "Hellsing") . ", " . hlink("", "reloaded.rar", "Reloaded") . ", " . hlink("", "deluxe_red.rar", "deLuxe Red") . ""));
$ausgabe .= tr(td(2, "center", input("text", "styleneu", $inhalte_s["style"]) . " " . input("submit", "aktion", "Übernehmen")));
$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Profil/Beschreibung"));
$ausgabe .= tr(td(2, "center", "Hier kannst du Informationen über dich hinterlassen.<br/>Diese werden bei den Spielerinformationen angezeigt."));
$ausgabe .= tr(td(2, "center", "<textarea name=\"profil\" wrap=\"virtual\" cols=55 rows=10>" . $inhalte_s["profil"] . "</textarea>"));
$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Speichern")));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$ausgabe .= tr(td(2, "head", "Avatar"));
if (is_file($inhalte_s["avatar"])) {
	$out = "<img src=\"" . $inhalte_s["avatar"] . "\">";
} else {
	$out = "keiner vorhanden";
}
$ausgabe .= tr(td(0, "left", "Aktueller Avatar") . td(0, "right", $out));
$ausgabe .= tr(td(2, "center", "Ein Avatar kann ein Bild (jpg, gif, png) mit der max. Grösse von 100 KB bei max. 200x200 Pixel sein."));
$ausgabe .= tr(td(0, "left", "Neuen Avatar hochladen") . td(0, "right", input("file", "avatar", "")));
$ausgabe .= tr(td(0, "left", "Avatar löschen") . td(0, "right", input("checkbox", "delava", "1")));
$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Speichern")));
$ausgabe .= "</table>\n</form>\n";

?>