<?php

include_once "parser.inc.php";

$aid = $inhalte_s["alli"];

$a = isset($_REQUEST["a"]) ? $_REQUEST["a"] : NULL;
$rid = isset($_REQUEST["rid"]) ? $_REQUEST["rid"] : NULL;
$n = isset($_REQUEST["n"]) ? $_REQUEST["n"] : NULL;
$s = isset($_REQUEST["s"]) ? $_REQUEST["s"] : NULL;
$t = isset($_REQUEST["s"]) ? $_REQUEST["t"] : NULL;
$rname = isset($_REQUEST["rname"]) ? $_REQUEST["rname"] : NULL;
$symb = isset($_REQUEST["symb"]) ? $_REQUEST["symb"] : NULL;
$typ = isset($_REQUEST["typ"]) ? $_REQUEST["typ"] : NULL;
$ts = isset($_REQUEST["ts"]) ? $_REQUEST["ts"] : NULL;
$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
$sicher = isset($_REQUEST["sicher"]) ? $_REQUEST["sicher"] : NULL;
$bewerb_text = isset($_REQUEST["bewerb_text"]) ? $_REQUEST["bewerb_text"] : NULL;
$symb_name = isset($_REQUEST["symb_name"]) ? $_REQUEST["symb_name"] : NULL;
$symb_text = isset($_REQUEST["symb_text"]) ? $_REQUEST["symb_text"] : NULL;
$symb_tag = isset($_REQUEST["symb_tag"]) ? $_REQUEST["symb_tag"] : NULL;
$symb_intern = isset($_REQUEST["symb_intern"]) ? $_REQUEST["symb_intern"] : NULL;
$symb_bild_up = isset($_REQUEST["symb_bild_up"]) ? $_REQUEST["symb_bild_up"] : NULL;
$symb_bild = isset($_REQUEST["symb_bild"]) ? $_REQUEST["symb_bild"] : NULL;
$symb_url = isset($_REQUEST["symb_url"]) ? $_REQUEST["symb_url"] : NULL;
$symb_forum = isset($_REQUEST["symb_forum"]) ? $_REQUEST["symb_forum"] : NULL;

if ($aid > 0) {
	$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
	$ausgabe .= table(600, "bg");
	$ausgabe .= tr(td(7, "head", "Symbiosen Administration"));

	$result_symb = mysql_query("SELECT * FROM genesis_allianzen WHERE id='$aid'");
	$inhalte_symb = mysql_fetch_array($result_symb, MYSQL_ASSOC);
	$symb = $inhalte_symb["tag"];
	$r = $inhalte_s["alli_rang"];
	$result_rang = mysql_query("SELECT * FROM genesis_raenge WHERE alli='$aid' and rang='$r'");
	$inhalte_rang = mysql_fetch_array($result_rang, MYSQL_ASSOC);
	$arecht = preg_split('//', $inhalte_rang["rechte"], -1, PREG_SPLIT_NO_EMPTY);

	$result_mem = mysql_query("SELECT count(*) FROM genesis_spieler WHERE alli='$aid' and alli_rang='0'");
	$inhalte_mem = mysql_fetch_array($result_mem, MYSQL_NUM);
	$ganz = $inhalte_mem[0];

	$result_w = mysql_query("SELECT id FROM genesis_news WHERE an='$aid' AND typ='bewerb'");
	$bewerbungen = mysql_num_rows($result_w);

	if ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0) {
		if ($aktion == "Speichern" && $symb_name != "" && ($arecht[0] == 1 || $r == 0)) {
			$symb_name = sauber($symb_name);
			$symb_text = sauber($symb_text);
			$symb_intern = sauber($symb_intern);

			if (substr($symb_url, 0, 7) != "http://" && $symb_url != "") {
				$symb_url = "http://" . $symb_url;
			}
			if (substr($symb_forum, 0, 7) != "http://" && $symb_forum != "") {
				$symb_forum = "http://" . $symb_forum;
			}
			$fehler = "";

			if ($symb_bild_up != "") {
				$filesize = filesize($symb_bild_up) / 1024;
				if ($filesize > 200) {
					$fehler .= "<font color=red>Datei ist zu groß!</font><br>";
				}
				list($width, $height, $type, $attr) = getimagesize($symb_bild_up);
				if ($width > 500 || $height > 450 || ($type != 1 && $type != 2 && $type != 3)) {
					$fehler .= "<font color=red>Datei hat ungültiges Format!</font><br>";
				}
				if ($fehler == "") {
					$ext = substr($_FILES['symb_bild_up']['type'], strrpos($_FILES['symb_bild_up']['type'], "/") + 1);
					if ($ext == "pjpeg") {
						$ext = "jpg";
					}
					if ($ext == "x-png") {
						$ext = "png";
					}
					$random = time();
					if (copy($symb_bild_up, "images/symbpics/$random.$ext")) {
						if ($inhalte_symb["bild"] != "" && substr($inhalte_symb["bild"], 0, 4) != "http") {
							unlink($inhalte_symb["bild"]);
						}
						$symb_bild = "images/symbpics/$random.$ext";
					}
				}
			} elseif ($symb_bild != "" && $symb_bild != $inhalte_symb["bild"]) {
				if ($inhalte_symb["bild"] != "" && substr($inhalte_symb["bild"], 0, 4) != "http") {
					unlink($inhalte_symb["bild"]);
				}
			}
			mysql_query("UPDATE genesis_allianzen SET name='$symb_name', beschreibung='$symb_text', intern='$symb_intern', url='$symb_url', forum='$symb_forum', bild='$symb_bild' WHERE id='$aid'");
			$fehler .= "<br>Erfolgreich gespeichert!";
			$result_symb = mysql_query("SELECT * FROM genesis_allianzen WHERE id='$aid'");
			$inhalte_symb = mysql_fetch_array($result_symb, MYSQL_ASSOC);
			$aktion = "";
		} elseif ($aktion == "delete" && $r == 0) {
			if ($sicher == 1) {
				$result_mem = mysql_query("SELECT id FROM genesis_spieler WHERE alli='$aid'");
				while ($inhalte_mem = mysql_fetch_array($result_mem, MYSQL_ASSOC)) {
					mysql_query("UPDATE genesis_spieler SET alli='0', alli_rang='0' WHERE id='" . $inhalte_mem["id"] . "'");
					mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('$sid','" . $inhalte_mem["id"] . "','" . time() . "','news','Symbiose aufgelöst','Deine Symbiose [$symb] wurde aufgelöst.')");
				}
				$result_p = mysql_query("SELECT * FROM genesis_politik WHERE alli2='$aid' and (typ='1' or typ='3')");
				while ($inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC)) {
					$result_a1 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli1"] . "'");
					$inhalte_a1 = mysql_fetch_array($result_a1, MYSQL_ASSOC);
					$result_a2 = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte_p["alli2"] . "'");
					$inhalte_a2 = mysql_fetch_array($result_a2, MYSQL_ASSOC);
					mysql_query("DELETE FROM genesis_politik WHERE id='" . $inhalte_p["id"] . "'");
					mysql_query("INSERT INTO genesis_history (alli1,alli2,typ,zeit,zusatz) VALUES ('" . $inhalte_a1["tag"] . "','" . $inhalte_a2["tag"] . "','" . ($inhalte_p["typ"] + 1) . "','" . time() . "','0')");
				}
				mysql_query("DELETE FROM genesis_allianzen WHERE id='$aid'");
				mysql_query("DELETE FROM genesis_raenge WHERE alli='$aid'");
				mysql_query("DELETE FROM genesis_news WHERE an='$aid' and typ='alli_news'");
				mysql_query("DELETE FROM genesis_politik WHERE alli2='$aid' and typ='7' and accept='0'");
				$symb = "";
				$ausgabe .= "<script type=\"text/javascript\">window.location.href='game.php?id=$id&b=$b&nav=$nav'</script>\n";
			} else {
				$ausgabe .= tr(td(2, "navi", "Symbiose auflösen"));
				$result_polit = mysql_query("SELECT id FROM genesis_politik WHERE alli1='$aid' OR (alli2='$aid' and (typ='5' or (typ='7' and accept='1')))");
				if ($inhalte_polit = mysql_fetch_array($result_polit, MYSQL_ASSOC)) {
					$ausgabe .= tr(td(2, "center", "Bitte erst alle aktiven Verhältnisse beenden!"));
					$ausgabe .= tr(td(2, "center", "<br>"));
					$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin", "zurück zur Administration")));
				} else {
					$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=delete&sicher=1", "Ja, ich bin mir sicher!")));
					$ausgabe .= tr(td(2, "center", "<br>"));
					$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin", "zurück zur Administration")));
				}
			}
		} elseif ($aktion == "Akzeptieren" && $s != "" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$result_w = mysql_query("SELECT id FROM genesis_news WHERE an='" . $inhalte_symb["id"] . "' AND von='$s' AND typ='bewerb'");
			if ($inhalte_w = mysql_fetch_array($result_w, MYSQL_ASSOC)) {
				$anz = $inhalte_symb["anz"] + 1;
				mysql_query("UPDATE genesis_allianzen SET anz='$anz' WHERE id='$aid'");
				mysql_query("UPDATE genesis_spieler SET alli='$aid', alli_rang='1' WHERE id='$s'");
				mysql_query("DELETE FROM genesis_news WHERE von='$s' AND typ='bewerb' AND an='$aid'");
				mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('$sid','$s','" . time() . "','news','Bewerbung akzeptiert','Deine Bewerbung bei [$symb] wurde akzeptiert.<br>Du bist nun ein Mitglied dieser Symbiose.')");
				$results = mysql_query("SELECT name FROM genesis_spieler WHERE id='$s'");
				$inhaltes = mysql_fetch_array($results, MYSQL_ASSOC);
				$fehler .= "<br>" . $inhaltes["name"] . " wurde in die Symbiose aufgenommen!";
				$result_w = mysql_query("SELECT id FROM genesis_news WHERE an='$aid' AND typ='bewerb'");
				$bewerbungen = mysql_num_rows($result_w);
			}
			$aktion = "";
		} elseif ($aktion == "Ablehnen" && $s != "" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$result_w = mysql_query("SELECT id FROM genesis_news WHERE an='" . $inhalte_symb["id"] . "' AND von='$s' AND typ='bewerb'");
			if ($inhalte_w = mysql_fetch_array($result_w, MYSQL_ASSOC)) {
				mysql_query("DELETE FROM genesis_news WHERE von='$s' AND typ='bewerb' AND an='$aid'");
				mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('$sid','$s','" . time() . "','news','Bewerbung abgelehnt','Deine Bewerbung bei [$symb] wurde abgelehnt.')");
				$results = mysql_query("SELECT name FROM genesis_spieler WHERE id='" . $s . "'");
				$inhaltes = mysql_fetch_array($results, MYSQL_ASSOC);
				$fehler .= "<br>Die Bewerbung von " . $inhaltes["name"] . " wurde abgelehnt!";
				$result_w = mysql_query("SELECT id FROM genesis_news WHERE an='$aid' AND typ='bewerb'");
				$bewerbungen = mysql_num_rows($result_w);
			}
			$aktion = "";
		} elseif ($aktion == "Änderungen übernehmen" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$result_mem = mysql_query("SELECT id,alli_rang FROM genesis_spieler WHERE alli='$aid'");
			while ($inhalte_mem = mysql_fetch_array($result_mem, MYSQL_ASSOC)) {
				if (($inhalte_mem["alli_rang"] != 0 && $sid != $inhalte_mem["id"]) || ($inhalte_mem["alli_rang"] == 0 && $r == 0 && $sid != $inhalte_mem["id"])) {
					$kick = 0;
					$rmem = $_REQUEST["rang" . $inhalte_mem["id"]];
					$kick = $_REQUEST["kick" . $inhalte_mem["id"]];
					if ($kick == 1) {
						mysql_query("UPDATE genesis_spieler SET alli='0', alli_rang='0' WHERE id='" . $inhalte_mem["id"] . "'");
						mysql_query("INSERT INTO genesis_news (von,an,zeit,typ,betreff,news) VALUES ('$sid','" . $inhalte_mem["id"] . "','" . time() . "','news','Ausschluss aus Symbiose','Deine Mitgliedschaft in der Symbiose [$symb] wurde beendet.')");
					} else {
						$result_raenge = mysql_query("SELECT id FROM genesis_raenge WHERE alli='$aid' and rang='$rmem'");
						if (mysql_fetch_array($result_raenge, MYSQL_ASSOC)) {
							mysql_query("UPDATE genesis_spieler SET alli_rang='$rmem' WHERE id='" . $inhalte_mem["id"] . "'");
						}
					}
				}
			}
			$ausgabe .= "<script type=\"text/javascript\">window.location.href='game.php?id=$id&b=$b&nav=$nav&aktion=member'</script>\n";
		} elseif ($aktion == "update" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			mysql_query("UPDATE genesis_spieler SET punktea=punkte, punktema=punktem WHERE alli='$aid'");
			$ausgabe .= "<script type=\"text/javascript\">window.location.href='game.php?id=$id&b=$b&nav=$nav&aktion=member'</script>\n";
		} elseif ($aktion == "bewerb" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$result_w = mysql_query("SELECT * FROM genesis_news WHERE an='" . $inhalte_symb["id"] . "' AND typ='bewerb'");
			while ($inhalte_w = mysql_fetch_array($result_w, MYSQL_ASSOC)) {
				$results = mysql_query("SELECT id,name FROM genesis_spieler WHERE id='" . $inhalte_w["von"] . "'");
				$inhaltes = mysql_fetch_array($results, MYSQL_ASSOC);
				$ausgabe .= tr(td(0, "head", "Bewerbung von " . hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhaltes["id"], $inhaltes["name"])));
				$ausgabe .= tr(td(0, "center", parsetxt($inhalte_w["news"])));
				$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&s=" . $inhaltes["id"] . "&a=admin&aktion=Akzeptieren", "Akzeptieren")));
				$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&s=" . $inhaltes["id"] . "&a=admin&aktion=Ablehnen", "Ablehnen")));
			}
		} elseif ($aktion == "member" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$ausgabe .= tr(
				td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=admin&aktion=member&t=0", "Name"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=admin&aktion=member&t=1", "Rang"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=admin&aktion=member&t=2", "Punkte"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=admin&aktion=member&t=4", "Online"))
				 . td(0, "navi", "Kick"));

			if ($t == "" || $t == 0) {
				$ord = "name";
			}
			if ($t == 1) {
				$ord = "alli_rang";
			}
			if ($t == 2) {
				$ord = "punkte DESC";
			}
			if ($t == 3) {
				$ord = "punktem DESC";
			}
			if ($t == 4) {
				$ord = "log DESC";
			}

			$result_mem = mysql_query("SELECT id,name,alli_rang,basis1,punkte,punktem,punktea,punktema,log,urlaub FROM genesis_spieler WHERE alli='$aid' order by $ord");
			while ($inhalte_mem = mysql_fetch_array($result_mem, MYSQL_ASSOC)) {
				$class = "";
				$class2 = "";
				$um = "";
				if ($inhalte_mem["urlaub"] > time()) {
					$um = " [U] ";
				}
				if ($inhalte_mem["punktea"] < $inhalte_mem["punkte"]) {
					$class = " class='ja'";
				}
				if ($inhalte_mem["punktea"] > $inhalte_mem["punkte"]) {
					$class = " class='nein'";
				}
				if ($inhalte_mem["punktema"] < $inhalte_mem["punktem"]) {
					$class2 = " class='ja'";
				}
				if ($inhalte_mem["punktema"] > $inhalte_mem["punktem"]) {
					$class2 = " class='nein'";
				}

				if ($inhalte_mem["name"] == $name) {
					$out2 = $inhalte_rang["name"];
				} elseif ($inhalte_mem["alli_rang"] == 0 && $r != 0) {
					$result_raenge = mysql_query("SELECT name FROM genesis_raenge WHERE alli='$aid' and rang='0'");
					$inhalte_raenge = mysql_fetch_array($result_raenge, MYSQL_ASSOC);
					$out2 = $inhalte_raenge["name"];
				} else {
					$out2 = "<select name=\"rang" . $inhalte_mem["id"] . "\">\n";
					$result_raenge = mysql_query("SELECT rang,name FROM genesis_raenge WHERE alli='$aid' order by rang");
					while ($inhalte_raenge = mysql_fetch_array($result_raenge, MYSQL_ASSOC)) {
						if (($r == 0 && $inhalte_raenge["rang"] == 0) || $inhalte_raenge["rang"] > 0) {
							$out2 .= "<option value=" . $inhalte_raenge["rang"];
							if ($inhalte_mem["alli_rang"] == $inhalte_raenge["rang"]) {
								$out2 .= " selected";
							}
							$out2 .= ">" . $inhalte_raenge["name"] . "</option>\n";
						}
					}
					$out2 .= "</select>";
				}
				if ($inhalte_mem["alli_rang"] != 0 && $inhalte_mem["name"] != $name) {
					$out3 = input("checkbox", "kick" . $inhalte_mem["id"], "1");
				} else {
					$out3 = "-";
				}
				$ausgabe .= tr(td(0, "center", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte_mem["id"], $inhalte_mem["name"]) . " <i>(" . $inhalte_mem["basis1"] . ")</i>" . $um) . td(0, "center", $out2) . td(0, "center", "<p$class>" . format($inhalte_mem["punkte"] . "</p>")) . td(0, "center", date("d.m.Y (H:i:s)", $inhalte_mem["log"]) . td(0, "center", $out3)));
			}
			$ausgabe .= tr(td(5, "center", "<br>"));
			$ausgabe .= tr(td(5, "center", input("submit", "aktion", "Änderungen übernehmen")));
			$ausgabe .= tr(td(5, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=update", "Punkte aktualisieren")));
			$ausgabe .= tr(td(5, "center", "<br>"));
			$ausgabe .= tr(td(5, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin", "zurück zur Administration")));
		} elseif ($aktion == "raenge" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$ausgabe .= tr(td(0, "navi", "Titel") . td(0, "navi", "<i>Administrieren | Mitglieder Verwalten | Nachrichten schreiben | Nachrichten lesen | erweiterte Mitgliederansicht | Rundschreiben</i>") . td(0, "navi", "&nbsp;"));

			$result_raenge = mysql_query("SELECT * FROM genesis_raenge WHERE alli='$aid' order by rang");
			while ($inhalte_raenge = mysql_fetch_array($result_raenge, MYSQL_ASSOC)) {
				$arecht = preg_split('//', $inhalte_raenge["rechte"], -1, PREG_SPLIT_NO_EMPTY);
				$out1 = "";
				if ($inhalte_raenge["rang"] < 2) {
					$out2 = "-";
					if ($inhalte_raenge["rang"] == 0) {
						$out1 = "vordefinierter Rang mit allen Rechten (Gründer)";
					}
					if ($inhalte_raenge["rang"] == 1) {
						$out1 = "vordefinierter Rang ohne Rechte (neues Mitglied)";
					}
				} else {
					for ($i = 0; $i <= 5; $i++) {
						if ($arecht[$i] == 1) {
							$out1 .= input("checkedbox", "rr_" . $inhalte_raenge["rang"] . "_$i", 1);
						} else {
							$out1 .= input("checkbox", "rr_" . $inhalte_raenge["rang"] . "_$i", 1);
						}
					}
					$out2 = hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=delrang&rid=" . $inhalte_raenge["rang"], "Löschen");
				}
				$ausgabe .= tr(td(0, "center", input("text", "rn_" . $inhalte_raenge["rang"], $inhalte_raenge["name"])) . td(0, "center", $out1) . td(0, "center", $out2));
			}
			$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=add", "Rang hinzufügen")) . td(0, "center", "&nbsp;") . td(0, "center", "&nbsp;"));
			$ausgabe .= tr(td(3, "center", "<br>"));
			$ausgabe .= tr(td(3, "center", input("hidden", "aktion", "changeraenge") . input("submit", "blabla", "Änderungen übernehmen")));
			$ausgabe .= tr(td(3, "center", "<br>"));
			$ausgabe .= tr(td(3, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin", "zurück zur Administration")));
		} elseif ($aktion == "changeraenge" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$result_raenge = mysql_query("SELECT * FROM genesis_raenge WHERE alli='$aid' order by rang");
			while ($inhalte_raenge = mysql_fetch_array($result_raenge, MYSQL_ASSOC)) {
				$rechte = "";
				if ($inhalte_raenge["rang"] > 1) {
					for($i = 0; $i <= 5; $i++) {
						$recht = $_REQUEST["rr_" . $inhalte_raenge["rang"] . "_" . $i];
						if ($recht == "") {
							$recht = 0;
						}
						$rechte .= $recht;
					}
				} else {
					$rechte = $inhalte_raenge["rechte"];
				}
				$rname = $_REQUEST["rn_" . $inhalte_raenge["rang"]];
				if ($rname == "") {
					$rname = $inhalte_raenge["name"];
				}
				mysql_query("UPDATE genesis_raenge SET name='$rname', rechte='$rechte' WHERE id='" . $inhalte_raenge["id"] . "'");
			}
			$ausgabe .= "<script type=\"text/javascript\">window.location.href='game.php?id=$id&b=$b&nav=$nav&aktion=raenge'</script>\n";
		} elseif ($aktion == "delrang" && $rid > 0 && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$result_raenge = mysql_query("SELECT * FROM genesis_raenge WHERE alli='$aid' and rang='$rid'");
			if ($inhalte_raenge = mysql_fetch_array($result_raenge, MYSQL_ASSOC)) {
				$result_mem = mysql_query("SELECT id FROM genesis_spieler WHERE alli='$aid' and alli_rang='$rid'");
				while ($inhalte_mem = mysql_fetch_array($result_mem, MYSQL_ASSOC)) {
					mysql_query("UPDATE genesis_spieler SET alli_rang='1' WHERE id='" . $inhalte_mem["id"] . "'");
				}
				mysql_query("DELETE FROM genesis_raenge WHERE alli='$aid' and rang='$rid'");
			}
			$ausgabe .= "<script type=\"text/javascript\">window.location.href='game.php?id=$id&b=$b&nav=$nav&aktion=raenge'</script>\n";
		} elseif ($aktion == "add" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			if ($rname != "") {
				$result_raenge = mysql_query("SELECT rang FROM genesis_raenge WHERE alli='$aid' order by rang desc LIMIT 1");
				$inhalte_raenge = mysql_fetch_array($result_raenge, MYSQL_ASSOC);
				mysql_query("INSERT INTO genesis_raenge (alli, rang, name, rechte) VALUES ('$aid', '" . ($inhalte_raenge["rang"] + 1) . "', '$rname', '0000000000')");
				$ausgabe .= "<script type=\"text/javascript\">window.location.href='game.php?id=$id&b=$b&nav=$nav&aktion=raenge'</script>\n";
			} else {
				$ausgabe .= tr(td(2, "navi", "Rang hinzufügen" . input("hidden", "aktion", "add")));
				$ausgabe .= tr(td(2, "center", "Titel " . input("text", "rname", "")));
				$ausgabe .= tr(td(2, "center", input("submit", "balbla", "Hinzufügen")));
			}
		} elseif ($aktion == "edit" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			$ausgabe .= tr(td(0, "leftsymb", "Name") . td(0, "right", input("symbtext", "symb_name", $inhalte_symb["name"])));
			$ausgabe .= tr(td(0, "leftsymb", "Homepage (URL)") . td(0, "right", input("symbtext", "symb_url", $inhalte_symb["url"])));
			$ausgabe .= tr(td(0, "leftsymb", "Forum (URL)") . td(0, "right", input("symbtext", "symb_forum", $inhalte_symb["forum"])));
			$ausgabe .= tr(td(0, "leftsymb", "Bild (URL)") . td(0, "right", input("symbtext", "symb_bild", $inhalte_symb["bild"])));
			$ausgabe .= tr(td(0, "leftsymb", "Bild hochladen") . td(0, "right", input("file", "symb_bild_up", "") . "<br>(max. 500x400 Pixel und 200kB)"));
			$ausgabe .= tr(td(0, "leftsymb", "Interne Information") . td(0, "right", "<textarea name=symb_intern cols=60 rows=10>" . $inhalte_symb["intern"] . "</textarea>"));
			$ausgabe .= tr(td(0, "leftsymb", "Beschreibung") . td(0, "right", "<textarea name=symb_text cols=60 rows=20>" . $inhalte_symb["beschreibung"] . "</textarea>"));
			$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Speichern")));
		}

		if ($aktion == "" && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
			if (($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
				$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=edit", "Daten editieren")));
			}
			if ($bewerbungen > 0 && ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0)) {
				$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=bewerb", "Bewerbungen bearbeiten")));
			}
			if ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0) {
				$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=member", "Mitglieder verwalten")));
			}
			if ($arecht[0] == 1 || $r == 0) {
				$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_polit", "Verhältnisse bearbeiten")));
			}
			if ($arecht[0] == 1 || $arecht[1] == 1 || $r == 0) {
				$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=raenge", "Ränge verwalten")));
			}
			if ($r == 0) {
				$ausgabe .= tr(td(2, "center", hlink("", "game.php?id=$id&b=$b&nav=symb_admin&aktion=delete", "Symbiose auflösen")));
			}
			if (isset($fehler)) {
				$ausgabe .= tr(td(2, "center", $fehler));
			}
		}
	}
}

unset ($idm, $fehler, $inhalte_b, $result_b, $bewerungen, $inhalte_symb, $result_symb, $inhalte2, $result2, $inhalte_symb2, $result_symb2, $inhalte_mem, $result_mem, $r, $a, $aktion, $symb, $symb_tag, $symb_text, $symb_bild, $symb_url, $symb_text);

$ausgabe .= "</table>\n</form>\n";

?>