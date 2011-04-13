<?php

$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
$typ = isset($_REQUEST["typ"]) ? $_REQUEST["typ"] : NULL;
$sname = isset($_REQUEST["sname"]) ? $_REQUEST["sname"] : NULL;
$stext = isset($_REQUEST["stext"]) ? $_REQUEST["stext"] : NULL;

if ($aktion == "Absenden" && $typ == "shout" && $sname != "" && $stext != "" && $sname != "Name" && $stext != "Text" && $sname != "Text" && $stext != "Name") {
	$sname = sauber($_SESSION["name"]);
	$stext = substr(sauber($stext), 0, 50);
	$result = mysql_query("select name,text from gen_shoutbox order by zeit desc limit 1");
	$inhalte = mysql_fetch_array($result);

	$asd = split(" ", $stext . " 1 2 3 4");
	if ($asd[0] != $asd[1] && $asd[1] != $asd[2] && $asd[2] != $asd[3]) {
		if ($sname != $inhalte["name"] || $stext != $inhalte["text"]) {
			mysql_query("insert into gen_shoutbox (zeit, name, text) values ('" . time() . "', '$sname', '$stext')");
		}
	}
}

$ausgabe .= table(150, "bg");
$ausgabe .= tr(td(0, "head", "Shoutbox"));

$result = mysql_query("select * from gen_shoutbox order by zeit desc limit 5");
while ($inhalte = mysql_fetch_array($result)) {
	$ausgabe .= tr(td(0, "left", parsetxt("[b]" . $inhalte["name"] . ":[/b] " . $inhalte["text"])));
}

if (isset($_SESSION["name"])) {
	$result = mysql_query("select name from gen_shoutbox order by zeit desc limit 1");
	$inhalte = mysql_fetch_array($result);
	if ($inhalte["name"] != $_SESSION["name"]) {
		$ausgabe .= tr(td(0, "center",
				form("index.php?nav=$nav&typ=shout")
				 . input("shoutl", "sname", $_SESSION["name"]) . "<br>"
				 . input("shout", "stext", "Text") . "<br>"
				 . input("submit", "aktion", "Absenden") . "</form>"));
	}
} else {
	$ausgabe .= tr(td(0, "center", "Eintrag nur Eingeloggt möglich"));
}
$ausgabe .= "</table>\n";

?>