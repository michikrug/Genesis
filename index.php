<?php

session_start();
//ini_set("error_reporting", "E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR");

include_once "ctracker.php";

$nav = isset($_REQUEST["nav"]) ? $_REQUEST["nav"] : NULL;

require_once "headeri.inc.php";

require_once "sicher/config.inc.php";
$connection = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$connection) die("Es konnte keine Datenbankverbindung hergestellt werden.");
mysql_select_db($mysql_db, $connection);

require_once "parser.inc.php";

require_once "functions.inc.php";

include_once "whoisonline.inc.php";

if ($nav == "home" || $nav == "login" || $nav == "anmeldung" || $nav == "forum" || $nav == "changelog" || $nav == "neuchangelog" || $nav == "story" || $nav == "faq" || $nav == "kontakt" || $nav == "agb" || $nav == "statistiken" || $nav == "highscores") {
    $nav = $nav;
} else {
    $nav = "home";
}

$ausgabe .= "<table width=\"809\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:100%;\">\n";
$ausgabe .= "\t<tr><td colspan=\"7\" valign=\"top\" height=\"102\"><img src=\"images/genesis_01.jpg\" width=\"809\" height=\"102\"></td></tr>\n";
$ausgabe .= tr(
    td(0, "nav", hlink("", "index.php?nav=home", "<img src=\"images/genesis_02.jpg\" alt=\"Home\" width=\"118\" height=\"25\" onmouseover=\"this.src='images/genesis2_02.jpg';\" onmouseout=\"this.src='images/genesis_02.jpg';\">"))
     . td(0, "nav", hlink("", "index.php?nav=login", "<img src=\"images/genesis_03.jpg\" alt=\"Login\" width=\"100\" height=\"25\" onmouseover=\"this.src='images/genesis2_03.jpg';\" onmouseout=\"this.src='images/genesis_03.jpg';\">"))
     . td(0, "nav", hlink("", "index.php?nav=anmeldung", "<img src=\"images/genesis_04.jpg\" alt=\"Anmeldung\" width=\"154\" height=\"25\" onmouseover=\"this.src='images/genesis2_04.jpg';\" onmouseout=\"this.src='images/genesis_04.jpg';\">"))
     . td(0, "nav", hlink("new", "http://forum.vbfreak.de", "<img src=\"images/genesis_05.jpg\" alt=\"Forum\" width=\"120\" height=\"25\" onmouseover=\"this.src='images/genesis2_05.jpg';\" onmouseout=\"this.src='images/genesis_05.jpg';\">"))
     . td(0, "nav", hlink("", "index.php?nav=story", "<img src=\"images/genesis_06.jpg\" alt=\"Story\" width=\"104\" height=\"25\" onmouseover=\"this.src='images/genesis2_06.jpg';\" onmouseout=\"this.src='images/genesis_06.jpg';\">"))
     . td(0, "nav", hlink("", "index.php?nav=faq", "<img src=\"images/genesis_07.jpg\" alt=\"FAQ\" width=\"75\" height=\"25\" onmouseover=\"this.src='images/genesis2_07.jpg';\" onmouseout=\"this.src='images/genesis_07.jpg';\">"))
     . td(0, "nav", hlink("", "index.php?nav=kontakt", "<img src=\"images/genesis_08.jpg\" alt=\"Kontakt\" width=\"138\" height=\"25\" onmouseover=\"this.src='images/genesis2_08.jpg';\" onmouseout=\"this.src='images/genesis_08.jpg';\">")));
$ausgabe .= "
    <tr>
        <td colspan=\"7\" valign=\"top\">
            <table name=\"main\" id=\"main\" width=\"809\" class=\"main\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                <tr>
                    <td valign=\"top\" width=\"160\" align=\"right\">
                        <br/>\n";
include_once "links.inc.php";
$ausgabe .= "
                    </td>
                        <td valign=\"top\" width=\"489\" align=\"center\">
                        <br/>\n";
include_once "$nav.php";
$ausgabe .= "
                        </center>
                    </td>
                    <td valign=\"top\" width=\"160\" align=\"left\">
                        <br/>\n";
include "rechts.inc.php";
$ausgabe .= "
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>\n";

include_once "footer.inc.php";

mysql_close($connection);

$ausgabe = str_replace("<title>Genesis</title>", "<title>Genesis :: ". ucfirst($nav) ."</title>", $ausgabe);

echo $ausgabe;

?>