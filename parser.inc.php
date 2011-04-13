<?php

// Smilies umwandeln
function convertsmilies($message) {
	$orig = $repl = $test = array();
	$result_smilies = mysql_query("SELECT * FROM smilies");
	while($inhalte = @mysql_fetch_array($result_smilies)) $test[] = $inhalte;
	$smilies = $test;
	for ($i = 0; $i < count($smilies); $i++) {
		$orig[] = "/(?<=.\W|\W.|^\W)" . preg_quote($smilies[$i]['code'], "/") . "(?=.\W|\W.|\W$)/";
		$repl[] = '<img src="images/smilies/' . $smilies[$i]['smile_url'] . '" alt="' . $smilies[$i]['emoticon'] . '" />';
	}
	if (count($orig)) {
		$message = preg_replace($orig, $repl, ' ' . $message . ' ');
		$message = substr($message, 1, -1);
	}
	return $message;
}

// Zensierung böser Wörter
function censor ($text) {
	$search = array('fick','hure','Fäkalienlecker','drecksau');
	return str_replace($search, "-Zensiert-", $text);
}

// Link umwandeln
function converthttp ($text) {
	$ret = ' '. $text;
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
	$ret = substr($ret, 1);
	return $ret;
}

function convertpre($text){
	$text = preg_replace("#\[pre\](.*?)\[/pre\]#si", "<pre>\\1</pre>", $text);
	if (strpos(" " . $text, "<pre>") > 0) {
		$nt = explode("<pre>", $text);
		$t1 = explode("</pre>", $nt[1]);
		$t2 = str_replace("<br />", "", $t1[0]);
		$t2 = str_replace("[", "&lt;", $t2);
		$t2 = str_replace("]", "&gt;", $t2);
		$text = str_replace($t1[0], $t2, $text);
	}
	return $text;
}

function breakLongWords($str, $maxLength, $char){
	$wordEndChars = array(" ", "\n", "\r", "\f", "\v", "\0");
	$count = 0;
	$newStr = "";
	$openTag = false;
	for($i = 0; $i < strlen($str); $i++) {
		$newStr .= $str{$i};
		if($str{$i} == "<") {
			$openTag = true;
			continue;
		}
		if(($openTag) && ($str{$i} == ">")) {
			$openTag = false;
			continue;
		}
		if(!$openTag) {
			if(!in_array($str{$i}, $wordEndChars)) {
				$count++;
				if($count == $maxLength) {
					$newStr .= $char;
					$count = 0;
				}
			} else {
					$count = 0;
			}
		}

	}
	return $newStr;
}

function parsetxt ($text) {
	global $nav, $id, $b;
	$text = str_replace("#SID#", "$id&b=$b", $text);
	if ($nav == "info") {
		$text = str_replace("Urlaubsmodus", "-Zensiert-", $text);
		$text = str_replace("Noobschutz", "-Zensiert-", $text);
	}
	$text = censor($text);
	if ($nav != "neueposts") {
		$text = convertsmilies($text);
		$text = preg_replace("#\[size=([\-\+]?\d+)\](.*?)\[/size\]#si", "<span style=\"font-size:\\1\">\\2</span>", $text);
	} else {
		$text = str_replace("{SMILIES_PATH}", "images/smilies", $text);
		$text = preg_replace("#\[size=([\-\+]?\d+)\](.*?)\[/size\]#si", "<span style=\"font-size:\\1%\">\\2</span>", $text);
	}
	$text = convertpre($text);
	$text = preg_replace("#\[quote(?:=&quot;(.*?)&quot;)\](.*?)#si", "<div style=\"padding-left:10px;padding-right:10px\"><b>\\1 hat geschrieben:</b><br /><div style=\"margin-top:2px;padding:5px;line-height:125%;border:dashed 1px silver\">\\2", $text);
	$text = preg_replace("#\[quote(?:=&quot;.*?&quot;)\](.*?)#si", "<div style=\"padding-left:10px;padding-right:10px\"><div style=\"margin-top:2px;padding:5px;line-height:125%;border:dashed 1px silver\">\\2", $text);
	$text = preg_replace("#\[quote\]#si", "<div style=\"padding-left:10px;padding-right:10px\"><div style=\"margin-top:2px;padding:5px;line-height:125%;border:dashed 1px silver\">\\1", $text);
	$text = preg_replace("#\[/quote\]#si", "</div></div>", $text);
	$text = preg_replace("#\[color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]#si", "<span style=\"color:\\1\">\\2</span>", $text);
	$text = preg_replace("#\[font=([a-z\-\ ]+)\](.*?)\[/font\]#si", "<span style=\"font-family:\\1\">\\2</span>", $text);
	$text = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $text);
	$text = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $text);
	$text = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $text);
	$text = preg_replace("#\[c\](.*?)\[/c\]#si", "<div style=\"text-align:center\">\\1</div>", $text);
	$text = preg_replace("#\[r\](.*?)\[/r\]#si", "<div style=\"text-align:right\">\\1</div>", $text);
	$text = preg_replace("#\[l\](.*?)\[/l\]#si", "<div style=\"text-align:left\">\\1</div>", $text);
	$text = preg_replace("#\[bl\](.*?)\[/bl\]#si", "<div style=\"text-align:justify\">\\1</div>", $text);
	$text = preg_replace("#\[img=(.*?(\.(jpg|jpeg|gif|png|php)))\]#si", "<img src=\"\\1\" alt=\"\\1\"/>", $text);
	$text = preg_replace("#\[img\](http://([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png))))\[/img\]#si", "<img src=\"\\1\" alt=\"\\1\" />", $text);
	$text = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "<a href=\"\\1\" alt=\"Link\" target=\"_blank\">\\2</a>", $text);
	$text = converthttp($text);
	$text = breakLongWords($text, 40, "<wbr />");
	return nl2br($text);
}

?>