<?php

$ausgabe = "<html>
<head>
<meta http-equiv=\"content-language\" content=\"de\">
<meta name=\"author\" content=\"VBFrEaK\" />
<meta name=\"description\" content=\"das etwas andere Browsergame - entwickle dich in einer Welt der Genetik\">
<meta name=\"keywords\" content=\"Genesis, VBFrEaK, Browsergame, MMOG, Onlinegame, Browserspiel, Spiel, Adenin, Thymin, Guanin, Cytosin, ATP, Makrophage, Phage, Sentinel, Plasmazelle, T-Killerzelle, Killerzelle, Determinator, Spionage-Partikel, Partikel, Spionage, Tranze, Transporterzelle, Transport, Angriff, Keimzelle, zellular, Bombe, Neogen, Antikörper, Krieg, Bündnis, Frieden, Freund, Feind, Retikulum, Mitochondrium, Knochenmark, Großhirn, Speichervesikel, Kleinhirn, Immunsystem, Sensorneuronen, Partikelfilter, Ausbau, Evolution, Produktion, Handel, Symbiose, Nährstoff, Mutation, Lokomotion, Karyogamie, Immunität, Sensorik, Noobschutz\" />
<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\">
<title>Genesis</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\">
<script type=\"text/javascript\" src=\"js/prototype.js\"></script>
<script type=\"text/javascript\" src=\"js/effects.js\"></script>
<script type=\"text/javascript\" src=\"js/ajax_chat.js\"></script>
<script language=\"JavaScript\">
    var arImages=new Array();
    function Preload() {
        var temp = Preload.arguments;
        for(x=0; x < temp.length; x++) {
           arImages[x]=new Image();
           arImages[x].src=Preload.arguments[x];
        }
    }
    function showhide(was) {
        var elemente = document.getElementsByName(was);
        for (var i = 0; (i < elemente.length); i++) {
            if (elemente[i].style.display == 'none') {
                elemente[i].style.display = '';
            } else {
                elemente[i].style.display = 'none';
            }
        }
    }
</script>
</head>
<body onload=\"Preload('images/genesis_02.jpg','images/genesis_03.jpg','images/genesis_04.jpg','images/genesis_05.jpg','images/genesis_06.jpg','images/genesis_07.jpg','images/genesis_08.jpg','images/genesis2_02.jpg','images/genesis2_03.jpg','images/genesis2_04.jpg','images/genesis2_05.jpg','images/genesis2_06.jpg','images/genesis2_07.jpg','images/genesis2_08.jpg')\">
<center>\n";

$ausgabe .= "<div id=\"chat\"><input type=\"submit\" onClick=\"ajax_chat('init');\" value=\"Chat\" /></div>\n";

?>