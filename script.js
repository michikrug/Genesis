var standardtimer = window.setInterval("countdown()",1000);
var countdowns = new Array ();
StartZeit = new Date();

function init_countdown (id, time, done, cancel, aktion) {
	var newcounter = new Array ();
	if (id != "timer" && id != "timer2" && id != "uhr") {
		newcounter["time"] = StartZeit.getTime() + (time * 1000);
		newcounter["done"] = done;
	} else {
		if (id != "uhr") {
			newcounter["time"] = (time * 1000);
			var ZeitSrv = new Date(done);
			newcounter["done"] = ZeitSrv.getTime() - StartZeit.getTime();
		} else {
			newcounter["time"] = (time * 1000) - StartZeit.getTime();
			newcounter["done"] = 0;
		}
	}
	newcounter["id"] = id;
	newcounter["cancel"] = cancel;
	newcounter["aktion"] = aktion;
	countdowns.push (newcounter);
}

function countdown() {

	jetzt = new Date();
	jMinuten = jetzt.getMinutes();
	jStunden = jetzt.getHours();

	for (var counter = 0; counter < countdowns.length; counter++) {

		if (countdowns[counter]['id'] != "timer" && countdowns[counter]['id'] != "timer2" && countdowns[counter]['id'] != "uhr") {

			var diff_s = (countdowns[counter]['time'] - jetzt.getTime());

			var days = Math.floor((diff_s / 86400000) % 300);
			var hours = Math.floor((diff_s / 3600000) % 24);
			var minutes = Math.floor((diff_s / 60000) % 60);
			var seconds = Math.floor((diff_s / 1000) % 60);

			var p_days = days;
			var p_hours = ((hours < 10) ? "0" : "")+hours;
			var p_minutes = ((minutes < 10) ? "0" : "")+minutes;
			var p_seconds = ((seconds < 10) ? "0" : "")+seconds;

			if (document.getElementById && document.getElementById(countdowns[counter]['id']) != null) {
				if (diff_s <= 0) {
					document.getElementById(countdowns[counter]['id']).innerHTML = countdowns[counter]['done'];
					if (countdowns[counter]['aktion'] != "") {
						seite = countdowns[counter]['aktion'];
						window.setTimeout("anzeigen(seite)",10000);
						countdowns[counter]['aktion'] = "";
					}
				} else {
					if (p_days == 1) {
						document.getElementById(countdowns[counter]['id']).innerHTML = p_days+' Tag '+p_hours+':'+p_minutes+':'+p_seconds+countdowns[counter]['cancel'];
					} else if (p_days > 1) {
						document.getElementById(countdowns[counter]['id']).innerHTML = p_days+' Tage '+p_hours+':'+p_minutes+':'+p_seconds+countdowns[counter]['cancel'];
					} else {
						document.getElementById(countdowns[counter]['id']).innerHTML = p_hours+':'+p_minutes+':'+p_seconds+countdowns[counter]['cancel'];
					}
				}
			}

		} else {

			var diff_s = (countdowns[counter]['time'] + jetzt.getTime());
			var Zeit = new Date();
			Zeit.setTime(diff_s+countdowns[counter]['done']);
			seconds = Zeit.getSeconds();
			minutes = Zeit.getMinutes();
			hours = Zeit.getHours();
			day = Zeit.getDate();
			month = Zeit.getMonth()+1;
			year = Zeit.getFullYear();

			var p_hours = ((hours < 10) ? "0" : "")+hours;
			var p_minutes = ((minutes < 10) ? "0" : "")+minutes;
			var p_seconds = ((seconds < 10) ? "0" : "")+seconds;
			var p_day = ((day < 10) ? "0" : "")+day;
			var p_month = ((month < 10) ? "0" : "")+month;
			var p_year = year;

			if (document.getElementById && document.getElementById(countdowns[counter]['id']) != null) {
				document.getElementById(countdowns[counter]['id']).innerHTML = p_hours+':'+p_minutes+':'+p_seconds+' ('+p_day+'.'+p_month+'.'+p_year+')';
			}

		}

	}
}

function anzeigen(seite) {
	window.location.href = seite;
}

function shown(was) {
	var elemente = document.getElementsByName('nt');
	for (var i = 0; (i < elemente.length); i++) {
		elemente[i].style.display = 'none';
	}
	var elemente = document.getElementsByName('nd');
	for (var i = 0; (i < elemente.length); i++) {
		elemente[i].style.display = 'none';
	}
	var elemente = document.getElementsByName('nh');
	for (var i = 0; (i < elemente.length); i++) {
		elemente[i].style.display = 'none';
	}
	var elemente = document.getElementsByName(was);
	for (var i = 0; (i < elemente.length); i++) {
		elemente[i].style.display = '';
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

function checkfuell(kaps) {
	var neu = parseInt(kaps) - (parseInt(document.getElementsByName('mir1')[0].value)+parseInt(document.getElementsByName('mir2')[0].value)+parseInt(document.getElementsByName('mir3')[0].value)+parseInt(document.getElementsByName('mir4')[0].value)+parseInt(document.getElementsByName('mir5')[0].value));
	if (neu < 0) {
		document.getElementsByName('gesamt')[0].style.color = 'red';
	} else {
		document.getElementsByName('gesamt')[0].style.color = 'lime';
	}
	var nummer = '' + neu;
	var laenge = nummer.length;
	var anzeige = nummer;
	if (laenge > 3) {
		var mod = laenge % 3;
		var output = (mod > 0 ? (nummer.substring(0,mod)) : '');
		for (i = 0; i < Math.floor(laenge / 3); i++) {
			if ((mod == 0) && (i == 0))
				output += nummer.substring(mod + 3 * i, mod + 3 * i + 3);
			else
				output+= '.' + nummer.substring(mod + 3 * i, mod + 3 * i + 3);
		}
		anzeige = output;
	}
	document.getElementsByName('gesamt')[0].innerHTML = anzeige;
}

function fuell(was, mitwas) {
	var inputs = document.getElementsByName(was);
	for (var i = 0; (i < inputs.length); i++) {
		inputs[i].value=mitwas;
	}
}

function getElementsByName_iefix(tag, name) {
     var elem = document.getElementsByTagName(tag);
     var arr = new Array();
     for(i = 0,iarr = 0; i < elem.length; i++) {
          att = elem[i].getAttribute("name");
          if(att == name) {
               arr[iarr] = elem[i];
               iarr++;
          }
     }
     return arr;
}

function uncolor(was) {
	var tds = document.getElementsByName('zelle');
	if (tds.length == 144) {
		for (var i = 0; (i < tds.length); i++) {
			tds[i].style.backgroundColor='transparent';
		}
	} else {
		var tds = getElementsByName_iefix('td', 'zelle');
		for (var i = 0; (i < tds.length); i++) {
			tds[i].style.backgroundColor='transparent';
		}
	}
	document.getElementById(was).style.backgroundColor='#000000';
}