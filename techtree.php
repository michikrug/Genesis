<?php

$result2 = mysql_query("SELECT typ, bezeichnung FROM genesis_infos");
while($inhalte2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
	$namen[$inhalte2["typ"]] = $inhalte2["bezeichnung"];
}

$ausgabe .= table(550, "bg");
$ausgabe .= tr(td(2, "head", "Techtree"));
$ausgabe .= tr(td(2, "navi", "Anzeige: <a href=# onclick=\"shown('nh')\">alles</a> - <a href=# onclick=\"shown('')\">nur fehlendes</a>"));
$ausgabe .= tr(td(2, "center", "&nbsp;"));
$ausgabe .= tr(td(2, "head", "Ausbauten"));

$out = "-";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst1", $namen["konst1"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst1"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst1"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst2", $namen["konst2"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst1"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst1"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst3", $namen["konst3"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst1"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst1"] ." Stufe 3</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst4", $namen["konst4"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst1"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst1"] ." Stufe 3</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst5", $namen["konst5"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst1"] > 4) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst1"] ." Stufe 5</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst6", $namen["konst6"])) . td(0, "right", $out));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$out = "<font class=\"";
if ($inhalte_b["konst1"] > 7) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst1"] ." Stufe 8<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 2</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst7", $namen["konst7"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst1"] > 5) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst1"] ." Stufe 6</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst8", $namen["konst8"])) . td(0, "right", $out));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$out = "<font class=\"";
if ($inhalte_b["konst2"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst2"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst9", $namen["konst9"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst3"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst3"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst10", $namen["konst10"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst4"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst4"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst11", $namen["konst11"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst5"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst5"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst12", $namen["konst12"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst6"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst13", $namen["konst13"])) . td(0, "right", $out));

$ausgabe .= tr(td(2, "center", "&nbsp;"));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst14", $namen["konst14"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_s["forsch4"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 2</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst15", $namen["konst15"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_s["forsch5"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch5"] ." Stufe 2<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 3</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst16", $namen["konst16"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_s["forsch7"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 3<br/></font>
<font class=\"";
if ($inhalte_s["forsch8"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch8"] ." Stufe 2</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=konst17", $namen["konst17"])) . td(0, "right", $out));

$ausgabe .= tr(td(2, "center", "&nbsp;"));
$ausgabe .= tr(td(2, "head", "Evolutionen"));

$out = "<font class=\"";
if ($inhalte_b["konst8"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst8"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch1", $namen["forsch1"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst8"] > 4) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst8"] ." Stufe 5</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch2", $namen["forsch2"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst8"] > 14) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst8"] ." Stufe 15<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 10<br/></font>
<font class=\"";
if ($inhalte_s["forsch2"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch2"] ." Stufe 10</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch3", $namen["forsch3"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst8"] > 3) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst8"] ." Stufe 4<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch4", $namen["forsch4"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst8"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst8"] ." Stufe 3</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch5", $namen["forsch5"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst8"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst8"] ." Stufe 1<br/></font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch6", $namen["forsch6"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst8"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst8"] ." Stufe 2</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch7", $namen["forsch7"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst15"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst15"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=forsch8", $namen["forsch8"])) . td(0, "right", $out));

$ausgabe .= tr(td(2, "center", "&nbsp;"));
$ausgabe .= tr(td(2, "head", "Exo-Zellen"));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 2</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod1", $namen["prod1"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 4) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 5<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 3<br/></font>
<font class=\"";
if ($inhalte_s["forsch2"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch2"] ." Stufe 3<br/></font>
<font class=\"";
if ($inhalte_s["forsch4"] > 3) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 4<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 3) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 4<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 5) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 6</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod2", $namen["prod2"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 10<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 7) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 8<br/></font>
<font class=\"";
if ($inhalte_s["forsch2"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch2"] ." Stufe 10<br/></font>
<font class=\"";
if ($inhalte_s["forsch4"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 10<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 7) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 8<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 11) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 12</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod3", $namen["prod3"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 14) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 15<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 14) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 15<br/></font>
<font class=\"";
if ($inhalte_s["forsch2"] > 11) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch2"] ." Stufe 12<br/></font>
<font class=\"";
if ($inhalte_s["forsch4"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 10<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 11) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 12<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 15) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 16</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod4", $namen["prod4"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 19) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 20<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 19) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 20<br/></font>
<font class=\"";
if ($inhalte_s["forsch2"] > 17) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch2"] ." Stufe 18<br/></font>
<font class=\"";
if ($inhalte_s["forsch3"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch3"] ." Stufe 10<br/></font>
<font class=\"";
if ($inhalte_s["forsch4"] > 14) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 15<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 17) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 18<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 19) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 20</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod5", $namen["prod5"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch4"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 1<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 2<br/></font>
<font class=\"";
if ($inhalte_s["forsch8"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch8"] ." Stufe 1</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod6", $namen["prod6"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 2<br/></font>
<font class=\"";
if ($inhalte_s["forsch1"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 2<br/></font>
<font class=\"";
if ($inhalte_s["forsch2"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch2"] ." Stufe 2<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 3<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 1) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 2</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod7", $namen["prod7"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst7"] > 10) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst7"] ." Stufe 11<br/></font>
<font class=\"";
if ($inhalte_s["forsch3"] > 5) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch3"] ." Stufe 6<br/></font>
<font class=\"";
if ($inhalte_s["forsch4"] > 7) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 8<br/></font>
<font class=\"";
if ($inhalte_s["forsch6"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch6"] ." Stufe 10<br/></font>
<font class=\"";
if ($inhalte_s["forsch7"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 10</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=prod8", $namen["prod8"])) . td(0, "right", $out));

$ausgabe .= tr(td(2, "center", "&nbsp;"));
$ausgabe .= tr(td(2, "head", "Verteidigung"));

$out = "<font class=\"";
if ($inhalte_b["konst15"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst15"] ." Stufe 1<br/></font><font class=\"";
if ($inhalte_s["forsch1"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 1<br/></font><font class=\"";
if ($inhalte_s["forsch4"] > 0) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 1<br/></font><font class=\"";
if ($inhalte_s["forsch7"] > 2) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 3</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=vert1", $namen["vert1"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst15"] > 5) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst15"] ." Stufe 6<br/></font><font class=\"";
if ($inhalte_s["forsch1"] > 4) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 5<br/></font><font class=\"";
if ($inhalte_s["forsch4"] > 6) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 7<br/></font><font class=\"";
if ($inhalte_s["forsch7"] > 7) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 8</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=vert2", $namen["vert2"])) . td(0, "right", $out));

$out = "<font class=\"";
if ($inhalte_b["konst15"] > 11) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["konst15"] ." Stufe 12<br/></font><font class=\"";
if ($inhalte_s["forsch1"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch1"] ." Stufe 10<br/></font><font class=\"";
if ($inhalte_s["forsch4"] > 9) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch4"] ." Stufe 10<br/></font><font class=\"";
if ($inhalte_s["forsch7"] > 14) { $out .= "ja\" name=\"nh\" id=\"nh"; } else { $out .= "nein"; }
$out .= "\">". $namen["forsch7"] ." Stufe 15</font>";
$ausgabe .= tr(td(0, "lefth", hlink("nc", "game.php?id=$id&b=$b&nav=info&t=vert3", $namen["vert3"])) . td(0, "right", $out));

$ausgabe .= "\n</table>";

?>