<?php

$ausgabe .= form("game.php?id=$id&b=$b&nav=$nav");
$ausgabe .= table(550, "bg");

$zeit = time();

if ($inhalte_b["konst14"] > 0) {
    if ($inhalte_s["urlaub"] > time()) $ausgabe .= tr(td(0, "center", "Im Urlaubsmodus können keine Missionen gestartet werden."));

    $fehler = false;
    $missok = 0;
    $newmiss = 0;
    $result_miss = mysql_query("SELECT count(*) as anz FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='miss'");
    $inhalte_miss = mysql_fetch_array($result_miss, MYSQL_ASSOC);

    for ($i = 1; $i <= 8; $i++) {
        eval("\$me$i = isset(\$_REQUEST[\"me$i\"]) ? intval(\$_REQUEST[\"me$i\"]) : 0;");
    }
    for($i=1;$i<=5;$i++){
        eval("\$mir$i = isset(\$_REQUEST[\"mir$i\"]) ? intval(\$_REQUEST[\"mir$i\"]) : 0;");
        eval("\$mrp$i = isset(\$_REQUEST[\"mrp$i\"]) ? intval(\$_REQUEST[\"mrp$i\"]) : 0;");
    }

    $aktion = isset($_REQUEST["aktion"]) ? $_REQUEST["aktion"] : null;
    $stao = isset($_REQUEST["stao"]) ? intval($_REQUEST["stao"]) : 0;
    $save = isset($_REQUEST["save"]) ? intval($_REQUEST["save"]) : 0;
    $gematt = isset($_REQUEST["gematt"]) ? intval($_REQUEST["gematt"]) : 0;
    $vd = isset($_REQUEST["vd"]) ? intval($_REQUEST["vd"]) : 1;
    $mt = isset($_REQUEST["mt"]) ? intval($_REQUEST["mt"]) : null;
    $mg = isset($_REQUEST["mg"]) ? intval($_REQUEST["mg"]) : 100;
    $mkx = isset($_REQUEST["mkx"]) ? intval($_REQUEST["mkx"]) : null;
    $mky = isset($_REQUEST["mky"]) ? intval($_REQUEST["mky"]) : null;
    $mkz = isset($_REQUEST["mkz"]) ? intval($_REQUEST["mkz"]) : null;
    $mx = isset($_REQUEST["mx"]) ? $_REQUEST["mx"] : NULL;
    $my = isset($_REQUEST["my"]) ? $_REQUEST["my"] : NULL;
    $mz = isset($_REQUEST["mz"]) ? $_REQUEST["mz"] : NULL;
    $mid = isset($_REQUEST["mid"]) ? $_REQUEST["mid"] : NULL;


    if ($inhalte_miss["anz"] < missanz($inhalte_b["konst14"])) $missok = 1;

    if ($me1 <= 0 && $me2 <= 0 && $me3 <= 0 && $me4 <= 0 && $me5 <= 0 && $me6 <= 0 && $me7 <= 0 && $me8 <= 0 && $aktion != "info") {
        // Missionsübersicht
        $ausgabe .= tr(td(2, "head", "Missionsübersicht"));
        $zs = ")";
        if ($inhalte_s["basis2"]) $zs = " or basis1='" . $inhalte_s["basis2"] . "')";
        $result = mysql_query("SELECT * FROM genesis_aktionen WHERE (basis1='" . $inhalte_s["basis1"] . "'$zs AND typ='miss' ORDER BY endzeit");
        while ($inhalte = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $dauer = $inhalte["endzeit"] - time();
            include "dauer.inc.php";
            mt_srand(microtime() * 1000000);
            $tid = intval(mt_rand(1111111, 9999999));
            $outa = "<font id='$tid' title='$zeitpunkt'>$h:$m:$s</font><script language=JavaScript>init_countdown ('$tid', $dauer, 'Beendet', '', '');</script>";
            $i = $inhalte["aktion"];
            $mk = explode(":", $inhalte["basis2"]);
            $mk1 = explode(":", $inhalte["basis1"]);
            $mkxa = $mk[0];
            $mkya = $mk[1];
            $mkza = $mk[2];
            $cla = "";
            if ($i == 1) {
                if ($inhalte["zusatz"] == 1) $cla = "gemeinsamer ";
                $cla .= "Angriff";
            }
            if ($i == 2) {
                $cla = "Transport";
                if ($inhalte["zusatz"] == 1) $cla = "Stationierung";
                if ($inhalte["zusatz"] == 2) $out = "Save-Mission";
            }
            if ($i == 3) $cla = "Spionage";
            if ($i == 4) $cla = "Verteidigung";
            if ($i == 5) $cla = "Rückkehr";
            if ($i == 6) $cla = "Zellteilung";
            if ($i == 7) $cla = "Eiersuche";
            $result3 = mysql_query("SELECT name,bname FROM genesis_basen WHERE koordx='" . $mk1[0] . "' AND koordy='" . $mk1[1] . "' AND koordz='" . $mk1[2] . "'");
            $inhalte3 = mysql_fetch_array($result3, MYSQL_ASSOC);
            $result2 = mysql_query("SELECT name,bname FROM genesis_basen WHERE koordx='$mkxa' AND koordy='$mkya' AND koordz='$mkza'");
            $inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
            $result1 = mysql_query("SELECT id FROM genesis_spieler WHERE name='" . $inhalte2["name"] . "'");
            $inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
            if (!$inhalte2["name"]) {
                $inhalte2["name"] = "unbesetzt";
                $inhalte2["bname"] = "unbekannt";
            }
            // Rückkehr
            if ($i == 5 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"])) {
                $outb = hlink("", "game.php?id=$id&b=$b&nav=mission&aktion=info&mid=" . $inhalte["id"], "Mission");
                $outb .= " kehrt von ";
                $outb .= hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte1["id"] . "&k=$mkxa:$mkya:$mkza", $inhalte2["name"]) . " ($mkxa:$mkya:$mkza)";
                $outb .= " zurück nach ";
                $outb .= $inhalte3["bname"] . " (" . $inhalte["basis1"] . ")";
                // Hinflug
            } elseif (($i == 4 && $inhalte["zusatz"] >= 1 && $inhalte["zusatz"] <= 5 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"])) || ($i != 4 && $i != 5 && $inhalte["zusatz"] >= 0 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"]))) {
                $outb = hlink("", "game.php?id=$id&b=$b&nav=mission&aktion=info&mid=" . $inhalte["id"], $cla);
                $outb .= " von ";
                $outb .= $inhalte3["bname"] . " (" . $inhalte["basis1"] . ")";
                $outb .= " erreicht ";
                $outb .= hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte1["id"] . "&k=$mkxa:$mkya:$mkza", $inhalte2["name"]) . " ($mkxa:$mkya:$mkza)";
                // Eigene Verteidigung
            } elseif ($i == 4 && ($inhalte_s["basis1"] == $inhalte["basis1"] || $inhalte_s["basis2"] == $inhalte["basis1"])) {
                $outb = hlink("", "game.php?id=$id&b=$b&nav=mission&aktion=info&mid=" . $inhalte["id"], "Verteidigung");
                $outb .= " von ";
                $outb .= $inhalte3["bname"] . " (" . $inhalte["basis1"] . ")";
                $outb .= " bei ";
                $outb .= hlink("", "game.php?id=$id&b=$b&nav=info&t=spieler" . $inhalte1["id"] . "&k=$mkxa:$mkya:$mkza", $inhalte2["name"]) . " ($mkxa:$mkya:$mkza)";
            }
            $ausgabe .= tr(td(0, "center", $outa) . td(0, "center", $outb));
        }
        $ausgabe .= "</table><br/>\n";
        // Neue Mission
        $ausgabe .= table(550, "bg");
        $ausgabe .= tr(td(2, "head", "Mission"));
        $ausgabe .= input("hidden", "mx", $mkx);
        $ausgabe .= input("hidden", "my", $mky);
        $ausgabe .= input("hidden", "mz", $mkz);
        $ausgabe .= tr(td(2, "navi", "Exo-Zellen (<a href=\"#\" onclick=\"fuell('me1','" . $inhalte_b["prod1"] . "');fuell('me2','" . $inhalte_b["prod2"] . "');fuell('me3','" . $inhalte_b["prod3"] . "');fuell('me4','" . $inhalte_b["prod4"] . "');fuell('me5','" . $inhalte_b["prod5"] . "');fuell('me6','" . $inhalte_b["prod6"] . "');fuell('me7','" . $inhalte_b["prod7"] . "');fuell('me8','" . $inhalte_b["prod8"] . "');\">alle</a>)"));
        for ($i = 1; $i <= 8; $i++) {
            if ($inhalte_b["prod$i"] > 0) {
                $result = mysql_query("SELECT bezeichnung FROM genesis_infos WHERE typ='prod$i'");
                $inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
                $ausgabe .= tr(td(0, "center", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod$i", $inhalte["bezeichnung"])) . td(0, "center", input("zahl", "me$i", 0) . " (<a href=\"#\" onclick=\"fuell('me$i','" . $inhalte_b["prod$i"] . "');\">" . $inhalte_b["prod$i"] . "</a>)"));
            }
        }
        if ($missok == 1) {
            $ausgabe .= tr(td(2, "navi", input("submit", "aktion", "Weiter")));
        } else {
            $ausgabe .= tr(td(2, "center", "<font color=red>maximale Missionsanzahl erreicht</font>"));
        }
    } elseif ($inhalte_s["urlaub"] < time() && $mt <= 0 && $aktion == "Weiter" && $missok == 1 && $mkx == "" && $mky == "" && $mkz == "" && ($me1 > 0 || $me2 > 0 || $me3 > 0 || $me4 > 0 || $me5 > 0 || $me6 > 0 || $me7 > 0 || $me8 > 0)) {
        $x = $bk[0];
        $y = $bk[1];
        $z = $bk[2];

        if ($mx > 0 && $my > 0 && $mz > 0) {
            $mkx = $mx;
            $mky = $my;
            $mkz = $mz;
        }
        if ($mkx <= 0 || $mky <= 0 || $mkz <= 0) {
            $mkx = $x;
            $mky = $y;
            $mkz = $z;
        }

        $fehler = false;
        $ges = 1000000000;
        for ($i = 1; $i <= 8; $i++) {
            eval("\$anz = \$me$i;");
            if ($anz > $inhalte_b["prod$i"]) $anz = $inhalte_b["prod$i"];
            if ($anz > 0) {
                $result = mysql_query("SELECT wert5, wert6 FROM genesis_infos WHERE typ='prod$i'");
                $inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
                if ($ges > geschw($inhalte["wert6"], $inhalte_s["forsch2"])) $ges = geschw($inhalte["wert6"], $inhalte_s["forsch2"]);
                $ausgabe .= input("hidden", "me$i", $anz);
            }
        }
        if ($mkx != $x || $mky != $y || $mkz != $z) {
            $dist = round(sqrt(pow(abs($z - $mkz) * 100, 2) + pow(abs($y - $mky) * 100, 2) + pow(abs($x - $mkx) * 100, 2)), 2);
            $dist3 = round(sqrt(abs($z - $mkz) + abs($y - $mky) + abs($x - $mkx)) / 10, 3) + 1;
            $dist4 = round(($dist + 300) / $dist3, 2);
            /*
            $dist = 500;
            $dist3 = 500;
            $dist4 = 700;
            */
            $dauer = round((100 / $mg) * $dist4 / $ges * 4000, 0);
            include "dauer.inc.php";
            $daur = "$h:$m:$s";
            $ankz = $zeitpunkt;
            $dauer = (round((100 / $mg) * $dist4 / $ges * 4000, 2) * 2);
            include "dauer.inc.php";
            $ruez = $zeitpunkt;
        } else {
            $dist = "-";
            $ankz = "-";
            $ruez = "-";
            $daur = "-";
        }
        $neolink = "";
        if ($inhalte_s["basis2"]) {
            $neolink = " (<a href=\"#\" onclick=\"fuell('mkx','" . $bk2[0] . "');fuell('mky','" . $bk2[1] . "');fuell('mkz','" . $bk2[2] . "');daucalc();\">anderes Neogen</a>)";
        }
        $ausgabe .= table(550, "bg");
        $ausgabe .= tr(td(2, "head", "Mission"));
        $ausgabe .= tr(td(0, "left", "Zielkoordinaten" . $neolink) . td(0, "right", input("mkd", "mkx", $mkx) . " : " . input("mkd", "mky", $mky) . " : " . input("mkd", "mkz", $mkz)));
        $ausgabe .= tr(td(0, "left", "Geschwindigkeit") . td(0, "right", "<select id=\"mg\" name=\"mg\" onClick=\"daucalc();\" onKeyup=\"daucalc();\" onChange=\"daucalc();\"><option value=100>100%</option><option value=95>95%</option><option value=90>90%</option><option value=85>85%</option><option value=80>80%</option><option value=75>75%</option><option value=70>70%</option><option value=65>65%</option><option value=60>60%</option><option value=55>55%</option><option value=50>50%</option><option value=45>45%</option><option value=40>40%</option><option value=35>35%</option><option value=30>30%</option><option value=25>25%</option><option value=20>20%</option><option value=15>15%</option><option value=10>10%</option><option value=5>5%</option></select>"));
        $ausgabe .= tr(td(0, "left", "Entfernung") . td(0, "right", "<div id=\"w\" name=\"w\">$dist</div>"));
        $ausgabe .= tr(td(0, "left", "Dauer (eine Strecke)") . td(0, "right", "<div id=\"x\" name=\"x\">$daur</div>"));
        $ausgabe .= tr(td(0, "left", "Ankunftszeit") . td(0, "right", "<div id=\"z\" name=\"z\">$ankz</div>"));
        $ausgabe .= tr(td(0, "left", "Rückkehrzeit") . td(0, "right", "<div id=\"y\" name=\"y\">$ruez</div>"));

        include_once("miss_script.inc.php");
        $ausgabe .= tr(td(2, "navi", input("submit", "aktion", "Weiter")));
    } elseif ($inhalte_s["urlaub"] < time() && ($mt <= 0 && $aktion == "Weiter" && $missok == 1 && $mkx > 0 && $mky > 0 && $mkz > 0 && ($me1 > 0 || $me2 > 0 || $me3 > 0 || $me4 > 0 || $me5 > 0 || $me6 > 0 || $me7 > 0 || $me8 > 0)) || ($aktion == "Auftrag erteilen" && $missok == 1 && $mkx > 0 && $mky > 0 && $mkz > 0 && ($mt < 1 || $mt > 7 || $mt == 5))) {
        $x = $bk[0];
        $y = $bk[1];
        $z = $bk[2];

        $mkx = intval($mkx);
        $mky = intval($mky);
        $mkz = intval($mkz);
        $mg = intval($mg);
        if ($mg < 5) $mg = 5;
        if ($mg > 100) $mg = 100;

        $ausgabe .= input("hidden", "mkx", $mkx);
        $ausgabe .= input("hidden", "mky", $mky);
        $ausgabe .= input("hidden", "mkz", $mkz);
        $ausgabe .= input("hidden", "mg", $mg);

        /*
        $dist = 500;
        $dist3 = 500;
        $dist4 = 700;
        */

        $dist = round(sqrt(pow(abs($z - $mkz) * 100, 2) + pow(abs($y - $mky) * 100, 2) + pow(abs($x - $mkx) * 100, 2)), 5);
        $dist3 = round(sqrt(abs($z - $mkz) + abs($y - $mky) + abs($x - $mkx)) / 10, 5) + 1;
        $dist4 = round(($dist + 300) / $dist3, 5);

        $result2 = mysql_query("SELECT name,bname,punkte,typ FROM genesis_basen WHERE koordx='$mkx' AND koordy='$mky' AND koordz='$mkz'");
        $inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
        if (!$inhalte2["name"]) {
            $inhalte2["name"] = "unbesetzt";
            $inhalte2["punkte"] = "0";
            $inhalte2["bname"] = "unbekannt";
        }

        $result1 = mysql_query("SELECT id,alli,urlaub,gesperrt,deffs FROM genesis_spieler WHERE name='" . $inhalte2["name"] . "'");
        $inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);

        $noob = isnoob($sid, $inhalte1["id"]);

        $ladekap = 0;
        $ebedarf = 0;
        $ges = 100000;
        for ($i = 1; $i <= 8; $i++) {
            $anz = 0;
            eval("\$anz = \$me$i;");
            if ($anz > 0) {
                $result = mysql_query("SELECT wert1,wert5,wert6 FROM genesis_infos WHERE typ='prod$i'");
                $inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
                if ($ges > geschw($inhalte["wert6"], $inhalte_s["forsch2"])) $ges = geschw($inhalte["wert6"], $inhalte_s["forsch2"]);
                $ausgabe .= input("hidden", "me$i", $anz);
                $ladekap += $anz * ladekap($inhalte["wert5"], $inhalte_s["forsch6"]);
                $ebedarf += $anz * verbrauch($inhalte["wert1"]) / 2;
            }
        }

        $dauer = round((100 / $mg) * $dist4 / $ges * 4000, 0);
        include "dauer.inc.php";
        $teiler = 1;

        $ausgabe .= table(550, "bg");
        $ausgabe .= tr(td(2, "head", "Missionsinformation"));

        if ($noob == true) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Missionsziel ist im Noobschutz</font>"));
        if ($inhalte1["urlaub"] > time()) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Missionsziel ist im Urlaubsmodus</font>"));
        if ($inhalte1["gesperrt"] > time()) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Missionsziel ist gesperrt<br/>(es wird keine Zelle zerstört, aber Nährstoffe geplündert)</font>"));
        if ($inhalte_s["alli"] == $inhalte1["alli"] && $inhalte_s["alli"] > 0) $ausgabe .= tr(td(2, "center", "<font color=lime>Symbiose-Mitglied</font>"));

        $result_p = mysql_query("SELECT alli1,typ FROM genesis_politik WHERE bis>'" . time() . "' AND ((alli1='" . $inhalte1["alli"] . "' and alli2='" . $inhalte_s["alli"] . "') or (alli2='" . $inhalte1["alli"] . "'and alli1='" . $inhalte_s["alli"] . "'))");
        $inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC);
        if ($inhalte_p["typ"] == 7) $ausgabe .= tr(td(2, "center", "<font color=\"lime\">Bündnis-Partner</font>"));
        if ($inhalte_p["typ"] == 5) {
            $ausgabe .= tr(td(2, "center", "<font color=\"red\">Kriegs-Gegner</font>"));
            $teiler = 2;
        }
        if ($inhalte_p["typ"] == 3 && $inhalte_p["alli1"] == $inhalte_s["alli"]) $ausgabe .= tr(td(2, "center", "<font color=\"red\">Feind</font>"));
        if ($inhalte_p["typ"] == 1 && $inhalte_p["alli1"] == $inhalte_s["alli"]) $ausgabe .= tr(td(2, "center", "<font color=\"lime\">Freund</font>"));

        unset($inhaltea, $resulta, $result_p, $inhalte_p);

        $allitag = "";
        if ($inhalte1["alli"] != 0) {
            $resulta = mysql_query("SELECT tag FROM genesis_allianzen WHERE id='" . $inhalte1["alli"] . "'");
            $inhaltea = mysql_fetch_array($resulta, MYSQL_ASSOC);
            $allitag = "[" . $inhaltea["tag"] . "] ";
        }
        unset($inhaltea, $resulta);

        $ausgabe .= tr(td(0, "left", "Startpunkt") . td(0, "right", $inhalte_s["basis$b"]));
        $ausgabe .= tr(td(0, "left", "Zielkoordinaten") . td(0, "right", "$mkx:$mky:$mkz"));
        $ausgabe .= tr(td(0, "left", "Neogen / Punkte") . td(0, "right", $inhalte2["bname"] . " / " . format($inhalte2["punkte"])));
        $ausgabe .= tr(td(0, "left", "Spieler") . td(0, "right", $allitag . $inhalte2["name"]));
        $ausgabe .= tr(td(0, "left", "Entfernung") . td(0, "right", format(round($dist, 0))));
        $ausgabe .= tr(td(0, "left", "Dauer (eine Strecke)") . td(0, "right", "$h:$m:$s"));
        $ausgabe .= tr(td(0, "left", "Ankunftszeit") . td(0, "right", "<font id=\"timer\" title=\"$zeitpunkt\">$zeitpunkt</font><script language=JavaScript>init_countdown ('timer', '$dauer', '" . date("M, d Y H:i:s") . "', '', '');</script>"));
        $dauer = round((100 / $mg) * $dist4 / $ges * 4000, 2) * 2;
        include "dauer.inc.php";
        $ausgabe .= tr(td(0, "left", "Rückkehrzeit") . td(0, "right", "<font id=\"timer2\" title=\"$zeitpunkt\">$zeitpunkt</font><script language=JavaScript>init_countdown ('timer2', '$dauer', '" . date("M, d Y H:i:s") . "', '', '');</script>"));

        $ebedarf = round($dist4 * $ebedarf / 650, 0) + round($ebedarf * sqrt($mg) / 6, 0) + 1;

        if ($ebedarf > $inhalte_b["ress5"]) {
            $ausgabe .= tr(td(0, "left", "Energiebedarf") . td(0, "right", "<font class=\"nein\">" . format($ebedarf) . "</font>"));
        } else {
            $ausgabe .= tr(td(0, "left", "Energiebedarf") . td(0, "right", "<font class=\"ja\">" . format($ebedarf) . "</font>"));
        }

        if ($ebedarf > $ladekap) {
            $ausgabe .= tr(td(0, "left", "Ladekapazität") . td(0, "right", "<font class=\"nein\">überschritten</font>"));
        } else {
            $ausgabe .= tr(td(0, "left", "Ladekapazität (verbleibend)") . td(0, "right", "<font class=\"ja\" name=\"gesamt\" id=\"gesamt\">" . format($ladekap - $ebedarf) . "</font>"));
        }

        $ausgabe .= "</table><br/>\n";

        $ausgabe .= table(550, "bg");
        $ausgabe .= tr(td(2, "head", "Missionstyp"));

        if (!islocked($sid, $inhalte1["id"]) && ($inhalte1["deffs"] < 8 || $inhalte1["log"] < (time() - 86400 * 7))) {
            if ((time() > 1222120800) && $inhalte2["name"] != $name && $inhalte2["name"] != "unbesetzt" && $noob == false && $inhalte1["urlaub"] < time() && ($me1 > 0 || $me2 > 0 || $me3 > 0 || $me4 > 0 || $me5 > 0 || $me7 > 0 || $me8 > 0)) {
                $out1 = input("radio", "mt", "1") . " Angriff";
                $out2 = "Plünderung der Nährstoffe in folgender Reihenfolge:<br/>\n";
                $out2 .= " <select name=mrp1><option value=1 selected>Adenin</option><option value=2>Thymin</option><option value=3>Guanin</option><option value=4>Cytosin</option><option value=5>ATP</option></select>";
                $out2 .= " <select name=mrp2><option value=1>Adenin</option><option value=2 selected>Thymin</option><option value=3>Guanin</option><option value=4>Cytosin</option><option value=5>ATP</option></select>";
                $out2 .= " <select name=mrp3><option value=1>Adenin</option><option value=2>Thymin</option><option value=3 selected>Guanin</option><option value=4>Cytosin</option><option value=5>ATP</option></select>";
                $out2 .= " <select name=mrp4><option value=1>Adenin</option><option value=2>Thymin</option><option value=3>Guanin</option><option value=4 selected>Cytosin</option><option value=5>ATP</option></select>";
                $out2 .= " <select name=mrp5><option value=1>Adenin</option><option value=2>Thymin</option><option value=3>Guanin</option><option value=4>Cytosin</option><option value=5 selected>ATP</option></select>";
                if ($inhalte_b["konst14"] > 5) {
                    if ($inhalte_s["attzeit"] < (time() - (6 * 3600 / $teiler) + round((100 / $mg) * $dist4 / $ges * 3000, 0))) {
                        $out2 .= "<br/>\n" . input("checkbox", "gematt", "1") . " gemeinsamer Angriff\n";
                    } else {
                        $out2 .= "<br/>\n" . date("H:i:s (d.m.Y)", $inhalte_s["attzeit"] + (6 * 3600 / $teiler)) . " ist ein gemeinsamer Angriff wieder möglich\n";
                    }
                }
                $ausgabe .= tr(td(0, "left", $out1) . td(0, "right", $out2));
            }
        } else {
            $ausgabe .= tr(td(0, "left", "Angriff") . td(0, "right", "gesperrt"));
        }

        if ("$x:$y:$z" != "$mkx:$mky:$mkz" && $inhalte2["name"] != "unbesetzt" && $inhalte1["urlaub"] < time()) {
            if (!islocked($sid, $inhalte1["id"])) {
                $ladekap -= $ebedarf;
                if ($ladekap > 0) {
                    $fuell1 = $inhalte_b["ress1"];
                    $fuell2 = $inhalte_b["ress2"];
                    $fuell3 = $inhalte_b["ress3"];
                    $fuell4 = $inhalte_b["ress4"];
                    $fuell5 = $inhalte_b["ress5"] - $ebedarf;
                    if ($fuell5 < 0) $fuell5 = 0;
                    if ($ladekap < $inhalte_b["ress1"]) $fuell1 = $ladekap;
                    if ($ladekap < $inhalte_b["ress2"]) $fuell2 = $ladekap;
                    if ($ladekap < $inhalte_b["ress3"]) $fuell3 = $ladekap;
                    if ($ladekap < $inhalte_b["ress4"]) $fuell4 = $ladekap;
                    if ($ladekap < $inhalte_b["ress5"]) $fuell5 = $ladekap;
                    $out1 = input("radio", "mt", "2") . " Transport";
                    if ("$mkx:$mky:$mkz" == $inhalte_s["basis1"] || "$mkx:$mky:$mkz" == $inhalte_s["basis2"]) {
                        $out1 .= "<br/>\n<input id=\"chkstao\" style=\"border:none\" type=\"checkbox\" name=\"stao\" value=\"1\" onclick=\"$('chksave').checked='';\"> Stationierung\n";
                        $out1 .= "<br/>\n<input id=\"chksave\" style=\"border:none\" type=\"checkbox\" name=\"save\" value=\"1\" onclick=\"$('chkstao').checked='';\"> Nährstoffe nicht abladen\n";
                    }
                    $out2 = "Adenin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir1\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir1','$fuell1');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                    $out2 .= "Thymin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir2\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir2','$fuell2');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                    $out2 .= "Guanin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir3\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir3','$fuell3');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                    $out2 .= "Cytosin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir4\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir4','$fuell4');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                    $out2 .= "ATP <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir5\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir5','$fuell5');checkfuell('$ladekap');\">Max</a>)";
                    $ausgabe .= tr(td(0, "left", $out1) . td(0, "right", $out2));
                }
            } else {
                $ausgabe .= tr(td(0, "left", "Transport") . td(0, "right", "gesperrt"));
            }
            $result_p = mysql_query("SELECT id FROM genesis_politik WHERE typ='7' and accept='1' and ((alli1='" . $inhalte1["alli"] . "' and alli2='" . $inhalte_s["alli"] . "') or (alli2='" . $inhalte1["alli"] . "'and alli1='" . $inhalte_s["alli"] . "'))");
            if (($inhalte1["alli"] == $inhalte_s["alli"] || mysql_fetch_array($result_p, MYSQL_ASSOC)) && ($me1 > 0 || $me2 > 0 || $me3 > 0 || $me4 > 0 || $me5 > 0 || $me7 > 0) && $inhalte_b["konst14"] > 2) {
                if ($inhalte_s["deffzeit"] < (time() - (6 * 3600 / $teiler) + round((100 / $mg) * $dist4 / $ges * 3000, 0))) {
                    $out1 = input("radio", "mt", "4") . " Verteidigung";
                    $out2 = "Verweildauer: <select name=vd><option value=1>1 Stunde</option><option value=2>2 Stunden</option><option value=3>3 Stunden</option><option value=4>4 Stunden</option><option value=5>5 Stunden</option></select>";
                    $ausgabe .= tr(td(0, "left", $out1) . td(0, "right", $out2));
                } else {
                    $out1 = "Verteidigung";
                    $out2 = date("H:i:s (d.m.Y)", $inhalte_s["deffzeit"] + (24 * 3600)) . " ist eine Verteidigung wieder möglich";
                    $ausgabe .= tr(td(0, "left", $out1) . td(0, "right", $out2));
                }
            }
        } elseif ("$x:$y:$z" != "$mkx:$mky:$mkz" && $inhalte2["name"] == "unbesetzt" && $me1 == 0 && $me2 == 0 && $me3 == 0 && $me4 == 0 && $me5 == 0 && $me7 == 0 && $me8 == 1) {
            $ladekap -= $ebedarf;
            if ($ladekap > 0) {
                $fuell1 = $inhalte_b["ress1"];
                $fuell2 = $inhalte_b["ress2"];
                $fuell3 = $inhalte_b["ress3"];
                $fuell4 = $inhalte_b["ress4"];
                $fuell5 = $inhalte_b["ress5"] - $ebedarf;
                if ($fuell5 < 0) $fuell5 = 0;
                if ($ladekap < $inhalte_b["ress1"]) $fuell1 = $ladekap;
                if ($ladekap < $inhalte_b["ress2"]) $fuell2 = $ladekap;
                if ($ladekap < $inhalte_b["ress3"]) $fuell3 = $ladekap;
                if ($ladekap < $inhalte_b["ress4"]) $fuell4 = $ladekap;
                if ($ladekap < $inhalte_b["ress5"]) $fuell5 = $ladekap;
                $out1 = input("radio", "mt", "6") . " Zellteilung";
                $out2 = "Adenin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir1\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir1','$fuell1');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                $out2 .= "Thymin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir2\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir2','$fuell2');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                $out2 .= "Guanin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir3\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir3','$fuell3');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                $out2 .= "Cytosin <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir4\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir4','$fuell4');checkfuell('$ladekap');\">Max</a>)<br/>\n";
                $out2 .= "ATP <input style=\"text-align:right\" size=\"10\" maxlength=\"9\" type=\"text\" name=\"mir5\" value=\"0\" onkeyup=\"checkfuell('$ladekap');\" onclick=\"checkfuell('$ladekap');\"> (<a href=\"#\" onclick=\"fuell('mir5','$fuell5');checkfuell('$ladekap');\">Max</a>)";
                $ausgabe .= tr(td(0, "left", $out1) . td(0, "right", $out2));
            }
        } else {
            $fehler = true;
        }
        if (!islocked($sid, $inhalte1["id"]) && $me1 == 0 && $me2 == 0 && $me3 == 0 && $me4 == 0 && $me5 == 0 && $me6 > 0 && $me7 == 0 && $me8 == 0 && $inhalte2["name"] != $name && $inhalte2["name"] != "unbesetzt" && $inhalte1["urlaub"] < time()) $ausgabe .= tr(td(0, "left", input("radio", "mt", "3") . " Spionage") . td(0, "right", "&nbsp;"));
        // Osternspecial
        /*
        $resultm1 = mysql_query("SELECT count(*) as anz FROM genesis_aktionen WHERE basis1='" . $inhalte_s["basis$b"] . "' AND typ='miss' AND aktion='7'");
        $inhaltem1 = mysql_fetch_array($resultm1, MYSQL_ASSOC);
        if ($inhaltem1["anz"] == 0 && $me1 == 0 && $me2 == 0 && $me3 == 0 && $me4 == 0 && $me5 == 0 && $me6 == 0 && $me7 > 0 && $me8 == 0 && $inhalte2["name"] != $name && $inhalte2["name"] != "unbesetzt" && $inhalte1["urlaub"] < time()) $ausgabe .= tr(td(0, "left", input("radio", "mt", "7") . " Eiersuche") . td(0, "right", "&nbsp;"));
        */
        // Osternspecial
        if ($fehler == false) {
            $ausgabe .= tr(td(2, "center", input("submit", "aktion", "Auftrag erteilen")));
        } else {
            $ausgabe .= tr(td(2, "center", "kein Auftrag möglich"));
        }
    } elseif ($inhalte_s["urlaub"] < time() && $aktion == "Auftrag erteilen" && $missok == 1 && $mkx > 0 && $mky > 0 && $mkz > 0 && ($mt == 1 || $mt == 2 || $mt == 3 || $mt == 4 || $mt == 6)) {
        $x = $bk[0];
        $y = $bk[1];
        $z = $bk[2];

        $teiler = 1;
        $fehler = false;
        $vd = intval($vd);
        if ($vd < 1) $vd = 1;
        if ($vd > 5) $vd = 5;
        $mg = intval($mg);
        if ($mg < 5) $mg = 5;
        if ($mg > 100) $mg = 100;

        for ($i = 1; $i <= 8; $i++) {
            eval("\$me$i = abs((int)\$me$i);
if (\$me$i > \$inhalte_b[\"prod$i\"]) \$fehler = true;
\$en$i = \$inhalte_b[\"prod$i\"] - \$me$i;");
        }

        $mrg = 0;

        $dist = round(sqrt(pow(abs($z - $mkz) * 100, 2) + pow(abs($y - $mky) * 100, 2) + pow(abs($x - $mkx) * 100, 2)), 5);
        $dist3 = round(sqrt(abs($z - $mkz) + abs($y - $mky) + abs($x - $mkx)) / 10, 5) + 1;
        $dist4 = round(($dist + 300) / $dist3, 5);

        /*
        $dist = 500;
        $dist3 = 500;
        $dist4 = 700;
        */

        $ladekap = 0;
        $ebedarf = 0;
        $ges = 100000;
        for ($i = 1; $i <= 8; $i++) {
            eval("\$anz = \$me$i;");
            if ($anz > 0) {
                $result = mysql_query("SELECT wert1,wert5,wert6 FROM genesis_infos WHERE typ='prod$i'");
                $inhalte = mysql_fetch_array($result, MYSQL_ASSOC);
                if ($ges > geschw($inhalte["wert6"], $inhalte_s["forsch2"])) $ges = geschw($inhalte["wert6"], $inhalte_s["forsch2"]);
                $ladekap += $anz * ladekap($inhalte["wert5"], $inhalte_s["forsch6"]);
                $ebedarf += $anz * verbrauch($inhalte["wert1"]) / 2;
            }
        }
        $result2 = mysql_query("SELECT name,bname,punkte,typ FROM genesis_basen WHERE koordx='$mkx' AND koordy='$mky' AND koordz='$mkz'");
        $inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC);
        $result1 = mysql_query("SELECT id,alli,urlaub,gesperrt,deffs FROM genesis_spieler WHERE name='" . $inhalte2["name"] . "'");
        $inhalte1 = mysql_fetch_array($result1, MYSQL_ASSOC);
        $result_p = mysql_query("SELECT alli1,typ FROM genesis_politik WHERE bis>'" . time() . "' AND ((alli1='" . $inhalte1["alli"] . "' and alli2='" . $inhalte_s["alli"] . "') or (alli2='" . $inhalte1["alli"] . "'and alli1='" . $inhalte_s["alli"] . "'))");
        $inhalte_p = mysql_fetch_array($result_p, MYSQL_ASSOC);
        $noob = isnoob($sid, $inhalte1["id"]);
        if ($inhalte_p["typ"] == 5) $teiler = 2;
        $teiler = 2;

        if ($fehler == false) {
            if ($inhalte2["name"] != "Administrator" || $name == "Administrator") {
                if (!islocked($sid, $inhalte1["id"]) || $mt == 4) {
                    if (($mkx != $x || $mky != $y || $mkz != $z) && ($inhalte2["name"] != "" || $mt == 6)) {
                        if (($inhalte_s["angriffe"] <= 20 && ($mt == 1 || $mt == 4)) || ($mt != 1 && $mt != 4)) {
                            if ((($inhalte1["deffs"] < 8 || $inhalte1["log"] < (time() - 86400 * 7)) && $mt == 1) || ($mt != 1)) {
                                if ((time() > 1222120800 && $mt == 1) || ($mt != 1)) {
                                    if (($noob == true && $mt != 1) || ($noob == false)) {
                                        if (($inhalte_b["konst14"] > 5 && $mt == 1 && $gematt == 1 && ($inhalte_s["attzeit"] < (time() - (6 * 3600 / $teiler) + round((100 / $mg) * $dist4 / $ges * 3000, 0)))) || ($mt == 1 && $gematt == 0) || $mt != 1) {
                                            if (($inhalte_b["konst14"] > 2 && $mt == 4) || $mt != 4) {
                                                if ((($inhalte_s["deffzeit"] < (time() - (6 * 3600 / $teiler) + round((100 / $mg) * $dist4 / $ges * 3000, 0))) && $mt == 4) || $mt != 4) {
                                                    // $result_p = mysql_query("SELECT id FROM genesis_politik WHERE typ='7' and accept='1' and ((alli1='" . $inhalte1["alli"] . "' and alli2='" . $inhalte_s["alli"] . "') or (alli2='" . $inhalte1["alli"] . "'and alli1='" . $inhalte_s["alli"] . "'))");
                                                    // if ((($inhalte1["alli"] == $inhalte_s["alli"] || mysql_fetch_array($result_p, MYSQL_ASSOC)) && $mt == 4) || $mt != 4) {
                                                    if ($inhalte1["urlaub"] < time()) {
                                                        $ebedarf = round($dist4 * $ebedarf / 650, 0) + round($ebedarf * sqrt($mg) / 6, 0) + 1;
                                                        $ladekap -= $ebedarf;
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            eval("\$mir$i = abs((int)\$mir$i);
if (\$mt != 2 && \$mt != 6) { \$mir$i = 0; }
if (\$mir$i > \$inhalte_b[\"ress$i\"]) {
    if ($i == 5) {
        \$mir$i = \$inhalte_b[\"ress$i\"] - \$ebedarf;
    } else {
        \$fehler = true;
    }
}
\$rn$i = \$inhalte_b[\"ress$i\"] - \$mir$i;
\$mrg += \$mir$i;");
                                                        }

                                                        if ($fehler == false) {
                                                            if ($inhalte_b["ress5"] >= ($ebedarf + $mir5)) {
                                                                if ($ladekap >= $mrg) {
                                                                    $rn5 -= $ebedarf;
                                                                    $dauer = round((100 / $mg) * $dist4 / $ges * 4000, 2);
                                                                    $endzeit = time() + $dauer;
                                                                    $einheiten = "$me1||$me2||$me3||$me4||$me5||$me6||$me7||$me8";
                                                                    $missress = "$mir1||$mir2||$mir3||$mir4||$mir5||$ebedarf";
                                                                    if ($mt == 1) {
                                                                        $missress1 = "$mrp1||$mrp2||$mrp3||$mrp4||$mrp5||$ebedarf";
                                                                        $mrpar = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
                                                                        for ($i = 1; $i <= 5; $i++) eval("\$mrpar[\$mrp$i] = 1;");
                                                                        for ($i = 1; $i <= 5; $i++) {
                                                                            for ($j = $i + 1; $j <= 5; $j++) {
                                                                                eval("if (\$mrp$i == \$mrp$j) {
\$mrp$j = array_search(0, \$mrpar);
\$mrpar[\$mrp$j] = 1;
}");
                                                                            }
                                                                        }
                                                                        $missress = "$mrp1||$mrp2||$mrp3||$mrp4||$mrp5||$ebedarf";
                                                                        if ($missress != $missress1) $ausgabe .= tr(td(2, "center", "<font color=red>Plünderprioität korrigiert</font>"));
                                                                    }
                                                                    if ($mt == 1 && $gematt == 0) $vd = 0;
                                                                    if ($mt == 2 && $stao == 0 && $save == 0) $vd = 0;
                                                                    if ($mt == 2 && $stao == 1 && $save == 0) $vd = 1;
                                                                    if ($mt == 2 && $save == 1 && $stao == 0) $vd = 2;

                                                                    if ($mt == 1 || $mt == 4) mysql_query("UPDATE genesis_spieler SET angriffe=angriffe+1 WHERE id='$sid'");
                                                                    if ($mt == 1 && $gematt == 1) mysql_query("UPDATE genesis_spieler SET attzeit='$endzeit' WHERE id='$sid'");
                                                                    if ($mt == 4) mysql_query("UPDATE genesis_spieler SET deffzeit='$endzeit' WHERE id='$sid'");
                                                                    mysql_query("UPDATE genesis_basen SET ress1='$rn1', ress2='$rn2', ress3='$rn3', ress4='$rn4', ress5='$rn5', prod1='$en1', prod2='$en2', prod3='$en3', prod4='$en4', prod5='$en5', prod6='$en6', prod7='$en7', prod8='$en8' WHERE name='$name' and koordx='" . $bk[0] . "' and koordy='" . $bk[1] . "' and koordz='" . $bk[2] . "'");
                                                                    if ($mt != 3) mysql_query("INSERT INTO genesis_log (name, ip, zeit, aktion) VALUES ('$name', '" . $REMOTE_ADDR . "', '" . time() . "', 'START: miss $mt - " . $inhalte_s["basis$b"] . " - $mkx:$mky:$mkz - $einheiten - $missress - $vd')");
                                                                    mysql_query("INSERT INTO genesis_aktionen (startzeit, endzeit, basis1, basis2, typ, aktion, einheiten, ress, zusatz) VALUES ('" . time() . "', '$endzeit', '" . $inhalte_s["basis$b"] . "', '$mkx:$mky:$mkz', 'miss', '$mt', '$einheiten', '$missress', '$vd')");
                                                                    $mid = mysql_insert_id();
                                                                    $newmiss = 1;
                                                                    include("missinfo.inc.php");
                                                                } else {
                                                                    $ausgabe .= tr(td(0, "head", "Mission"));
                                                                    $ausgabe .= tr(td(0, "center", "<font color=\"red\">Fehler! Ladekapazität überschritten</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                                                }
                                                            } else {
                                                                $ausgabe .= tr(td(0, "head", "Mission"));
                                                                $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! ATP-Vorrat nicht ausreichend</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                                            }
                                                        } else {
                                                            $ausgabe .= tr(td(0, "head", "Mission"));
                                                            $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Nicht genug Nährstoffe vorhanden</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                                        }
                                                    } else {
                                                        $ausgabe .= tr(td(0, "head", "Mission"));
                                                        $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Missionsziel ist im Urlaubsmodus</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                                    }
                                                    /*} else {
                                            $ausgabe .= tr(td(0, "head", "Mission"));
                                            $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Verteidigung nur bei Symbiosemitgliedern/Bündnispartnern</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                        }*/
                                                } else {
                                                    $ausgabe .= tr(td(0, "head", "Mission"));
                                                    $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Verteidigung nur aller 12h möglich</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                                }
                                            } else {
                                                $ausgabe .= tr(td(0, "head", "Mission"));
                                                $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Verteidigung erst ab Kleinhirn Stufe 3 möglich</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                            }
                                        } else {
                                            $ausgabe .= tr(td(0, "head", "Mission"));
                                            $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Gemeinsamer Angriff erst ab Kleinhirn Stufe 6 und nur aller 12h möglich</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                        }
                                    } else {
                                        $ausgabe .= tr(td(0, "head", "Mission"));
                                        $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Missionsziel ist im Noobschutz</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                    }
                                } else {
                                    $ausgabe .= tr(td(0, "head", "Mission"));
                                    $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Angriffssperre</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                                }
                            } else {
                                $ausgabe .= tr(td(0, "head", "Mission"));
                                $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! 8 Angriffe pro Tag auf diesen Spieler erreicht</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                            }
                        } else {
                            $ausgabe .= tr(td(0, "head", "Mission"));
                            $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! 20 Angriffe/Verteidigungen pro Tag erreicht</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                        }
                    } else {
                        $ausgabe .= tr(td(0, "head", "Mission"));
                        $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Zielkoordinaten führen zu unbesetztem/eigenem Neogen</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                    }
                } else {
                    $ausgabe .= tr(td(0, "head", "Mission"));
                    $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Durch Multi-Schutz sind Missionen zu diesem Spieler gesperrt.</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
                }
            } else {
                $ausgabe .= tr(td(0, "head", "Mission"));
                $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Administrator-Account ist kein Missionsziel</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
            }
        } else {
            $ausgabe .= tr(td(0, "head", "Mission"));
            $ausgabe .= tr(td(2, "center", "<font color=\"red\">Fehler! Nicht genug Exo-Zellen vorhanden</font><br/><br/><a href=\"#\" onclick=\"history.back()\">zurück</a>"));
        }
    } elseif ($aktion == "info" && $mid > 0) {
        include("missinfo.inc.php");
    }
} else {
    $ausgabe .= tr(td(0, "head", "Mission"));
    $ausgabe .= tr(td(0, "center", "Bevor du Missionen starten kannst, musst du ein Kleinhirn entwickeln."));
}

unset($ebedarf, $ladekap, $dist, $dist3, $dist4, $mt, $inhalte, $result, $inhalte1, $result1, $inhalte2, $result2, $inhalte3, $result3, $mrp1, $mrp2, $mrp3, $mrp4, $mrp5, $mrpar, $rn1, $rn2, $rn3, $rn4, $rn5, $en1, $en2, $en3, $en4, $en5, $en6, $en7, $en8, $en9, $mkx, $mkz, $mky, $missress, $einheiten, $vd, $endzeit, $zeit);

$ausgabe .= "</table></form>\n";

?>