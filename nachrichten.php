<?php

//update genesis_spieler s set s.nachrichten=(select count(*) from genesis_news n where n.typ='news' and n.newsalt='0' and n.an=s.id);

$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
$n = isset($_REQUEST["n"]) ? $_REQUEST["n"] : NULL;
$betreff = isset($_REQUEST["betreff"]) ? $_REQUEST["betreff"] : NULL;
$knx = isset($_REQUEST["knx"]) ? $_REQUEST["knx"] : NULL;
$kny = isset($_REQUEST["kny"]) ? $_REQUEST["kny"] : NULL;
$knz = isset($_REQUEST["knz"]) ? $_REQUEST["knz"] : NULL;
$empf = isset($_REQUEST["empf"]) ? $_REQUEST["empf"] : NULL;
$news = isset($_REQUEST["news"]) ? $_REQUEST["news"] : NULL;
$nid = isset($_REQUEST["nid"]) ? $_REQUEST["nid"] : NULL;
$w = isset($_REQUEST["w"]) ? $_REQUEST["w"] : NULL;
$zv = isset($_REQUEST["zv"]) ? $_REQUEST["zv"] : NULL;
$f = isset($_REQUEST["f"]) ? $_REQUEST["f"] : NULL;

include "parser.inc.php";

$zeit = time();
$fehler = "";
$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav&zv=$zv");
$ausgabe .= table(600, "bg");

if ($aktion == "Aktualisieren") {
	$notizen = sauber($notizen);
	mysql_query("UPDATE genesis_spieler SET notiz='". $notizen ."' WHERE id='$sid'");
	$inhalte_s["notiz"] = $notizen;
} elseif ($aktion == "Nachrichten löschen") {
	$qry = "SELECT id FROM genesis_news WHERE an='$sid'";
	if ($w == 1) $qry .= " AND typ='ereignis' AND newsalt='2' AND meldung='0'";
	if ($w == 2) $qry .= " AND typ='ereignis' AND newsalt='1' AND meldung='0'";
	if ($w == 3) $qry .= " AND typ='news' AND newsalt='0' AND meldung='0'";
	if ($w == 4) $qry .= " AND typ='news' AND newsalt='1' AND meldung='0'";
	$result2 = mysql_query($qry);
	while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		$nclr = $_REQUEST["n" . $inhalte2["id"]];
		if (($nclr == 1 && $n == 1) || $n == 0 || ($nclr == 0 && $n == 2)) {
			$qry = "DELETE FROM genesis_news WHERE id='" . $inhalte2["id"] . "' AND an='$sid' AND";
			if ($w == 1) mysql_query("$qry typ='ereignis' AND newsalt='2' AND meldung='0'");
			if ($w == 2) mysql_query("$qry typ='ereignis' AND newsalt='1' AND meldung='0'");
			if ($w == 3) {
				mysql_query("$qry typ='news' AND newsalt='0' AND meldung='0'");
				mysql_query("UPDATE genesis_spieler SET nachrichten=nachrichten-1 WHERE id='$sid'");
			}
			if ($w == 4) mysql_query("$qry typ='news' AND newsalt='1' AND meldung='0'");
		}
	}
} elseif ($aktion == "Nachricht versenden" && ($empf != "" || ($knx != "" && $kny != "" && $knz != "")) && $news != "") {
	if ($betreff == "" || $betreff == " ") {
		$betreff = "kein Betreff";
	}
	if ($knx != "" && $kny != "" && $knz != "" && $empf == "") {
		$result1 = mysql_query("SELECT name FROM genesis_basen WHERE koordx='$knx' AND koordy='$kny' AND koordz='$knz'");
		$inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
		$empf = $inhalte1["name"];
	}
	$news = sauber($news);
	$betreff = sauber($betreff);
	$result = mysql_query("SELECT id FROM genesis_spieler WHERE name='$empf'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$fehler = "<font color=green>Nachricht erfolgreich versendet!</font><br/>";
		mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('$sid','" . $inhalte["id"] . "','$zeit','news','$betreff','$news')");
		mysql_query("UPDATE genesis_spieler SET nachrichten=nachrichten+1 WHERE id='" . $inhalte["id"] . "'");
	} else {
		$w = "new";
		$fehler = "<font color=red>Nachricht konnte nicht versendet werden.<br/>Dieser Spieler existiert nicht.</font><br/>";
	}
} elseif ($w == "meld" && $n != "") {
	if ($aktion == "Absenden" && $grund != "") {
		$w = "rd";
		$result2 = mysql_query("SELECT news FROM genesis_news WHERE id='$n'");
		$inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		$neutext = $inhalte2["news"] ."\n[Meldung] $grund";
		mysql_query("UPDATE genesis_news SET news='$neutext', meldung='1' WHERE id='$n'");
		$fehler = "<br/><font color=red>Die Nachricht wurde dem Administrator gemeldet.<br/>Gemeldete Nachrichten können einige Tage lang nicht gelöscht werden!</font><br/>";
	} else {
		$ausgabe .= tr(td(2, "head", "Nachricht melden"));
		$ausgabe .= tr(td(0, "center", "Grund") . td(0, "center", input("text", "grund", $grund) . input("hidden", "n", $n) . input("hidden", "w", $w)));
		$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Absenden")));
		$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=rd&n=$n", "Zurück")));
  	}
}

mysql_query("UPDATE genesis_news SET newsalt='1' WHERE an='$sid' AND typ='ereignis' AND newsalt='2'");

if ($zv == "") {
	$zv = 0;
}

if ($w == "clr" && $n != "") {
	mysql_query("DELETE FROM genesis_news WHERE id='$n' AND an='$sid' AND meldung='0'");
	$w = "";
}

if ($zv == 0) {
	$start = mktime(0, 0, 0, date("m", time()), date("d", time()), date("Y", time()));
	$ende = mktime(23, 59, 59, date("m", time()), date("d", time()), date("Y", time()));
	$ausgabezv = tr(td(4, "boldc", hlink("nc", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=0", "heute") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=1", "letzten 3 Tage") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=2", "letzten 7 Tage") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=3", "davor")));
} elseif ($zv == 1) {
	$start = mktime(0, 0, 0, date("m", time() - 86400*3), date("d", time() - 86400*3), date("Y", time() - 86400*3));
	$ende = mktime(23, 59, 59, date("m", time()), date("d", time()), date("Y", time()));
	$ausgabezv = tr(td(4, "boldc", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=0", "heute") . " - " . hlink("nc", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=1", "letzten 3 Tage") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=2", "letzten 7 Tage") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=3", "davor")));
} elseif ($zv == 2) {
	$start = mktime(0, 0, 0, date("m", time() - 86400*7), date("d", time() - 86400*7), date("Y", time() - 86400*7));
	$ende = mktime(23, 59, 59, date("m", time()), date("d", time()), date("Y", time()));
	$ausgabezv = tr(td(4, "boldc", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=0", "heute") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=1", "letzten 3 Tage") . " - " . hlink("nc", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=2", "letzten 7 Tage") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=3", "davor")));
} elseif ($zv == 3) {
	$start = 0;
	$ende = mktime(23, 59, 59, date("m", time() - 86400*8), date("d", time() - 86400*8), date("Y", time() - 86400*8));
	$ausgabezv = tr(td(4, "boldc", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=0", "heute") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=1", "letzten 3 Tage") . " - " . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=2", "letzten 7 Tage") . " - " . hlink("nc", "game.php?id=$id&b=$b&nav=$nav&w=$w&zv=3", "davor")));
}

if ($w == "") {

	$zeitalt = time() - 86400 * 14;
	mysql_query("DELETE FROM genesis_news WHERE an='$sid' AND typ='ereignis' AND newsalt='1' AND meldung='0' AND zeit<'$zeitalt'");
	$zeitalt = time() - 86400 * 21;
	mysql_query("DELETE FROM genesis_news WHERE an='$sid' AND typ='news' AND newsalt='1' AND meldung='0' AND zeit<'$zeitalt'");

	$ausgabe .= tr(td(0, "head", "Nachrichten"));
	$result = mysql_query("SELECT count(*) FROM genesis_news WHERE an='$sid' AND typ='ereignis' AND newsalt='0'");
	$inhalte = mysql_fetch_array($result, MYSQL_NUM);
	$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=1", "Neue Ereignisse (" . $inhalte[0] . ")")));
	$result = mysql_query("SELECT count(*) FROM genesis_news WHERE an='$sid' AND typ='ereignis' AND newsalt='1'");
	$inhalte = mysql_fetch_array($result, MYSQL_NUM);
	$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=2", "Alte Ereignisse (" . $inhalte[0] . ")")));
	$result = mysql_query("SELECT count(*) FROM genesis_news WHERE an='$sid' AND typ='news' AND newsalt='0'");
	$inhalte = mysql_fetch_array($result, MYSQL_NUM);
	$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=3", "Neue Nachrichten (" . $inhalte[0] . ")")));
	$result = mysql_query("SELECT count(*) FROM genesis_news WHERE an='$sid' AND typ='news' AND newsalt='1'");
	$inhalte = mysql_fetch_array($result, MYSQL_NUM);
	$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=4", "Gelesene Nachrichten (" . $inhalte[0] . ")")));
	$result = mysql_query("SELECT count(*) FROM genesis_news WHERE von='$sid' AND typ='news' AND betreff not like '[Symbiosen Rundschreiben]%'");
	$inhalte = mysql_fetch_array($result, MYSQL_NUM);
	$result1 = mysql_query("SELECT count(*) FROM genesis_news WHERE von='$sid' AND typ='news' AND betreff like '[Symbiosen Rundschreiben]%' group by betreff");
	$inhalte1 = mysql_fetch_array($result1, MYSQL_NUM);
	$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=5", "Gesendete Nachrichten (" . ($inhalte[0] + $inhalte1[0]) . ")")));
	$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=new", "Neue Nachricht schreiben")));
	$ausgabe .= "</table>\n<br/>\n";
	$ausgabe .= table(600, "bg");
	$ausgabe .= tr(td(0, "head", "persönliche Notizen"));
	$ausgabe .= tr(td(0, "center", "<textarea name=\"notizen\" cols=\"60\" rows=\"10\">" . $inhalte_s["notiz"] . "</textarea>"));
	$ausgabe .= tr(td(0, "center", input("submit", "aktion", "Aktualisieren")));

} elseif ($w == 1 || $w == 2) {
	if ($w == 1) {
		$ausgabe .= tr(td(4, "head", "Neue Ereignisse" . input("hidden", "w", $w)));
		$result = mysql_query("SELECT * FROM genesis_news WHERE an='$sid' AND typ='ereignis' AND newsalt='0' ORDER BY zeit DESC");
		mysql_query("UPDATE genesis_spieler SET ereignisse='0' WHERE id='$sid'");
	}
	if ($w == 2) {
		unset($f1, $f2, $f3, $f4, $f0);
		if ($f <= 0) {
			$f0 = " selected";
			$fil = "";
		} elseif ($f == 1) {
			$f1 = " selected";
			$fil = " and betreff='Ausbau'";
		} elseif ($f == 2) {
			$f2 = " selected";
			$fil = " and betreff='Evolution'";
		} elseif ($f == 3) {
			$f3 = " selected";
			$fil = " and betreff='Produktion'";
		} elseif ($f == 4) {
			$f4 = " selected";
			$fil = " and betreff='Mission'";
		}
		$ausgabe .= tr(td(4, "head", "Alte Ereignisse" . input("hidden", "w", $w)));
		$ausgabe .= $ausgabezv;
		$ausgabe .= tr(td(4, "center", "<select name=f><option value=0$f0>Alle</option><option value=1$f1>Ausbau</option><option value=2$f2>Evolution</option><option value=3$f3>Produktion</option><option value=4$f4>Mission</option></select>&nbsp;&nbsp;&nbsp;" . input("submit", "aktion", "Filter anwenden")));
		$ausgabe .= tr(td(4, "hr", "<hr>"));
		$result = mysql_query("SELECT * FROM genesis_news WHERE zeit>='$start' and zeit<='$ende' and an='$sid' AND typ='ereignis' AND newsalt='1'$fil ORDER BY zeit DESC");
	}

	while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ausgabe .= tr(
			td(0, "left", date("H:i:s", $inhalte["zeit"]) . "<br/>" . date("(d.m.Y)", $inhalte["zeit"]))
			 . td(0, "left", censor($inhalte["betreff"]))
			 . td(0, "left", str_replace("#SID#", $id . "&b=$b", $inhalte["news"]))
			 . td(0, "center", input("checkbox", "n" . $inhalte["id"], 1)));
		$ausgabe .= tr(td(4, "hr", "<hr>"));
		mysql_query("UPDATE genesis_news SET newsalt='2' WHERE id='" . $inhalte["id"] . "' AND an='$sid' AND typ='ereignis' AND newsalt='0'");
	}


} elseif ($w == 3 || $w == 4) {
	if ($w == 3) {
		$ausgabe .= tr(td(4, "head", "Neue Nachrichten" . input("hidden", "w", $w)));
		$result = mysql_query("SELECT * FROM genesis_news WHERE an='$sid' AND typ='news' AND newsalt='0' ORDER BY zeit DESC");
		$ausgabe .= tr(td(0, "navi", "Zeit") . td(0, "navi", "Absender") . td(0, "navi", "Betreff") . td(0, "navi", " "));
		while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($inhalte["von"] != "0") {
				$out = hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte["von"], id2name($inhalte["von"]));
			} else {
				$out = "Administrator";
			}
			$ausgabe .= tr(
				td(0, "center", date("H:i:s (d.m.Y)", $inhalte["zeit"]))
				 . td(0, "center", $out)
				 . td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=rd&n=" . $inhalte["id"], censor($inhalte["betreff"])))
				 . td(0, "center", input("checkbox", "n" . $inhalte["id"], 1)));
			$ausgabe .= tr(td(4, "hr", "<hr>"));
		}
	}
	if ($w == 4) {
		$ausgabe .= tr(td(4, "head", "Gelesene Nachrichten" . input("hidden", "w", $w)));
		$ausgabe .= $ausgabezv;
		$ausgabe .= tr(td(0, "navi", "Zeit") . td(0, "navi", "Absender") . td(0, "navi", "Betreff") . td(0, "navi", " "));

		$result = mysql_query("SELECT * FROM genesis_news WHERE zeit>='$start' and zeit<='$ende' and an='$sid' AND typ='news' AND newsalt='1' ORDER BY zeit DESC");
		while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($inhalte["von"] != "0") {
				$out = hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte["von"], id2name($inhalte["von"]));
			} else {
				$out = "Administrator";
			}
			$ntext = parsetxt($inhalte["news"]);
			$ausgabe .= tr(
				td(0, "center", date("H:i:s (d.m.Y)", $inhalte["zeit"]))
				 . td(0, "center", $out)
				 . td(0, "center", censor($inhalte["betreff"]))
				 . td(0, "center", input("checkbox", "n" . $inhalte["id"], 1)));
			$ausgabe .= tr(td(4, "nachricht", $ntext));
			if ($inhalte["von"] != "0") {
				$ausgabe .= tr(td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=meld&n=" . $inhalte["id"], "Melden")) . td(3, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=new&empf=" . str_replace(" ", "%20", id2name($inhalte["von"])) . "&betreff=Re:" . str_replace(" ", "%20", str_replace("Re:", "", $inhalte["betreff"])) . "&nid=" . $inhalte["id"], "Antworten")));
			}
			$ausgabe .= tr(td(4, "hr", "<hr>"));
		}
	}
} elseif ($w == 5) {
	$ausgabe .= tr(td(4, "head", "Gesendete Nachrichten" . input("hidden", "w", $w)));
	$ausgabe .= $ausgabezv;
	$ausgabe .= tr(td(0, "navi", "Zeit") . td(0, "navi", "Empfänger") . td(0, "navi", "Betreff"));
	$oldbetreff = "";
	$result = mysql_query("SELECT * FROM genesis_news WHERE zeit>='$start' and zeit<='$ende' and von='$sid' AND typ='news' ORDER BY zeit DESC");
	while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if (($inhalte["betreff"] != $oldbetreff && strpos(" " . $inhalte["betreff"], "[Symbiosen Rundschreiben]") == 1) || strpos(" " . $inhalte["betreff"], "[Symbiosen Rundschreiben]") != 1) {
			if (strpos(" " . $inhalte["betreff"], "[Symbiosen Rundschreiben]") != 1) {
				$out = hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte["an"], id2name($inhalte["an"]));
			} else {
				$out = "Symbiose";
			}
			$ntext = parsetxt($inhalte["news"]);
			$ausgabe .= tr(
				td(0, "center", date("H:i:s (d.m.Y)", $inhalte["zeit"]))
				 . td(0, "center", $out)
				 . td(0, "center", censor($inhalte["betreff"])));
			$ausgabe .= tr(td(3, "nachricht", $ntext));
			$ausgabe .= tr(td(3, "hr", "<hr>"));
			$oldbetreff = $inhalte["betreff"];
		}
	}
} elseif ($w == "rd" && $n != "") {
	$result = mysql_query("SELECT * FROM genesis_news WHERE id='$n' AND an='$sid' AND typ='news'");
	if ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($inhalte["von"] != "0") {
			$out = hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte["von"], id2name($inhalte["von"]));
		} else {
			$out = "Administrator";
		}
		$ausgabe .= tr(td(2, "head", censor($inhalte["betreff"])));
		$ausgabe .= tr(td(2, "center", "Von: $out" . " - " . date("d.m.Y (H:i:s)", $inhalte["zeit"])));
		$ausgabe .= tr(td(2, "hr", "<hr>"));
		$ausgabe .= tr(td(2, "nachricht", parsetxt($inhalte["news"])));
		if ($inhalte["von"] != "0") {
			$out = hlink("", "game.php?id=$id&b=$b&nav=$nav&w=meld&n=" . $inhalte["id"], "Melden");
			$out2 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=new&empf=" . str_replace(" ", "%20", id2name($inhalte["von"])) . "&betreff=Re:" . str_replace(" ", "%20", str_replace("Re:", "", $inhalte["betreff"])) . "&nid=$n", "Antworten");
		} else {
			$out = "&nbsp;";
			$out2 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . hlink("", "game.php?id=$id&b=$b&nav=$nav&w=new&empf=Administrator&betreff=Re:RM&nid=$n", "Admin anschreiben");
		}
		$ausgabe .= tr(td(2, "hr", "<hr>"));
		$ausgabe .= tr(td(0, "navi", $out) . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&w=clr&n=" . $inhalte["id"], "Löschen") . $out2));
	}
	mysql_query("UPDATE genesis_news SET newsalt='1' WHERE id='$n' AND an='$sid' AND typ='news' AND newsalt='0'");
	mysql_query("UPDATE genesis_spieler SET nachrichten=nachrichten-1 WHERE id='$sid'");
} elseif ($w == "new") {
	if ($nid > 0) {
		$result = mysql_query("SELECT news FROM genesis_news WHERE id='$nid' AND an='$sid' AND typ='news'");
		$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
		$ausgabe .= tr(td(2, "head", "Antwort schreiben"));
	} else {
		$ausgabe .= tr(td(2, "head", "Neue Nachricht schreiben"));
	}
	if ($nid > 0) {
		$news = "\n\n$empf schrieb:\n>> " . str_replace("\n", "\n>> ", $inhalte["news"]);
	} else {
		$news = "";
	}
	$ausgabe .= tr(td(0, "center", "Empfänger") . td(0, "center", input("text", "empf", $empf) . " oder " . input("koord", "knx", "") . " : " . input("koord", "kny", "") . " : " . input("koord", "knz", "")));
	$ausgabe .= tr(td(0, "center", "Betreff") . td(0, "center", input("symbtext", "betreff", $betreff)));
	$ausgabe .= tr(td(2, "center", "<textarea name=\"news\" rows=10 cols=55>$news</textarea>"));
	$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Nachricht versenden")));
}
if ($w > 0 && $w < 5) {
	$ausgabe .= tr(td(4, "center", "<select name=n><option value=0>Alle</option><option value=1 selected>Markierte</option><option value=2>Nicht Markierte</option></select>&nbsp;&nbsp;&nbsp;" . input("submit", "aktion", "Nachrichten löschen")));
}

$ausgabe .= "</table>\n</form>\n$fehler";

unset($inhalte2, $result2, $ausgabezv, $inhalte, $result, $start, $ende, $n, $zv, $w, $nid, $empf, $out, $out2, $news, $ntext, $fil, $f1, $f2, $f3, $f4);

?>