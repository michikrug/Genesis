<?php

include_once "parser.inc.php";

$aid = $inhalte_s["alli"];

$s = isset($_REQUEST["s"]) ? $_REQUEST["s"] : NULL;
$a = isset($_REQUEST["a"]) ? $_REQUEST["a"] : NULL;
$n = isset($_REQUEST["n"]) ? $_REQUEST["n"] : NULL;
$symb = isset($_REQUEST["symb"]) ? $_REQUEST["symb"] : NULL;
$typ = isset($_REQUEST["typ"]) ? $_REQUEST["typ"] : NULL;
$ts = isset($_REQUEST["ts"]) ? $_REQUEST["ts"] : NULL;
$ok = isset($_REQUEST["ok"]) ? $_REQUEST["ok"] : NULL;
$dat = isset($_REQUEST["dat"]) ? $_REQUEST["dat"] : NULL;
$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
$newsbetr = isset($_REQUEST["newsbetr"]) ? $_REQUEST["newsbetr"] : NULL;
$newstxt = isset($_REQUEST["newstxt"]) ? $_REQUEST["newstxt"] : NULL;
$bewerb_text = isset($_REQUEST["bewerb_text"]) ? $_REQUEST["bewerb_text"] : NULL;
$symb_name = isset($_REQUEST["symb_name"]) ? $_REQUEST["symb_name"] : NULL;
$symb_text = isset($_REQUEST["symb_text"]) ? $_REQUEST["symb_text"] : NULL;
$symb_tag = isset($_REQUEST["symb_tag"]) ? $_REQUEST["symb_tag"] : NULL;
$symb_url = isset($_REQUEST["symb_url"]) ? $_REQUEST["symb_url"] : NULL;
$symb_forum = isset($_REQUEST["symb_forum"]) ? $_REQUEST["symb_forum"] : NULL;

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= table(600, "bg");
$ausgabe .= tr(td(7, "head", "Symbiose"));

if ($aid == 0) {
	$result_w = mysql_query("SELECT id,an FROM genesis_news WHERE von='$sid' AND typ='bewerb'");
	if ($inhalte_w = mysql_fetch_array($result_w, MYSQL_ASSOC)) {
		$result_symb = mysql_query("SELECT id,tag FROM genesis_allianzen WHERE id='" . $inhalte_w["an"] . "'");
		$inhalte_symb = mysql_fetch_array($result_symb, MYSQL_ASSOC);
		$symb_tag = $inhalte_symb["tag"];
		if ($a == "abbr") {
			mysql_query("DELETE FROM genesis_news WHERE id='" . $inhalte_w["id"] . "'");
			$fehler = "<br>Du hast die Bewerbung bei [$symb_tag] zurückgezogen!";
			$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav&fehler=$fehler'\",1000)</script>\n";
		}
		$ausgabe .= tr(td(0, "center", "Du hast dich bei der Symbiose [" . hlink("", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte_symb["id"], $inhalte_symb["tag"]) . "] beworben."));
		$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&nav=$nav&a=abbr", "Bewerbung zurückziehen")));
	} else {
		if ($a == "neu") {
			$ausgabe .= tr(td(0, "center", "Tag (ohne [])") . td(0, "center", input("text", "symb_tag", "")));
			$ausgabe .= tr(td(0, "center", "Name") . td(0, "center", input("text", "symb_name", "")));
			$ausgabe .= tr(td(0, "center", "Homepage (URL)") . td(0, "center", input("text", "symb_url", "")));
			$ausgabe .= tr(td(0, "center", "Forum (URL)") . td(0, "center", input("text", "symb_forum", "")));
			$ausgabe .= tr(td(0, "center", "Beschreibung") . td(0, "center", "<textarea name=symb_text cols=60 rows=10></textarea>"));
			$ausgabe .= tr(td(2, "center", input("submit", "a", "Gründen")));
		} elseif ($a == "such") {
			$ausgabe .= input("hidden", "a", "such");
			$ausgabe .= tr(td(2, "navi", "Symbiose suchen"));
			$ausgabe .= tr(td(0, "center", "TAG (ohne [])") . td(0, "center", input("text", "symb", "")));
			$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Suchen")));

			if ($aktion == "Suchen" && $symb != "") {
				$ausgabe .= tr(td(2, "center", "<br>"));
				$result_symb = mysql_query("SELECT id, tag, name FROM genesis_allianzen where tag like '%$symb%'");
				if (mysql_num_rows($result_symb) > 0) {
					while ($inhalte_symb = mysql_fetch_array($result_symb, MYSQL_ASSOC)) {
						$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte_symb["id"], "[" . $inhalte_symb["tag"] . "] ") . $inhalte_symb["name"]));
					}
				} else {
					$ausgabe .= tr(td(2, "center", "keine entsprechenden Symbiosen gefunden"));
				}
			}
		} elseif ($a == "bewerb" && $symb != "") {
			$result_symb = mysql_query("SELECT id,tag FROM genesis_allianzen WHERE id='$symb'");
			if ($inhalte_symb = mysql_fetch_array($result_symb, MYSQL_ASSOC)) {
				$symb_tag = $inhalte_symb["tag"];
				if ($aktion == "Bewerben" && $bewerb_text != "") {
					$zeit = time();
					mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news) VALUES ('$sid','$symb','$zeit','bewerb','$bewerb_text')");
					$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav'\",1000)</script>\n";
				} else {
					$ausgabe .= input("hidden", "a", "bewerb");
					$ausgabe .= input("hidden", "symb", "$symb");
					$ausgabe .= tr(td(2, "navi", "Bewerbung bei [" . hlink("", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte_symb["id"], $inhalte_symb["tag"]) . "]"));
					$ausgabe .= tr(td(2, "center", "<textarea name=bewerb_text cols=60 rows=10>(Bewerbungstext)</textarea>"));
					$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Bewerben")));
				}
			}
		} elseif ($a == "Gründen") {
			$result_symb2 = mysql_query("SELECT id FROM genesis_allianzen WHERE tag='$symb_tag'");
			if (!$inhalte_symb2 = mysql_fetch_array($result_symb2, MYSQL_ASSOC) && $symb_name != "" && $symb_tag != "") {
				$symb_name = sauber($symb_name);
				$symb_text = sauber($symb_text);
				$symb_tag = sauber($symb_tag);
				if (substr($symb_url, 0, 7) != "http://" && $symb_url != "") {
					$symb_url = "http://" . $symb_url;
				}
				if (substr($symb_forum, 0, 7) != "http://" && $symb_forum != "") {
					$symb_forum = "http://" . $symb_forum;
				}
				mysql_query("INSERT INTO genesis_allianzen (tag, name, beschreibung, url, forum) VALUES ('$symb_tag', '$symb_name', '$symb_text', '$symb_url', '$symb_forum')");
				$aid = mysql_insert_id();
				mysql_query("UPDATE genesis_spieler SET alli='$aid', alli_rang='0' WHERE id='$sid'");
				mysql_query("INSERT INTO genesis_raenge (alli, rang, name, rechte) VALUES ('$aid', '0', 'Gründer', '1111111111')");
				mysql_query("INSERT INTO genesis_raenge (alli, rang, name, rechte) VALUES ('$aid', '1', 'Mitglied', '0000000000')");
				$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav'\",1000)</script>\n";
			} else {
				$fehler .= "<br>Symbiosen-TAG schon vergeben, bitte einen anderen wählen!";
			}
		} else {
			$ausgabe .= tr(td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=neu", "Symbiose gründen")));
			$ausgabe .= tr(td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=such", "Symbiose suchen")));
		}
	}
} else {

	$result_p = mysql_query("SELECT * FROM genesis_politik WHERE bis<'" . time() . "' and bis>'0' and (alli1='$aid' OR (alli2='$aid' and (typ='5' or (typ='7' and accept='1'))))");
	while ($inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC)) {
		$zusatz = 0;
		$result_a1 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli1"] . "'");
		$inhalte_a1 = mysql_fetch_array($result_a1, MYSQL_ASSOC);
		$result_a2 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli2"] . "'");
		$inhalte_a2 = mysql_fetch_array($result_a2, MYSQL_ASSOC);
		if ($inhalte_p["typ"] == 5) {
			for ($i = 1; $i <= 7; $i++) {
				eval("unset(\$prod" . $i . "1g,\$prod" . $i . "1v,\$prod" . $i . "2g,\$prod" . $i . "2v);");
			}
			unset($ress11, $ress21, $ress31, $ress41, $ress51, $ress12, $ress22, $ress32, $ress42, $ress52, $kp1, $kp2, $kp1a, $kp2a);
			$qry = "SELECT t1.id,t1.name,t1.prod1,t1.prod2,t1.prod3,t1.prod4,t1.prod5,t1.prod6,t1.prod7,t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t3.prod1,t3.prod2,t3.prod3,t3.prod4,t3.prod5,t3.prod6,t3.prod7,t3.vert1,t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.vertv1,t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5,t3.name,t1.kp,t3.kp FROM genesis_att t1 LEFT JOIN genesis_berichte t2 USING(id) LEFT JOIN genesis_deff t3 ON t3.id=t1.id WHERE t1.alli='[" . $inhalte_a1["tag"] . "]' and t3.alli='[" . $inhalte_a2["tag"] . "]' and t2.typ='1' and t2.zeit>'" . $inhalte_p["von"] . "' and t2.zeit<'" . time() . "' order by t1.id, t1.name";
			$result = mysql_query($qry);
			unset($namea, $nameb, $id, $anz1);
			while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
				$anz1++;
				if ($id != $berichte[0]) {
					for ($i = 1; $i <= 7; $i++) {
						$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
						$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
						$kp2a += $berichte[($i + 8)] * $inhalte[0];
					}
					$kp1a += $berichte[38];
				} elseif ($id == $berichte[0] && $namea != $berichte[1]) {
					for ($i = 1; $i <= 7; $i++) {
						$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
						$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
						$kp2a += $berichte[($i + 8)] * $inhalte[0];
					}
					$kp1a += $berichte[38];
				}
				$id = $berichte[0];
				$namea = $berichte[1];
				$nameb = $berichte[37];
			}
			$qry = "SELECT t1.id,t1.name,t1.prod1,t1.prod2,t1.prod3,t1.prod4,t1.prod5,t1.prod6,t1.prod7,t1.prodv1,t1.prodv2,t1.prodv3,t1.prodv4,t1.prodv5,t1.prodv6,t1.prodv7,t3.prod1,t3.prod2,t3.prod3,t3.prod4,t3.prod5,t3.prod6,t3.prod7,t3.vert1,t3.prodv1,t3.prodv2,t3.prodv3,t3.prodv4,t3.prodv5,t3.prodv6,t3.prodv7,t3.vertv1,t1.ress1,t1.ress2,t1.ress3,t1.ress4,t1.ress5,t3.name,t1.kp,t3.kp FROM genesis_att t1 LEFT JOIN genesis_berichte t2 USING(id) LEFT JOIN genesis_deff t3 ON t3.id=t1.id WHERE t2.typ='1' and t2.zeit>'" . $inhalte_p["von"] . "' and t2.zeit<'" . time() . "' and t3.alli='[" . $inhalte_a1["tag"] . "]' and t1.alli='[" . $inhalte_a2["tag"] . "]' order by t1.id, t1.name";
			$result = mysql_query($qry);
			unset($namea, $nameb, $id, $anz2);
			while ($berichte = mysql_fetch_array($result, MYSQL_NUM)) {
				$anz2++;
				if ($id != $berichte[0]) {
					for ($i = 1; $i <= 7; $i++) {
						$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
						$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
						$kp1a += $berichte[($i + 8)] * $inhalte[0];
					}
					$kp2a += $berichte[38];
				} elseif ($id == $berichte[0] && $namea != $berichte[1]) {
					for ($i = 1; $i <= 7; $i++) {
						$result2 = mysql_query("SELECT wert2 FROM genesis_infos WHERE typ='prod$i'");
						$inhalte = mysql_fetch_array($result2, MYSQL_NUM);
						$kp1a += $berichte[($i + 8)] * $inhalte[0];
					}
					$kp2a += $berichte[38];
				}
				$id = $berichte[0];
				$namea = $berichte[1];
				$nameb = $berichte[37];
			}
			if (($kp1a - $kp2a > ($kp1a / 100 * 5)) || ($kp1a > $kp2a && $anz2 == 0)) {
				$zusatz = 1;
			} elseif (($kp2a - $kp1a > ($kp2a / 100 * 5)) || ($kp2a > $k1pa && $anz1 == 0)) {
				$zusatz = 2;
			} else {
				$zusatz = 3;
			}
			for ($i = 1; $i <= 7; $i++) {
				eval("unset(\$prod" . $i . "1g,\$prod" . $i . "1v,\$prod" . $i . "2g,\$prod" . $i . "2v);");
			}
			unset($ress11, $ress21, $ress31, $ress41, $ress51, $ress12, $ress22, $ress32, $ress42, $ress52, $kp1, $kp2, $kp1a, $kp2a);
		}

		mysql_query("DELETE FROM genesis_politik WHERE id='" . $inhalte_p["id"] . "'");
		if (($inhalte_p["typ"] == 7 && $inhalte_p["accept"] == 1) || $inhalte_p["typ"] != 7) {
			mysql_query("INSERT INTO genesis_history (alli1,alli2,typ,zeit,zusatz) VALUES ('" . $inhalte_a1["tag"] . "','" . $inhalte_a2["tag"] . "','" . ($inhalte_p["typ"] + 1) . "','" . time() . "','$zusatz')");
		}
	}

	$result_symb = mysql_query("SELECT * FROM genesis_allianzen WHERE id='$aid'");
	$inhalte_symb = mysql_fetch_array($result_symb, MYSQL_ASSOC);
	$symb = $inhalte_symb["tag"];
	$r = $inhalte_s["alli_rang"];
	$result_rang = mysql_query("SELECT * FROM genesis_raenge WHERE alli='$aid' and rang='$r'");
	$inhalte_rang = mysql_fetch_array($result_rang, MYSQL_ASSOC);
	$arecht = preg_split('//', $inhalte_rang["rechte"], -1, PREG_SPLIT_NO_EMPTY);

	$result_mem = mysql_query("SELECT id FROM genesis_spieler WHERE alli='$aid' and alli_rang='0'");
	$ganz = mysql_num_rows($result_mem);

	$result_w = mysql_query("SELECT id FROM genesis_news WHERE an='$aid' AND typ='bewerb'");
	$bewerbungen = mysql_num_rows($result_w);

	if ($a == "weg") {
		if ($ok != 1 && ($r != 0 || ($r == 0 && $ganz > 1))) {
			$ausgabe .= tr(td(0, "center", "Willst du wirklich die Symbiose [$symb] verlassen?") . td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=weg&ok=1", "Ja")));
		} elseif ($ok != 1 && ($r == 0 && $ganz == 1 && $inhalte_symb["anz"] > 1)) {
			$ausgabe .= tr(td(2, "center", "Bitte zuerst einen neuen Gründer benennen."));
		} elseif ($ok == 1) {
			$anz = $inhalte_symb["anz"] - 1;
			mysql_query("UPDATE genesis_allianzen SET anz='$anz' WHERE id='$aid'");
			mysql_query("UPDATE genesis_spieler SET alli='0', alli_rang='0' WHERE id='$sid'");
			mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news,newsalt,meldung) VALUES ('$sid','$aid','" . time() . "','alli_news','Der Spieler $name hat die Symbiose verlassen.','1','0')");
			$fehler .= "<br>Du bist nun kein Mitglied der Symbiose [$symb] mehr!\n";
			$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav&fehler=$fehler'\",1000)</script>\n";
		}
	} elseif ($aktion == "Nachrichten löschen" && $n >= 0 && ($arecht[0] == 1 || $r == 0)) {
		$result2 = mysql_query("SELECT id FROM genesis_news WHERE an='$aid' AND typ='alli_news'");
		while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$nclr = $_REQUEST["n" . $inhalte2["id"]];
			if (($nclr == 1 && $n == 1) || $n == 0 || ($nclr == 0 && $n == 2)) {
				mysql_query("DELETE FROM genesis_news WHERE id='" . $inhalte2["id"] . "'");
			}
		}
		$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav'\",1000)</script>\n";
	} elseif ($a == "memnews" && (($arecht[5] == 1) || $r == 0)) {
		if ($aktion != "Absenden") {
			$ausgabe .= input("hidden", "a", "memnews");
			$ausgabe .= tr(td(2, "head", "Neues Rundschreiben"));
			$ausgabe .= tr(td(2, "center", "Betreff: " . input("text", "newsbetr", "")));
			$ausgabe .= tr(td(2, "center", "<textarea name=newstxt cols=60 rows=10></textarea>"));
			$ausgabe .= tr(td(0, "right", input("submit", "aktion", "Absenden")));
		} elseif ($aktion == "Absenden" && $newsbetr != "" && $newstxt != "") {
			$zeit = time();
			$newsbetr = sauber($newsbetr);
			$newstxt = sauber($newstxt);
			$result_mem = mysql_query("SELECT id FROM genesis_spieler WHERE alli='$aid'");
			while ($inhalte_mem = mysql_fetch_array($result_mem, MYSQL_ASSOC)) {
				mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('$sid','" . $inhalte_mem["id"] . "','$zeit','news','[Symbiosen Rundschreiben] $newsbetr','$newstxt')");
				mysql_query("UPDATE genesis_spieler SET nachrichten=nachrichten+1 WHERE id='" . $inhalte_mem["id"] . "'");
			}
			$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav'\",1000)</script>\n";
		} else {
			$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav&a=news'\",1000)</script>\n";
		}
	} elseif ($a == "news" && ($arecht[2] == 1 || $r == 0)) {
		if ($aktion != "Eintragen") {
			$ausgabe .= input("hidden", "a", "news");
			$ausgabe .= tr(td(0, "head", "Neue Nachricht"));
			$ausgabe .= tr(td(0, "center", "<textarea name=newstxt cols=60 rows=10></textarea>"));
			$ausgabe .= tr(td(0, "center", input("submit", "aktion", "Eintragen")));
		} elseif ($aktion == "Eintragen" && $newstxt != "") {
			$zeit = time();
			$newstxt = sauber($newstxt);
			mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,news) VALUES ('$sid','$aid','$zeit','alli_news','$newstxt')");
			$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav'\",1000)</script>\n";
		} else {
			$ausgabe .= "<script type=\"text/javascript\">window.setTimeout(\"window.location.href='game.php?id=$id&b=$b&nav=$nav&a=news'\",1000)</script>\n";
		}
	} elseif ($a == "mem") {
		if ($arecht[4] == 1 || $r == 0) {
			$zeit = time();
			$handle = opendir("images/graphs");
			while (false !== ($file = readdir($handle))) {
				if (filetype("images/graphs/$file") == "file" && filemtime("images/graphs/$file") < (time()-60)) {
					unlink("images/graphs/$file");
				}
			}
			if ($inhalte_symb["punktek"] > 0) {
				include("class_eq_pie.inc.php");
				$eq_pie = new eq_pie;
				$data[0][0] = "Ausbau";
				$data[0][1] = $inhalte_symb["punktek"];
				$data[0][2] = "#0000B6";
				if ($inhalte_symb["punktef"] > 0) {
					$data[1][0] = "Evolution";
					$data[1][1] = $inhalte_symb["punktef"];
					$data[1][2] = "#009000";
				}
				if ($inhalte_symb["punktem"] > 0) {
					$data[2][0] = "Zellen";
					$data[2][1] = $inhalte_symb["punktem"];
					$data[2][2] = "#919100";
				}
				if ($inhalte_symb["kampfpkt"] > 0) {
					$data[3][0] = "Kampfpkt.";
					$data[3][1] = round($inhalte_symb["kampfpkt"] * 0.3, 0);
					$data[3][2] = "#D2691E";
				}
				$eq_pie->MakePie('images/graphs/' . $zeit . '.png', '150', '110', '10', '#000000' , $data, '1');
			}
			$ausgabe .= tr(td(7, "head", "Mitglieder"));
			$ausgabe .= tr(
				td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=0", "Name"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=1", "Rang"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=2", "Punkte"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=3", "Zellpunkte"))
				 . td(0, "navi", "Diff " . hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=5", "G") . "/" . hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=6", "Z"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=4", "Online"))
				);
			if ($ts == "" || $ts == 0) {
				$ord = "name";
			}
			if ($ts == 1) {
				$ord = "alli_rang";
			}
			if ($ts == 2) {
				$ord = "punkte DESC";
			}
			if ($ts == 3) {
				$ord = "punktem DESC";
			}
			if ($ts == 4) {
				$ord = "log DESC";
			}
			if ($ts == 5) {
				$ord = " (punkte-punktea) DESC";
			}
			if ($ts == 6) {
				$ord = " (punktem-punktema) DESC";
			}
			$result_mem = mysql_query("SELECT id,name,alli_rang,punkte,punktem,(punkte-punktea),(punktem-punktema),log,sessid,urlaub,basis1 FROM genesis_spieler WHERE alli='$aid' ORDER BY $ord");
			while ($inhalte_mem = mysql_fetch_array($result_mem, MYSQL_NUM)) {
				$result_ra = mysql_query("SELECT name FROM genesis_raenge WHERE alli='$aid' and rang='" . $inhalte_mem[2] . "'");
				$inhalte_ra = mysql_fetch_array($result_ra, MYSQL_ASSOC);
				unset($class, $class2, $class3);
				$um = "";
				if ($inhalte_mem[8] != "0") {
					$daten = mysql_query("SELECT zeit FROM genesis_wio WHERE seite like '%" . $inhalte_mem[8] . "%'");
					if (mysql_fetch_array($daten, MYSQL_ASSOC)) {
						$class3 = " class=\"ja\"";
					}
				} else {
					$class3 = " class=\"nein\"";
				}
				if ($inhalte_mem[9] > time()) {
					$um = " [U]";
				}
				if ($inhalte_mem[5] > 0) {
					$class = " class='ja'";
				} elseif ($inhalte_mem[5] < 0) {
					$class = " class='nein'";
				}
				if ($inhalte_mem[6] > 0) {
					$class2 = " class='ja'";
				} elseif ($inhalte_mem[6] < 0) {
					$class2 = " class='nein'";
				}
				$out = td(0, "center", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte_mem[0], $inhalte_mem[1]) . " <i>(" . $inhalte_mem[10] . ")</i>$um");
				$out .= td(0, "center", $inhalte_ra["name"]);
				$out .= td(0, "center", "<p$class>" . format($inhalte_mem[3]) . "</p>");
				$out .= td(0, "center", "<p$class2>" . format($inhalte_mem[4]) . "</p>");
				$out .= td(0, "center", format($inhalte_mem[5]) . " / " . format($inhalte_mem[6]));
				$out .= td(0, "center", "<p$class3>" . date("d.m.Y (H:i)", $inhalte_mem[7]) . "</p>");
				$ausgabe .= tr($out);
			}
			unset($inhalte_mem, $result_mem, $class, $class2, $class3, $um);
			$ausgabe .= tr(td(7, "hr", "<hr>"));
			$ausgabe .= tr(td(7, "center", "(Grün = online/eingeloggt, Weiss = offline/nicht ausgeloggt, Rot = ausgeloggt)"));
			if ($arecht[1] == 1 || $r == 0) {
				$ausgabe .= tr(td(7, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=update", "Punkte aktualisieren")));
			}
			$ausgabe .= tr(td(7, "head", "Punkteverteilung"));
			if ($inhalte_symb["punktek"] > 0) {
				$ausgabe .= tr(td(7, "center", "<img src=\"images/graphs/$zeit.png\" border=\"0\">"));
			}
		} else {
			$ausgabe .= tr(td(5, "head", "Mitglieder"));
			$ausgabe .= tr(
				td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=0", "Name"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=1", "Rang"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=2", "Punkte"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=3", "Online"))
				);
			if ($ts == "" || $ts == 0) {
				$ord = "name";
			}
			if ($ts == 1) {
				$ord = "alli_rang";
			}
			if ($ts == 2) {
				$ord = "punkte DESC";
			}
			if ($ts == 3) {
				$ord = "log DESC";
			}
			$result_mem = mysql_query("SELECT id,name,alli_rang,basis1,punkte,punktem,punktea,punktema,log,sessid FROM genesis_spieler WHERE alli='$aid' ORDER BY $ord");
			while ($inhalte_mem = mysql_fetch_array($result_mem, MYSQL_ASSOC)) {
				$result_ra = mysql_query("SELECT name FROM genesis_raenge WHERE alli='$aid' and rang='" . $inhalte_mem["alli_rang"] . "'");
				$inhalte_ra = mysql_fetch_array($result_ra, MYSQL_ASSOC);
				unset($class, $class3);
				if ($inhalte_mem["sessid"] != "0") {
					$daten = mysql_query("SELECT zeit FROM genesis_wio WHERE seite like '%" . $inhalte_mem["sessid"] . "%'");
					if (mysql_fetch_array($daten, MYSQL_ASSOC)) {
						$class3 = " class=\"ja\"";
					}
				} else {
					$class3 = " class=\"nein\"";
				}
				if ($inhalte_mem["punktea"] + $inhalte_mem["punktema"] < $inhalte_mem["punkte"] + $inhalte_mem["punktem"]) {
					$class = " class=\"ja\"";
				}
				if ($inhalte_mem["punktea"] + $inhalte_mem["punktema"] > $inhalte_mem["punkte"] + $inhalte_mem["punktem"]) {
					$class = " class=\"nein\"";
				}
				$out = td(0, "center", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte_mem["id"], $inhalte_mem["name"]) . " <i>(" . $inhalte_mem["basis1"] . ")</i>$um");
				$out .= td(0, "center", $inhalte_ra["name"]);
				$out .= td(0, "center", "<p$class>" . format($inhalte_mem["punkte"]) . "</p>");
				$out .= td(0, "center", "<p$class3>" . date("d.m.Y (H:i:s)", $inhalte_mem["log"]) . "</p>");
				$ausgabe .= tr($out);
			}
			unset($inhalte_mem, $result_mem, $class, $class3);

			$ausgabe .= tr(td(7, "hr", "<hr>"));
			$ausgabe .= tr(td(7, "center", "(Grün = online/eingeloggt, Weiss = offline/nicht ausgeloggt, Rot = ausgeloggt)"));
			if ($arecht[1] == 1 || $r == 0) {
				$ausgabe .= tr(td(7, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=update", "Punkte aktualisieren")));
			}
		}
	} elseif ($a == "stats") {
		include("kriegsstat.inc.php");
	} elseif ($a == "symbstats") {
		include("symbstat.inc.php");
	} elseif ($a == "berichte" && ($arecht[4] == 1 || $r == 0)) {
		$ausgabe .= tr(td(5, "head", "Berichte"));
		$ausgabe .= input("hidden", "a", "berichte");
		if ($typ == "") {
			$typ = 3;
			$aktion = "Anzeigen";
		}
		$out2 = "Typ: <select name=\"typ\"><option value=1";
		if ($typ == 1) {
			$out2 .= " selected";
		}
		$out2 .= ">Angriffe</option><option value=2";
		if ($typ == 2) {
			$out2 .= " selected";
		}
		$out2 .= ">Spionagen</option><option value=3";
		if ($typ == 3) {
			$out2 .= " selected";
		}
		$out2 .= ">Verteidigungen</option></select>";
		$ausgabe .= tr(td(0, "center", $out2));
		$out = "Tag: <select name=\"dat\">";
		for ($i = 0; $i <= 5; $i++) {
			$out .= "<option value=$i";
			if ($dat == $i) {
				$out .= " selected";
			}
			$out .= ">" . date("d.m.Y", (time() - ($i * 86400))) . "</option>";
		}
		$out .= "</select>";
		$ausgabe .= tr(td(0, "center", $out));
		$ausgabe .= tr(td(0, "center", input("submit", "aktion", "Anzeigen")));
		if ($aktion == "Anzeigen" && $typ != "" && $dat >= 0 && $dat < 6) {
			$start = mktime(0, 0, 0, date("m", time() - ($dat * 86400)), date("d", time() - ($dat * 86400)), date("Y", time() - ($dat * 86400)));
			$ende = mktime(0, 0, 0, date("m", time() - ($dat * 86400)), date("d", time() - ($dat * 86400)), date("Y", time() - ($dat * 86400))) + 86399;
			if ($typ == 1 || $typ == 2) {
				if ($typ == 1) {
					$ausgabe .= tr(td(5, "head", "Angriffsberichte vom " . date("d.m.Y", time() - ($dat * 86400))));
				}
				if ($typ == 2) {
					$ausgabe .= tr(td(5, "head", "Spionageberichte vom " . date("d.m.Y", time() - ($dat * 86400))));
				}
$result_ab = mysql_query("SELECT
b.id,b.koords as koordsd,b.zeit,a.name,a.koords as koordsa
FROM genesis_att AS a
LEFT JOIN genesis_berichte AS b USING(id)
WHERE a.alli='[" . $inhalte_symb["tag"] . "]'
AND b.typ='$typ' AND b.zeit>='$start' AND b.zeit<='$ende'
ORDER BY b.zeit DESC");
				while ($inhalte_ab = mysql_fetch_array($result_ab, MYSQL_ASSOC)) {
					$result_ab1 = mysql_query("SELECT id,name FROM genesis_spieler WHERE name='" . $inhalte_ab["name"] . "'");
					$inhalte_ab1 = mysql_fetch_array($result_ab1, MYSQL_ASSOC);
					$result_ab2 = mysql_query("SELECT id,name FROM genesis_spieler WHERE basis1='" . $inhalte_ab["koordsd"] . "' OR basis2='" . $inhalte_ab["koordsd"] . "'");
					$inhalte_ab2 = mysql_fetch_array($result_ab2, MYSQL_ASSOC);
					if ($typ == 1) {
						$ausgabe .= tr(td(5, "center", date("d.m.Y (H:i:s)", $inhalte_ab["zeit"]) . " - " . hlink("new", "bericht.php?id=" . $inhalte_ab["id"], "Angriff") . " von " . hlink("", "game.php?id=$id&nav=info&t=spieler" . $inhalte_ab1["id"], $inhalte_ab1["name"]) . " (" . $inhalte_ab["koordsa"] . ")" . " auf " . hlink("", "game.php?id=$id&nav=info&t=spieler" . $inhalte_ab2["id"], $inhalte_ab2["name"]) . " (" . $inhalte_ab["koordsd"] . ")"));
					}
					if ($typ == 2) {
						$ausgabe .= tr(td(5, "center", date("d.m.Y (H:i:s)", $inhalte_ab["zeit"]) . " - " . hlink("new", "bericht.php?id=" . $inhalte_ab["id"], "Spionage") . " von " . hlink("", "game.php?id=$id&nav=info&t=spieler" . $inhalte_ab2["id"], $inhalte_ab2["name"]) . " (" . $inhalte_ab["koordsd"] . ")" . " durch " . hlink("", "game.php?id=$id&nav=info&t=spieler" . $inhalte_ab1["id"], $inhalte_ab1["name"]) . " (" . $inhalte_ab["koordsa"] . ")"));
					}
				}
			} elseif ($typ == 3) {
				$ausgabe .= tr(td(5, "head", "Verteidigungen vom " . date("d.m.Y", time() - ($dat * 86400))));
$result_ab = mysql_query("SELECT
b.id,b.koords,b.zeit,d.name
FROM genesis_deff AS d
LEFT JOIN genesis_berichte AS b USING(id)
WHERE d.alli='[" . $inhalte_symb["tag"] . "]'
AND b.typ='1' AND b.zeit>'$start' AND b.zeit<'$ende'
ORDER BY b.zeit DESC");
				while ($inhalte_ab = mysql_fetch_array($result_ab, MYSQL_ASSOC)) {
					$result_ab1 = mysql_query("SELECT id,name FROM genesis_spieler WHERE name='" . $inhalte_ab["name"] . "'");
					$inhalte_ab1 = mysql_fetch_array($result_ab1, MYSQL_ASSOC);
					$result_ab2 = mysql_query("SELECT a.name,a.koords,s.id FROM genesis_att AS a, genesis_spieler AS s  WHERE (s.basis1=a.koords or s.basis2=a.koords) and a.id='" . $inhalte_ab["id"] . "'");
					$inhalte_ab2 = mysql_fetch_array($result_ab2, MYSQL_ASSOC);
					$ausgabe .= tr(td(5, "center", date("d.m.Y (H:i:s)", $inhalte_ab["zeit"]) . " - " . hlink("new", "bericht.php?id=" . $inhalte_ab["id"], "Verteidigung") . " von " . hlink("", "game.php?id=$id&nav=info&t=spieler" . $inhalte_ab1["id"], $inhalte_ab1["name"]) . " (" . $inhalte_ab["koords"] . ") gegen " . hlink("", "game.php?id=$id&nav=info&t=spieler" . $inhalte_ab2["id"], $inhalte_ab2["name"]) . " (" . $inhalte_ab2["koords"] . ")"));
				}
			}
		}
	} else {
		if ($inhalte_symb["bild"] != "") {
			$out = "";
			if (strpos($inhalte_symb["bild"], "http://") === false) {
				list($width, $height, $type, $attr) = getimagesize($inhalte_symb["bild"]);
				if ($width > 590) {
					$out = " width=590";
				}
			}
			$ausgabe .= tr(td(2, "center", "<img src=\"" . $inhalte_symb["bild"] . "\"$out>"));
		}
		$ausgabe .= tr(td(0, "leftsymb", "Tag") . td(0, "right", "[" . $inhalte_symb["tag"] . "]"));
		$ausgabe .= tr(td(0, "leftsymb", "Name") . td(0, "right", $inhalte_symb["name"]));
		$ausgabe .= tr(td(0, "leftsymb", "Mitglieder") . td(0, "right", $inhalte_symb["anz"] . " (" . hlink("", "game.php?id=$id&b=$b&nav=$nav&a=mem&ts=2", "Anzeigen") . ")"));
		if ($bewerbungen > 0) {
			$ausgabe .= tr(td(0, "left", "Bewerbungen") . td(0, "right", "<p class=\"ja\">" . $bewerbungen . "</p>"));
		}
		$ausgabe .= tr(td(0, "leftsymb", "Punkte") . td(0, "right", format($inhalte_symb["punkte"])));
		$ausgabe .= tr(td(0, "leftsymb", "Zellpunkte") . td(0, "right", format($inhalte_symb["punktem"])));
		$ausgabe .= tr(td(0, "leftsymb", "Punkte Durchschnitt") . td(0, "right", format($inhalte_symb["punkted"])));
		$ausgabe .= tr(td(0, "leftsymb", "Kampfpunkte") . td(0, "right", format($inhalte_symb["kampfpkt"])));
		$ausgabe .= tr(td(0, "leftsymb", "Dein Rang") . td(0, "right", $inhalte_rang["name"]));
		if ($inhalte_symb["url"] != "") {
			$ausgabe .= tr(td(0, "leftsymb", "Homepage") . td(0, "right", hlink("new", $inhalte_symb["url"], $inhalte_symb["url"])));
		}
		if ($inhalte_symb["forum"] != "") {
			$ausgabe .= tr(td(0, "leftsymb", "Forum") . td(0, "right", hlink("new", $inhalte_symb["forum"], $inhalte_symb["forum"])));
		}
		$resultn = mysql_query("SELECT id FROM genesis_news WHERE an='$aid' AND typ='alli_news' and newsalt='0'");
		$anzn = mysql_num_rows($resultn);
		$resultn = mysql_query("SELECT id FROM genesis_news WHERE an='$aid' AND typ='alli_news' and newsalt='1'");
		$anzne = mysql_num_rows($resultn);
		$ausgabe .= tr(td(0, "leftsymb", "Nachrichten/Externe") . td(0, "right", hlink("", "game.php?id=$id&b=$b&nav=$nav" . "#nach", $anzn) . "/" . hlink("", "game.php?id=$id&b=$b&nav=$nav" . "#ext", $anzne)));

		$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&nav=info&t=alli" . $inhalte_symb["id"], "zur Beschreibung")));

		$ausgabe1 = tr(td(2, "head", "Aktive Verhältnisse"));
		$result_polit = mysql_query("SELECT * FROM genesis_politik WHERE alli1='$aid' OR (alli2='$aid' and (typ='5' or (typ='7' and accept='1'))) ORDER BY typ,von DESC");
		while ($inhalte_polit = mysql_fetch_array($result_polit, MYSQL_ASSOC)) {
			$zusatz = "";
			$ausgabe .= $ausgabe1;
			$ausgabe1 = "";
			if ($inhalte_polit["alli1"] == $aid) {
				$result_a = mysql_query("SELECT id,tag FROM genesis_allianzen where id='" . $inhalte_polit["alli2"] . "'");
				$inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC);
			} else {
				$result_a = mysql_query("SELECT id,tag FROM genesis_allianzen where id='" . $inhalte_polit["alli1"] . "'");
				$inhalte_a = mysql_fetch_array($result_a, MYSQL_ASSOC);
			}
			if ($inhalte_polit["bis"] > 0) {
				$pbis = " bis<br/>" . date("d.m.Y (H:i:s)", $inhalte_polit["bis"]);
			} else {
				$pbis = "";
			}
			if ($inhalte_polit["typ"] == 1) {
				$ptyp = "<font class=ja>Freundschaft</font> mit ";
			} elseif ($inhalte_polit["typ"] == 3) {
				$ptyp = "<font style=\"color:orange;\">Feindschaft</font> mit ";
			} elseif ($inhalte_polit["typ"] == 5) {
				$ptyp = "<font class=nein>Krieg</font> gegen ";
				$zusatz = " (" . hlink("", "game.php?id=$id&b=$b&nav=$nav&a=stats&pid=" . $inhalte_polit["id"], "Statistik") . ")";
			} elseif ($inhalte_polit["typ"] == 7 && $inhalte_polit["accept"] == 1) {
				$ptyp = "<font class=ja>Bündnis</font> mit ";
			} elseif ($inhalte_polit["typ"] == 7 && $inhalte_polit["accept"] == 0) {
				$ptyp = "<font class=nan>Bündnis (nicht bestätigt)</font> mit ";
			}
			$ausgabe .= tr(td(0, "leftsymb", date("d.m.Y (H:i:s)", $inhalte_polit["von"]) . $pbis) . td(0, "center", $ptyp . "[" . hlink("", "game.php?id=$id&b=$b&nav=info&t=alli" . $inhalte_a["id"], $inhalte_a["tag"]) . "]" . $zusatz));
		}
		$zusatz = "";
		$ausgabe .= tr(td(2, "center", "<hr/>"));
		if ($arecht[4] == 1 || $r == 0) {
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=berichte", "Berichte anzeigen")));
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=symbstats", "Symbiosen Statistik")));
		}
		if ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0) {
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin", "Symbiose administrieren")));
		}
		if ($ganz > 1 || $inhalte_symb["anz"] > 1) {
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=weg", "Symbiose verlassen")));
		}
		$ausgabe .= tr(td(2, "center", "&nbsp;"));
		if ($arecht[5] == 1 || $r == 0) {
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=memnews", "Neues Rundschreiben verfassen")));
		}
		if ($arecht[2] == 1 || $r == 0) {
			$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=news", "Neue Nachricht verfassen")));
		}
		$ausgabe .= tr(td(2, "center", "&nbsp;"));
		$ausgabe1 = tr(td(2, "head", "Interne Informationen"));
		if ($inhalte_symb["intern"]) {
			$ausgabe .= $ausgabe1;
			$ausgabe1 = "";
			$ausgabe .= tr(td(2, "center", parsetxt($inhalte_symb["intern"])));
		}
		if ($arecht[3] == 1 || $r == 0) {
			$ausgabe .= input("hidden", "a", "clr");
			$zeit = time();

			$ausgabe1 = tr(td(2, "head", "Externe Nachrichten<a name=\"ext\"></a>"));
			$result2 = mysql_query("SELECT id,zeit,von,news FROM genesis_news WHERE an='$aid' AND typ='alli_news' and newsalt='1' ORDER BY zeit DESC");
			while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$ausgabe .= $ausgabe1;
				$ausgabe1 = "";
				$result3 = mysql_query("SELECT id,name FROM genesis_spieler WHERE id='" . $inhalte2["von"] . "'");
				$inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);
				if (($arecht[0] == 1 || $r == 0)) $out1 = input("checkbox", "n" . $inhalte2["id"], "1");
				$out = td(0, "leftsymb", hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte2["von"], $inhalte3["name"]) . "<br>" . date("d.m.Y (H:i)", $inhalte2["zeit"]) . "<br>" . $out1);
				$out .= td(3, "center", parsetxt($inhalte2["news"]));
				$ausgabe .= tr($out);
				$ausgabe .= tr(td(2, "hr", "<hr/>"));
			}

			$ausgabe1 = tr(td(2, "head", "Nachrichten<a name=\"nach\"></a>"));
			$result2 = mysql_query("SELECT id,zeit,von,news FROM genesis_news WHERE an='$aid' AND typ='alli_news' and newsalt='0' ORDER BY zeit DESC");
			while ($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$ausgabe .= $ausgabe1;
				$ausgabe1 = "";
				$result3 = mysql_query("SELECT id,name FROM genesis_spieler WHERE id='" . $inhalte2["von"] . "'");
				$inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);
				if (($arecht[0] == 1 || $r == 0)) $out1 = input("checkbox", "n" . $inhalte2["id"], "1");
				$out = td(0, "leftsymb", hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte2["von"], $inhalte3["name"]) . "<br>" . date("d.m.Y (H:i)", $inhalte2["zeit"]) . "<br>" . $out1);
				$out .= td(3, "center", parsetxt($inhalte2["news"]));
				$ausgabe .= tr($out);
				$ausgabe .= tr(td(2, "hr", "<hr/>"));
			}
		}
		if (($arecht[0] == 1 || $r == 0) && ($anzn > 0 || $anzne > 0)) {
			$ausgabe .= tr(td(2, "center", "<select name=n><option value=0>Alle</option><option value=1 selected>Markierte</option><option value=2>Nicht Markierte</option></select>" . input("submit", "aktion", "Nachrichten löschen")));
		}
	}
}
unset ($idm, $fehler, $inhalte_b, $result_b, $bewerungen, $inhalte_symb, $result_symb, $inhalte2, $result2, $inhalte_symb2, $result_symb2, $inhalte_mem, $result_mem, $r, $a, $aktion, $symb, $symb_tag, $symb_text, $symb_bild, $symb_url, $symb_text);

$ausgabe .= "</table>\n</form>\n";

?>