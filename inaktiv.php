<?php

include_once "sicher/config.inc.php";
$conn = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db, $conn);

$zeit = time() - (86400 * 7);

include_once "functions.inc.php";

$result = mysql_query("SELECT id,login,name,email,punkte,inaktivmail FROM genesis_spieler WHERE (loesch<'$zeit' and loesch>'0') or (loesch<>'4294967295' and urlaub<'$zeit' and inaktivmail<'$zeit' and inaktivmail>'0')", $conn);
while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$message = "Löschung des Accounts bestätigt:\n";
	$message .= "Name: ". $inhalte["name"] ."\n";
	$message .= "Punkte: " . $inhalte["punkte"] . "\n";
	$message .= "Login: " . $inhalte["login"] . "\n";
	$message .= "Email: " . $inhalte["email"] . "\n";
	$message .= "IMail:". date("H:i:s (d.m.Y)", $inhalte["inaktivmail"]) ."\n\n";
	$message .= "http://genesis.vbfreak.de/loeschung.php?sid=" . $inhalte["id"] . "&aktion=delete";
	$header = "From: VBFrEaK <vbfreak@freakmail.de>\n";
	$header .= "Date: " . date("D, d M Y H:i:s") . " UT\n";
	$header .= "Reply-To: VBFrEaK <vbfreak@freakmail.de>\n";
	$header .= "X-Mailer: PHP/" . phpversion() . "\n";
	$header .= "MIME-Version: 1.0\n";
	$header .= "Content-Type: text/plain; charset=iso-8859-1\n";
	$mail_gesendet = mail("vbfreak@vbfreak.de", "Account-Löschung", preg_replace("#(?<!\r)\n#s", "\n", $message), $header);
	mysql_query("UPDATE genesis_spieler SET loesch='4294967295' WHERE id='" . $inhalte["id"] . "'", $conn);
	echo $inhalte["login"] . " (" . $inhalte["name"] . "), " . $inhalte["email"] . "<br>\n";
}

unset($mail_gesendet, $header, $message, $inhalte, $result);

$zeit = time() - (86400 * 14);

$result = mysql_query("SELECT id,login,name,email FROM genesis_spieler WHERE urlaub<'$zeit' and log<'$zeit' and inaktivmail='0' and loesch='0'", $conn);
while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$message = "Hallo " . $inhalte["name"] . "!
Du hast dich seit min. 14 Tagen nicht mehr bei Genesis eingeloggt.
Wenn die nächsten 7 Tage ohne einen Login vergehen wird dein Account unwiderruflich gelöscht.

Dein Loginname: " . $inhalte["login"] . "
Dein Passwort kannst du per \"Passwort vergessen\" auf der Loginseite gegebenfalls anfordern!

http://genesis.vbfreak.de";
	$header = "From: VBFrEaK <vbfreak@freakmail.de>\n";
	$header .= "Date: " . date("D, d M Y H:i:s") . " UT\n";
	$header .= "Reply-To: VBFrEaK <vbfreak@freakmail.de>\n";
	$header .= "X-Mailer: PHP/" . phpversion() . "\n";
	$header .= "MIME-Version: 1.0\n";
	$header .= "Content-Type: text/plain; charset=iso-8859-1\n";
	$mail_gesendet = mail($inhalte["email"], "Dein Genesis Account", preg_replace("#(?<!\r)\n#s", "\n", $message), $header);
	mysql_query("UPDATE genesis_spieler SET inaktivmail='" . time() . "' WHERE id='" . $inhalte["id"] . "'", $conn);
	echo $inhalte["login"] . " (" . $inhalte["name"] . "), " . $inhalte["email"] . "<br>\n";
}

unset($mail_gesendet, $header, $message, $inhalte, $result);

mysql_close($conn);

?>