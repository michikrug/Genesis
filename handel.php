<?php

$zeit = time();
$ausgabe .= table(600, "bg");

if ($inhalte_s["urlaub"] < $zeit) {
	$ausgabe .= tr(td(2, "head", "Handel"));

	$a = isset($_REQUEST["a"]) ? $_REQUEST["a"] : NULL;
	$aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : NULL;
	$gebot = isset($_REQUEST["gebot"]) ? $_REQUEST["gebot"] : NULL;
	$hid = isset($_REQUEST["hid"]) ? $_REQUEST["hid"] : NULL;
	$ord = isset($_REQUEST["ord"]) ? $_REQUEST["ord"] : NULL;

	if ($inhalte_b["konst1"] > 4 && $inhalte_b["typ"] == 0) {
		$ausgabe .= tr(td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=neu", "Angebot erstellen")) . td(0, "center", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=anz", "Angebote ansehen")));
		$ausgabe .= "</table>\n<br/>\n";
		if ($a != "neu") {
			$gebot = intval($gebot);
			if ($aktion == "Bieten" && $gebot > 0 && $hid > 0) {
				$result = mysql_query("SELECT * FROM genesis_handel WHERE id='$hid'");
				$inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
				if ($inhalte["zeit"] > time()) {
					if ($inhalte["anz_geb"] * 4 >= $gebot) {
						if ($inhalte_b["ress" . $inhalte["typ_such"]] >= $gebot && ($inhalte["anz_such"] + round($inhalte["anz_such"] / 50, 0)) < ($gebot + 1)) {
							mysql_query("UPDATE genesis_handel SET bieter='$sid', anz_such='$gebot' WHERE id='$hid'");
						}
					} else {
						$ausgabe .= "Na wir wollen mal nicht zuviel bieten! (Verhältnis 1:4)<br/>";
					}
				} else {
					$ausgabe .= "Handelsfrist schon abgelaufen.<br/>";
				}
			}
			if ($ord == "") $ord = "zeit";
			$ausgabe .= table(600, "bg");
			$ausgabe .= tr(td(5, "head", "Angebotsliste"));
			$ausgabe .= tr(
				td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=anz&ord=typ_geb", "Biete"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=anz&ord=typ_such", "Suche"))
				 . td(0, "navi", hlink("", "game.php?id=$id&b=$b&nav=$nav&a=anz", "Zeit"))
				 . td(0, "navi", "Aktion"));
			$result = mysql_query("SELECT * FROM genesis_handel WHERE zeit>'$zeit' order by $ord");
			mt_srand(microtime() * 1000000);
			while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$dauer = $inhalte["zeit"] - $zeit;
				include("dauer.inc.php");
				if ($inhalte_b["ress" . $inhalte["typ_such"]] >= ($inhalte["anz_such"] + round($inhalte["anz_such"] / 50, 0)) && $inhalte["bieter"] != $sid && $inhalte["sucher"] != $sid) {
					$out = form("game.php?id=$id&b=$b&nav=$nav&a=anz&hid=" . $inhalte["id"])
					 . input("text", "gebot", $inhalte["anz_such"] + round($inhalte["anz_such"] / 50, 0)) . " "
					 . input("submit", "aktion", "Bieten") . "</form>";
				} elseif ($inhalte["bieter"] == $sid && $inhalte["sucher"] != $sid) {
					$out = "du bist Höchstbietender";
				} elseif ($inhalte["sucher"] == $sid) {
					$out = "dein eigenes Angebot";
				} else {
					$out = "nicht genug Nährstoffe";
				}
				$tid = intval(mt_rand(1111111, 9999999));
				$outa = "<font id=\"$tid\" title=\"$zeitpunkt\">$h:$m:$s</font><script type=\"text/javascript\">init_countdown ('$tid', $dauer, 'Beendet', '', '');</script>";
				$ausgabe .= tr(td(0, "center", format($inhalte["anz_geb"]) . " " . num2typ($inhalte["typ_geb"])) . td(0, "center", format($inhalte["anz_such"]) . " " . num2typ($inhalte["typ_such"])) . td(0, "center", $outa) . td(0, "center", $out));
			}
			unset($outa);
			$ausgabe .= "</table>\n";
		} elseif ($a == "neu") {

			$anz_geb = isset($_REQUEST["anz_geb"]) ? intval($_REQUEST["anz_geb"]) : NULL;
			$anz_such = isset($_REQUEST["anz_such"]) ? intval($_REQUEST["anz_such"]) : NULL;
			$typ_geb = isset($_REQUEST["typ_geb"]) ? intval($_REQUEST["typ_geb"]) : NULL;
			$typ_such = isset($_REQUEST["typ_such"]) ? intval($_REQUEST["typ_such"]) : NULL;
			$dauer = isset($_REQUEST["dauer"]) ? intval($_REQUEST["dauer"]) : NULL;
			$aw = isset($_REQUEST["aw"]) ? $_REQUEST["aw"] : NULL;

			if ($aw == "Ja" && $aktion == "Erstellen" && $anz_geb > 0 && $anz_such > 0 && $typ_geb > 0 && $typ_such > 0 && $typ_geb < 5 && $typ_such < 5 && $dauer >= 3600 && $dauer <= 57600 && $typ_geb != $typ_such) {
				$result = mysql_query("SELECT id FROM genesis_handel WHERE sucher='$sid' and zeit>'$zeit'");
				if (!$inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
					if ($anz_such / 3 <= $anz_geb && $anz_geb / 4 <= $anz_such) {
						if ($inhalte_b["ress$typ_geb"] >= $anz_geb) {
							$ress_neu = $inhalte_b["ress$typ_geb"] - $anz_geb;
							$dauer += $zeit;
							mysql_query("UPDATE genesis_basen SET ress$typ_geb='$ress_neu' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
							mysql_query("INSERT INTO genesis_handel (zeit, sucher, bieter, typ_geb, typ_such, anz_geb, anz_such) VALUES ('$dauer', '$sid', '0', '$typ_geb', '$typ_such', '$anz_geb', '$anz_such')");
							$ausgabe .= "Angebot wurde erfolgreich erstellt!<br/>Sobald der Handel abgeschlossen ist, bekommst du eine Nachricht!";
						} else {
							$ausgabe .= "Angebot konnte nicht erstellt werden, da nicht genügend Nährstoffe zur Verfügung stehen!";
						}
					} else {
						$ausgabe .= "Angebot konnte nicht erstellt werden (du suchst mehr als 3x soviel wie du bietest / du bietest mehr als 4x soviel wie du suchst)!";
					}
				} else {
					$ausgabe .= "Du kannst nur ein Angebot erstellen!";
				}
			} elseif ($aktion == "Erstellen" && $anz_geb > 0 && $anz_such > 0 && $typ_geb > 0 && $typ_such > 0 && $typ_geb < 5 && $typ_such < 5 && $dauer >= 3600 && $dauer <= 57600 && $typ_geb != $typ_such) {
				$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav&a=neu");
				$ausgabe .= table(600, "bg");
				$ausgabe .= tr(td(2, "head", "Angebot erstellen" . input("hidden", "aktion", "Erstellen") . input("hidden", "anz_such", $anz_such) . input("hidden", "anz_geb", $anz_geb) . input("hidden", "typ_such", $typ_such) . input("hidden", "typ_geb", $typ_geb) . input("hidden", "dauer", $dauer)));
				$ausgabe .= tr(td(0, "center", "Du bietest " . format($anz_geb) . " " . num2typ($typ_geb) . " an."));
				$ausgabe .= tr(td(0, "center", "Du verlangst dafür mindestens " . format($anz_such) . " " . num2typ($typ_such) . "."));
				$ausgabe .= tr(td(0, "center", "Dein Angebot soll " . ($dauer / 3600) . " Stunde(n) gültig sein."));
				if ($anz_such / 3 > $anz_geb) $ausgabe .= tr(td(0, "center", "<br/>Angebot ist nicht gültig, da du mehr als 3x soviel verlangst wie du anbietest!"));
				if ($anz_geb / 4 > $anz_such) $ausgabe .= tr(td(0, "center", "Angebot ist nicht gültig, da du mehr als 4x soviel anbietest wie du verlangst!"));
				if ($inhalte_b["ress$typ_geb"] < $anz_geb) $ausgabe .= tr(td(0, "center", "Angebot kann so nicht erstellt werden, da nicht genügend Nährstoffe zur Verfügung stehen!"));
				if ($anz_such / 3 <= $anz_geb && $anz_geb / 4 <= $anz_such && $inhalte_b["ress$typ_geb"] >= $anz_geb) {
					$ausgabe .= tr(td(2, "center", "Ist dies korrekt? " . input("submit", "aw", "Ja") . " " . input("back", "aw", "Nein")));
				} else {
					$ausgabe .= tr(td(2, "center", input("back", "aw", "Zurück")));
				}
				$ausgabe .= "</table>\n</form><br/>Die gebotenen Nährstoffe werden dir sofort abgezogen!<br/>Wenn das Angebot nicht wahrgenommen wird, werden 10% Provision einbehalten.<br/>\n";
			} else {
				$result = mysql_query("SELECT id FROM genesis_handel WHERE sucher='$sid' and zeit>'$zeit'");
				if (!$inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav&a=neu");
					$ausgabe .= table(600, "bg");
					$ausgabe .= tr(td(2, "head", "Angebot erstellen"));
					$ausgabe .= tr(td(0, "navi", "Biete") . td(0, "center", input("text", "anz_geb", "") . " <select name=\"typ_geb\"><option value=1>Adenin</option><option value=2>Thymin</option><option value=3>Guanin</option><option value=4>Cytosin</option></select>"));
					$ausgabe .= tr(td(0, "navi", "Suche (min.)") . td(0, "center", input("text", "anz_such", "") . " <select name=\"typ_such\"><option value=1>Adenin</option><option value=2>Thymin</option><option value=3>Guanin</option><option value=4>Cytosin</option></select>"));
					$ausgabe .= tr(td(0, "navi", "Gebotsdauer") . td(0, "center", "<select name=\"dauer\"><option value=3600>1 Stunde</option><option value=7200>2 Stunden</option><option value=14400>4 Stunden</option><option value=28800>8 Stunden</option><option value=57600>16 Stunden</option></select>"));
					$ausgabe .= tr(td(2, "center", input("submit", "aktion", "Erstellen")));
					$ausgabe .= "</table>\n</form>\n";
				} else {
					$ausgabe .= "Du kannst nur ein Angebot erstellen!";
				}
			}
		}
	} else {
		$ausgabe .= tr(td(0, "center", "Handel erst ab Retikulum Stufe 5 möglich!<br/>(Auf 2. Neogen ist der Handel nicht möglich.)"));
		$ausgabe .= "</table>\n<br/>\n";
	}
} else {
	$ausgabe .= tr(td(0, "head", "Handel"));
	$ausgabe .= tr(td(0, "center", "Handel im Urlaubsmodus deaktiviert!"));
}

?>