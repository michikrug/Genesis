<?php

$a_login = isset($_REQUEST["a_login"]) ? $_REQUEST["a_login"] : NULL;
$a_name = isset($_REQUEST["a_name"]) ? $_REQUEST["a_name"] : NULL;
$a_passwort1 = isset($_REQUEST["a_passwort1"]) ? $_REQUEST["a_passwort1"] : NULL;
$a_passwort2 = isset($_REQUEST["a_passwort2"]) ? $_REQUEST["a_passwort2"] : NULL;
$a_agb = isset($_REQUEST["a_agb"]) ? $_REQUEST["a_agb"] : NULL;
$a_aktion = isset($_REQUEST["a_aktion"]) ? $_REQUEST["a_aktion"] : NULL;

$ausgabe .= form("index.php?nav=$nav");
$ausgabe .= table(300, "bg");
$ausgabe .= tr(td(2, "head", "Anmeldung"));

if (time() > 1125560000) {
    $ausgabe .= tr(td(0, "left", "<b>Loginname</b>") . td(0, "right", input("text", "a_login", "")));
    $ausgabe .= tr(td(0, "left", "<b>Ingamename</b>") . td(0, "right", input("text", "a_name", "")));
    $ausgabe .= tr(td(0, "left", "<b>Passwort</b>") . td(0, "right", input("password", "a_passwort1", "")));
    $ausgabe .= tr(td(0, "left", "<b>Passwort wdh.</b>") . td(0, "right", input("password", "a_passwort2", "")));
    $ausgabe .= tr(td(2, "center", input("checkbox", "a_agb", "1") . " Hiermit erkenne ich die <a href=\"index.php?nav=agb\">AGB</a> an</div>"));
    $ausgabe .= tr(td(2, "center", "<input onClick=\"return confirm('Hast du dir deinen Loginname und dein Passwort gut gemerkt?');\" type=\"submit\" name=\"a_aktion\" value=\"Anmelden\" size=\"20\">"));

    if ($a_login != "" && $a_name != "" && $a_passwort1 != "" && $a_passwort2 != "") {
        $fehler = "";

        $a_login = sauber($a_login);
        $a_name = sauber($a_name);

        $a_agb_check = 1;
        $a_login_check = 1;
        $a_name_check = 1;
        $a_pw_check = 1;

        if (!$a_agb) {
            $a_agb_check = 0;
            $ausgabe .= tr(td(2, "nein", "<b>Du musst die AGB anerkennen!</b>"));
        }
        if (!eregi("^[0-9a-zA-Z]*$", $a_login)) {
            $a_login_check = 0;
            $ausgabe .= tr(td(2, "nein", "<b>Loginname enthält Sonderzeichen!</b>"));
        }
        if ($a_login == $a_name) {
            $a_name_check = 0;
            $ausgabe .= tr(td(2, "nein", "<b>Loginname und Ingame-Namen müssen verschieden sein!</b>"));
        }
        if ($a_passwort1 != $a_passwort2) {
            $a_pw_check = 0;
            $ausgabe .= tr(td(2, "nein", "<b>Passwörter stimmen nicht überein!</b>"));
        }

        if ($a_aktion == "Anmelden" && $a_login_check == 1 && $a_name_check == 1 && $a_pw_check == 1 && $a_agb_check == 1) {
            $a_login_vorhanden = 0;
            $a_name_vorhanden = 0;
            $zeit = time();

            $result = mysql_query("SELECT id FROM genesis_spieler WHERE login='$a_login'");
            if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) $a_login_vorhanden = 1;
            $result = mysql_query("SELECT id FROM genesis_spieler WHERE name='$a_name'");
            if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) $a_name_vorhanden = 1;

            if ($a_login_vorhanden == 0) {
                if ($a_name_vorhanden == 0) {
                    srand((double)microtime() * 1000000);
                    mt_srand((double)microtime() * 1000000);
                    $koords = "";
                    while ($koords == "") {
                        $koords1 = intval(mt_rand(1, 6));
                        $koords2 = intval(mt_rand(1, 6));
                        $koords3 = intval(mt_rand(1, 6));
                        $koords = check_koord("$koords1:$koords2:$koords3");
                    }
                    $passwort = md5($a_passwort1);
                    $result = mysql_query("SELECT max(id) as id FROM gen_news");
                    $inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
                    if (mysql_query("INSERT INTO genesis_spieler (login, name, passwort, basis1, log, lastnews, atttime) VALUES ('$a_login', '$a_name', '$passwort', '$koords', '" . time() . "', '" . $inhalte["id"] . "', '" . time() . "')")) {
                        $sid = mysql_insert_id();
                        $zufb = intval(mt_rand(1, 6));
                        mysql_query("UPDATE genesis_basen SET name='$a_name', bname='Neogen', ress1='40000', ress2='25000', ress3='0', ress4='0', ress5='0', konst1='1', resszeit='$zeit', bild='$zufb' WHERE koordx='$koords1' AND koordy='$koords2' AND koordz='$koords3'");
                        $ausgabe .= tr(td(2, "ja", "<b>Die Anmeldung war erfolgreich.<br/>Du kannst dich sofort mit deinen gewählten Logindaten einloggen und loslegen. Viel Spaß!</b>"));
                    } else {
                        $ausgabe .= tr(td(2, "nein", "<b>Die Anmeldung ist fehlgeschlagen, bitte überprüfe deine Daten und versuche es erneut.</b>"));
                    }
                } else {
                    $ausgabe .= tr(td(2, "nein", "<b>Der Ingame-Name ist schon vergeben, bitte wähle einen anderen.</b>"));
                }
            } else {
                $ausgabe .= tr(td(2, "nein", "<b>Der Loginname ist schon vergeben, bitte wähle einen anderen.</b>"));
            }
        }
    }
    $ausgabe .= "</table>\n</form>\n<br/>
Bitte keine <i title='Leerzeichen ,- ,_ %, #, $, /, \, ? ...'>Sonderzeichen</i> beim Loginname benutzen.<br/>
Loginname und Ingame-Name müssen aus Sicherheitsgründen verschieden sein.";
} else {
    $ausgabe .= tr(td(2, "center", "<b class=ja>Runde beendet</b>"));
    $ausgabe .= tr(td(2, "center", "<b>Die Anmeldung zum Start der neuen Runde wieder freigeschaltet.</b>"));
    $ausgabe .= tr(td(2, "center", hlink("new", "http://www.galaxy-news.de/?page=charts&op=vote&game_id=35", "<img src=\"images/vote.gif\" border=0>")));
    $ausgabe .= "</table>\n</form>\n";
}

?>