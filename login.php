<?php

$l_login = isset($_REQUEST["l_login"]) ? $_REQUEST["l_login"] : NULL;
$l_passwort = isset($_REQUEST["l_passwort"]) ? $_REQUEST["l_passwort"] : NULL;
$l_aktion = isset($_REQUEST["l_aktion"]) ? $_REQUEST["l_aktion"] : NULL;
$l_email = isset($_REQUEST["l_email"]) ? $_REQUEST["l_email"] : NULL;
$a = isset($_REQUEST["a"]) ? $_REQUEST["a"] : NULL;
$code_x = isset($_REQUEST["code_x"]) ? $_REQUEST["code_x"] : NULL;
$code_y = isset($_REQUEST["code_y"]) ? $_REQUEST["code_y"] : NULL;

if (time() > 1230134400) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $daten = mysql_query("SELECT code FROM genesis_codes WHERE ip='" . $ip . "'");
    $da = mysql_fetch_array($daten, MYSQL_ASSOC);
    $check = $da["code"];
    $inhalt = " ";
    if ($code_x != "" && $code_y != "" && $l_login != "" && $l_passwort != "" && $check - 15 < $code_x && $check + 15 > $code_x && $code_y > 15 && $code_y < 60) {
        $inhalt = "";
        $result = mysql_query("SELECT id,name,passwort,style,gesperrt,sessid,alli FROM genesis_spieler WHERE login='$l_login'");
        $inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
        if ($inhalte) {
            $name = $inhalte["name"];
            $sid = $inhalte["id"];
            if (md5($l_passwort) == $inhalte["passwort"]) {
                if ($inhalte["gesperrt"] < time()) {
                    if (!isset($_COOKIE['gencookie']) || $_COOKIE['gencookie'] == $name || $_COOKIE['gencookie'] == "") {
                        setcookie ("gencookie", "", 315532800);
                        $_SESSION["sid"] = $sid;
                        mt_srand((double)microtime()*1000000);
                        $_SESSION["name"] = $name;
                        $_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
                        $_SESSION["delay"] = time() + mt_rand(60, 1800);
                        $_SESSION["klicks"] = mt_rand(80, 120);
                        $_SESSION["next"] = time() + (mt_rand(45, 75) * 60);
                        setcookie ("gencookie", $name, time() + 14400);
                        $id = session_id();
                        mysql_query("UPDATE genesis_spieler SET log='". time() ."', inaktivmail='0', sessid='$id' WHERE id='$sid'");
                        $ausgabe .= hlink("", "game.php?id=$id&b=1&nav=uebersicht", "Spiel betreten");
                        $ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=1&nav=uebersicht'\",3000)</script>\n";
                    } else {
                        $inhalt .= tr(td(2, "nein", "<b>Fehler beim Login!</b><br/>Ein anderen Spieler ist evt. schon eingeloggt oder der Cookie konnte nicht gesetzt werden."));
                    }
                } else {
                    $inhalt .= tr(td(2, "nein", "<b>Dieser Account ist gesperrt bis" . date("H:i:s (d.m.Y)", $inhalte["gesperrt"]) . "!</b><br>Für Gründe oder Erklärungen kontaktiere VBFrEaK (siehe Kontakt)"));
                }
            } else {
                $inhalt .= tr(td(2, "nein", "<b>Passwort falsch!</b>"));
            }
        } else {
            $inhalt .= tr(td(2, "nein", "<b>Loginname nicht vorhanden!</b>"));
        }
    }

    if ($inhalt != "") {
        include "code.inc.php";
        $ausgabe .= form("index.php?nav=$nav");
        $ausgabe .= table(250, "bg");
        $ausgabe .= tr(td(2, "head", "Login"));
        $ausgabe .= tr(td(0, "left", "<b>Name</b>") . td(0, "right", input("text", "l_login", "")));
        $ausgabe .= tr(td(0, "left", "<b>Passwort</b>") . td(0, "right", input("password", "l_passwort", "")));
        $ausgabe .= tr(td(2, "center", $out));
        $ausgabe .= tr(td(2, "center", input("image", "code", "src=\"images/codes/code". $codezeit .".png\" style=\"border:none\"")));
        $ausgabe .= "\n$inhalt";
        $ausgabe .= tr(td(2, "center", hlink("", "index.php?nav=$nav&a=pw", "Passwort vergessen")));
        $ausgabe .= "</table>\n</form>\n";
    }
} else {
    $ausgabe .= table(350, "bg");
    $ausgabe .= tr(td(2, "head", "Login"));
    $ausgabe .= tr(td(2, "center", "<b class=ja>Runde beendet</b>"));
    $ausgabe .= tr(td(2, "center", "<b>Der Login wird zum Start der neuen Runde wieder freigeschaltet.</b>"));

    $ausgabe .= tr(td(2, "center", "<b>Es geht weiter in <span id=\"timer\">". (1230134400 - time()) ." Sekunden.</b>"));

    $ausgabe .= "</table>\n";
}

?>