<?php

// include "poll.inc.php";

$ausgabe .= table(150, "bg");
$ausgabe .= tr(td(0, "head", "Info"));
$ausgabe .= tr(td(0, "center", "Du willst mehr über Genesis erfahren? Dann besuche unser " . hlink("new", "http://genesis.vbfreak.de/wiki", "Wiki") . "."));
$ausgabe .= tr(td(0, "center", "" . hlink("", "index.php?nav=highscores", "Aktuelle Highscores")));
$ausgabe .= "</table>\n<br>\n";

include "shoutbox.inc.php";


?>