<?php

require_once "parser.inc.php";
$ausgabe .= table(600, "bg");
$ausgabe .= tr(td(0, "head", "Changelog (die letzten 5 Einträge)"));
unset($updated);

$result = mysql_query("SELECT post_id, post_time, post_text FROM forum.phpbb_posts WHERE topic_id='45' ORDER BY post_time DESC LIMIT 5");
while($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)){
	if (!$updated) {
		mysql_select_db("genesis");
		mysql_query("update genesis_spieler set lastpost='" . $inhalte["post_id"] . "' where id='$sid'");
		$updated = 1;
	}
	if ($inhalte_s["lastpost"] < $inhalte["post_id"]) {
		$ausgabe .= tr(td(0, "navi", "<font class=\"ja\">NEU</font> Eingetragen: ". date("d.m.Y (H:i:s)", $inhalte["post_time"])));
	} else {
		$ausgabe .= tr(td(0, "navi", "Eingetragen: ". date("d.m.Y (H:i:s)", $inhalte["post_time"])));
	}
	$ausgabe .= tr(td(0, "left", parsetxt($inhalte["post_text"])));
	$ausgabe .= tr(td(0, "center", "<hr/>"));
	$post_id = $inhalte["post_id"];
}
$ausgabe .= tr(td(0, "center", "Mehr im " . hlink("new", "http://forum.vbfreak.de/viewtopic.php?p=" . $post_id . "#p" . $post_id, "Forum-Thread")));
$ausgabe .= "</table>\n";
unset($updated,$inhalte,$result,$post_id);

?>