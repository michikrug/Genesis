<?php

$news_id = isset($_REQUEST["news_id"]) ? $_REQUEST["news_id"] : NULL;
$typ = isset($_REQUEST["typ"]) ? $_REQUEST["typ"] : NULL;
$name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : NULL;
$text = isset($_REQUEST["text"]) ? $_REQUEST["text"] : NULL;
$code_x = isset($_REQUEST["code_x"]) ? $_REQUEST["code_x"] : NULL;
$code_y = isset($_REQUEST["code_y"]) ? $_REQUEST["code_y"] : NULL;

$ip = $_SERVER['REMOTE_ADDR'];
$daten = mysql_query("SELECT code FROM genesis_codes WHERE ip='$ip'");
$da = mysql_fetch_array($daten, MYSQL_ASSOC);
$check = $da["code"];

if ($news_id > 0) {
	if (($_SESSION["name"] || (isset($code_y) && isset($code_x))) && $typ == "comm" && $name != "" && $text != "") {
		if ($_SESSION["name"] || ($check - 15 < $code_x && $check + 15 > $code_x && $code_y > 15 && $code_y < 60)) {
			$name = sauber($name);
			$text = sauber($text);
			mysql_query("insert into gen_comments (zeit, news_id, name, text) values ('" . time() . "', '$news_id', '$name', '$text')");
			unset($name, $text);
		}
	}
	$qry = "select * from gen_news where id='$news_id'";
	if ($_SESSION["name"]) {
		mysql_query("update genesis_spieler set lastnews='$news_id' where name='" . $_SESSION["name"] . "' and lastnews<'$news_id'");
	}
} else {
	$qry = "select * from gen_news order by zeit desc limit 5";
}

$first = 0;
$result = mysql_query($qry);
while ($inhalte = mysql_fetch_array($result)) {
	$result2 = mysql_query("select id from gen_comments where news_id='" . $inhalte["id"] . "'");
	$comm_anz = mysql_num_rows($result2);
	$ausgabe .= table(450, "bg");
	$ausgabe .= tr(td(0, "head", "<a id=\"news" . $inhalte["id"] . "\" class=\"nc\" href=\"#news" . $inhalte["id"] . "\" onClick=\"showhide('n" . $inhalte["id"] . "')\">" . date("d.m.Y", $inhalte["zeit"]) . " - " . $inhalte["headline"] . "</a>"));
	if ($first <= 1) {
		$ausgabe .= "   <tr name=\"n" . $inhalte["id"] . "\" id=\"n" . $inhalte["id"] . "\">\n " . td(0, "center", parsetxt($inhalte["text"])) . "\n</tr>\n";
		$ausgabe .= "   <tr name=\"n" . $inhalte["id"] . "\" id=\"n" . $inhalte["id"] . "\">\n " . td(0, "right", hlink("", "index.php?nav=home&news_id=" . $inhalte["id"], "<i>$comm_anz Kommentar(e)</i>")) . "\n</tr>\n";
	} else {
		$ausgabe .= "   <tr name=\"n" . $inhalte["id"] . "\" id=\"n" . $inhalte["id"] . "\" style=\"display:none\">\n " . td(0, "center", parsetxt($inhalte["text"])) . "\n</tr>\n";
		$ausgabe .= "   <tr name=\"n" . $inhalte["id"] . "\" id=\"n" . $inhalte["id"] . "\" style=\"display:none\">\n " . td(0, "right", hlink("", "index.php?nav=home&news_id=" . $inhalte["id"], "<i>$comm_anz Kommentar(e)</i>")) . "\n</tr>\n";
	}
	$ausgabe .= "</table>\n<br/>\n";
	$first++;
	if ($news_id > 0) {
		$ausgabe .= table(450, "bg");
		$ausgabe .= tr(td(0, "head", "Kommentare"));
		$result2 = mysql_query("select * from gen_comments where news_id='$news_id' order by zeit desc");
		while ($inhalte2 = mysql_fetch_array($result2)) {
			$ausgabe .= tr(td(0, "center", parsetxt("[b]" . $inhalte2["name"] . ":[/b] " . $inhalte2["text"] . " [i](" . date("d.m.Y", $inhalte2["zeit"]) . ")[/i]")));
		}
		$ausgabe .= "</table>\n<br/>\n";
		$ausgabe .= table(450, "bg");
		$ausgabe .= tr(td(0, "head", "Kommentar schreiben"));
		if ($_SESSION["name"]) {
			$ausgabe .= tr(td(0, "center",
					form("index.php?nav=home&news_id=$news_id&typ=comm")
					. "<b>Name:</b> " . input("shoutl", "name", $_SESSION["name"]) . "<br/>"
					. "<textarea name=\"text\" rows=4 cols=50></textarea><br/>"
					. input("submit", "aktion", "Absenden") . "</form>"));
		} else {
			include "code.inc.php";
			$ausgabe .= tr(td(0, "center",
					form("index.php?nav=home&news_id=$news_id&typ=comm")
					. "<b>Name:</b> " . input("shout", "name", $name) . "<br/>"
					. "<textarea name=\"text\" rows=4 cols=50>". $text ."</textarea><br/>Zum Eintragen auf <b>$cod</b> klicken<br/>"
					. input("image", "code", "src=\"images/codes/code". $codezeit .".png\" style=\"border:none\"")
					));
		}
		$ausgabe .= "</table>\n<br/>\n";
	}
}

?>