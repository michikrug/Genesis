<?php

$handle = opendir("images/codes");
while (false !== ($file = readdir($handle))) if (filetype("images/codes/$file") == "file" && filemtime("images/codes/$file") < (time()-120)) unlink("images/codes/$file");

$codezeit = round((time() + microtime()) * 10);
$bild = ImageCreate(250, 75);
srand((double)microtime() * 1000000);
mt_srand((double)microtime() * 1000000);
$schwarz = ImageColorAllocate($bild, 0, 0, 0);
$cods = " ";
$centerX = 125;
$centerY = 37;
for($i = 0; $i < 5; $i++) {
    do {
        $z0 = mt_rand(1, 2);
        if ($z0 == 1) {
            $z = mt_rand(48, 57);
        } elseif ($z0 == 2) {
            $z = mt_rand(65, 90);
            if ($z == 79) $z = 80;
        }
        $cod = chr($z);
    } while (strpos($cods, $cod) != false);
    $cods .= $cod;
    $l = mt_rand(20, 220);
    $fo = mt_rand(1, 4);
    if ($fo == 1) $font = realpath("georgiab.ttf");
    if ($fo == 2) $font = realpath("courbd.ttf");
    if ($fo == 3) $font = realpath("comicbd.ttf");
    if ($fo == 4) $font = realpath("futurab.ttf");
    $color = ImageColorAllocate($bild, mt_rand(25, 255), mt_rand(25, 255), mt_rand(25, 255));
    ImageTTFText($bild, rand(28, 35), mt_rand(-30, 30), $l, mt_rand(40, 60), $color, $font, $cod);
}
$dat = $l + 10;
mysql_query("DELETE FROM genesis_codes WHERE zeit < '" . $codezeit . "' - 3000");
$daten = mysql_query("SELECT code FROM genesis_codes WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "'");
if ($da = mysql_fetch_array($daten, MYSQL_ASSOC)) {
    mysql_query("UPDATE genesis_codes SET zeit='" . $codezeit . "', code='" . $dat . "' WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "'");
} else {
    mysql_query("INSERT INTO genesis_codes (ip, zeit, code) VALUES ('$ip', '" . $codezeit . "', '$dat')");
}
$out = "klicke auf <b>" . $cod . "</b>";
imagecolortransparent ($bild, $schwarz);
imagepng($bild, "images/codes/code" . $codezeit . ".png");
imagedestroy($bild);

?>