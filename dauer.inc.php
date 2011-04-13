<?php

$zeitpunkt = date("H:i:s (d.m.Y)", time() + $dauer);
$zeitpunkt2 = date("H:i:s", time() + $dauer);

$s = $dauer;
$h = 0;
$m = 0;
if ($s > 59) {
	$m = intval($s / 60);
	$s = intval($s - $m * 60);
}
if ($m > 59) {
	$h = intval($m / 60);
	$m = intval($m - $h * 60);
}
if (strlen($h) == 1) $h = "0$h";
if ($h >= 24) {
	$d = intval($h / 24);
	$h1 = $h - ($d * 24);
	if (strlen($h1) == 1) $h1 = "0$h1";
	if ($d > 1) {
		$h = "$d Tage $h1";
	} else {
		$h = "1 Tag $h1";
	}
}
if (strlen($m) == 1) $m = "0$m";
if (strlen($s) == 1) $s = "0$s";

?>