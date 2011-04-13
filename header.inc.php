<?php

$ausgabe = "<html>
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\"/>
<title>Genesis</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\"/>
<script type=\"text/javascript\" src=\"script.js\"></script>
<script type=\"text/javascript\" src=\"js/prototype.js\"></script>
<script type=\"text/javascript\" src=\"js/scriptaculous.js\"></script>
<script type=\"text/javascript\" src=\"js/ajax_chat.js\"></script>
</head>
<body onLoad=\"ajax_chat('init_onload');\">
<center>\n";

$ausgabe .= "<div id=\"chat\"><input type=\"submit\" onClick=\"setCookie('show', 1);ajax_chat('init');\" value=\"Chat\" /></div>\n";

?>