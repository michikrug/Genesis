<?php

function convertsmilies($message) {
	global $comDb;
	$orig = $repl = $test = array();
	$comDb -> query("SELECT * FROM smilies");
	$smilies = $comDb -> get_2d_array();
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

function get_alli($user_id) {
	global $comDb;
	$comDb -> query("SELECT alli FROM genesis_spieler WHERE id = '" . $comDb -> escapeIdForQuery($user_id) . "';");
	$data = $comDb -> get_1d_array();
	return $data["alli"];
}


header("Content-Type: text/html; charset=ISO-8859-1");

session_start();

include_once "ctracker.php";

require_once "db.class.inc.php";
require_once "chat.class.inc.php";
require_once "functions.inc.php";

$comDb = new comDb;
$comDb -> debugQueries = false;

$chat = new chat;
$chat -> db = &$comDb;
$request = isset($_REQUEST["request"]) ? $_REQUEST["request"] : null;

$logged_in = false;
$myalli = 0;

if ($_COOKIE['genlan'] == $_SESSION["name"] && $_SESSION["sid"] > 0) {
	$logged_in = true;
	$myalli = get_alli($_SESSION["sid"]);
}

if ($request == "refresh" && $_REQUEST["lastline"] > 0) {
	$lines = $chat -> get_lines($_REQUEST["lastline"]);
	foreach ($lines as $key => $line) {
		if (substr($line["text"], 0, 4) != "!bot") {
			if ($line["intern"] == 0 || ($logged_in && $myalli > 0 && $myalli == $line["intern"])) {
				if ($logged_in && $line["user_id"] > 0) {
					if ($myalli > 0 && $myalli == $line["intern"]) $line["text"] = "<span class=\"intern\">(intern)</span>" . $line["text"];
					$line["name"] = "<a href=\"game.php?id=" . session_id() . "&b=1&nav=info&t=spieler" . $line["user_id"] . "\" class=\"nc\">" . $line["name"] . "</a>";
				}
				if ($line["user_id"] == -1) {
					$line["text"] = "<span class=\"intern\">(IRC)</span>" . $line["text"];
				}
				echo "                            <li id=\"line" . $line["id"] . "\" style=\"display:none;\"><b>[" . date("H:i", $line["time"]) . "] " . $line["name"] . ":</b> " . convertsmilies($line["text"]) . "</li>\n";
			}
		}
	}
}

if ($request == "init") {
	$lines = $chat -> get_lines();
	echo "                <div id=\"chatbox\">\n";
	echo "                        <ul id=\"chatlist\">\n";
	foreach ($lines as $key => $line) {
		if (substr($line["text"], 0, 4) != "!bot") {
			if ($line["intern"] == 0 || ($logged_in && $myalli > 0 && $myalli == $line["intern"])) {
				if ($logged_in && $line["user_id"] > 0) {
					if ($myalli > 0 && $myalli == $line["intern"]) $line["text"] = "<span class=\"intern\">(intern)</span>" . $line["text"];
					$line["name"] = "<a href=\"game.php?id=" . session_id() . "&b=1&nav=info&t=spieler" . $line["user_id"] . "\" class=\"nc\">" . $line["name"] . "</a>";
				}
				if ($line["user_id"] == -1) {
					$line["text"] = "<span class=\"intern\">(IRC)</span>" . $line["text"];
				}
				echo "                            <li id=\"line" . $line["id"] . "\"><b>[" . date("H:i", $line["time"]) . "] " . $line["name"] . ":</b> " . convertsmilies($line["text"]) . "</li>\n";
			}
		}
	}

	echo "                        </ul>\n";
	echo "                    </div>\n";
	echo "                    <form name=\"chat_form\" onSubmit=\"ajax_chat('new_entry'); document.getElementById('chat_text').value=''; document.getElementById('chat_text').focus(); return false;\">\n";

	if ($logged_in) {
		echo "                        <input type=\"hidden\" id=\"chat_name\" name=\"chat_name\" value=\"" . $_SESSION["name"] . "\"  />\n";
		echo "                        <input class=\"text\" type=\"text\" id=\"chat_text\" name=\"chat_text\" value=\"Text\" onFocus=\"if (this.value == 'Text') { this.value=''; }\" size=\"59\" maxlength=\"250\" tabindex=\"1\" />\n";
	} else {
		echo "                        <input class=\"text\" type=\"text\" id=\"chat_name\" name=\"chat_name\" value=\"Name\" onFocus=\"if (this.value == 'Name') { this.value=''; }\" size=\"7\" maxlength=\"20\" tabindex=\"1\" />\n";
		echo "                        <input class=\"text\" type=\"text\" id=\"chat_text\" name=\"chat_text\" value=\"Text\" onFocus=\"if (this.value == 'Text') { this.value=''; }\" size=\"49\" maxlength=\"250\" tabindex=\"2\" />\n";
	}

	echo "                        <input class=\"small_button\" type=\"submit\" name=\"chat_action\" value=\">\" onClick=\"ajax_chat('new_entry'); document.getElementById('chat_text').value=''; document.getElementById('chat_text').focus(); return false;\"/>\n";
	if ($logged_in) {
		echo "                        <i onClick=\"setCookie('show', 0);window.location.href=window.location.href;\">X</i>\n";
	}

	if ($logged_in && $myalli > 0) {
		echo "                            <br/><input class=\"check\" type=\"checkbox\" id=\"chat_intern\" name=\"chat_intern\" value=\"1\" /> nur für Symbiose-Mitglieder <i onClick=\"setCookie('intern', document.getElementById('chat_intern').checked); alert('Gespeichert!');\">(merken)</i> <i onClick=\"document.getElementById('chat_hilfe').style.display='block';\">?</i>\n";
	} else {
		echo "                            <input type=\"hidden\" id=\"chat_intern\" name=\"chat_intern\" value=\"0\"/>\n";
	}

	echo "                        </form>\n";
	echo "                        <div id=\"chat_hilfe\" style=\"display:none;\">\n";
	echo "                            <h3>Informationen zum Chat</h3>\n";
	echo "                            <b>GenesisBot Befehle:</b><br/>\n";
	echo "                            !bot platz<br/>!bot wetter<br/>!bot wetterlage<br/>!bot wetterneu PLZ/Ort aktuell/heute/morgen<br/>!bot bier<br/><br/>\n";
	echo "                            <i onClick=\"document.getElementById('chat_hilfe').style.display='none';\">Schliessen</i></div>\n";
	echo "                        </div>\n";
}

if ($request == "new_entry") {
	$chat_name = utf8_decode(stripslashes($_REQUEST["chat_name"]));
	$intern = 0;
	if ($logged_in) {
		if (intval($_REQUEST["chat_intern"]) == 1) $intern = $myalli;
		$chat_name = $_SESSION["name"];
	}
	$chat_text = utf8_decode(stripslashes($_REQUEST["chat_text"]));
	echo $chat -> new_entry($_SESSION["sid"], $chat_name, $chat_text, $intern);
}

$comDb -> destroy();

?>