function setCookie(name, value, days)
{
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = ';expires=' + date.toGMTString();
    } else var expires = '';
    document.cookie = name + '=' + value + expires + ';path=/';
}

function readCookie(name)
{
    var needle = name + '=';
    var cookieArray = document.cookie.split(';');
    for(var i = 0; i < cookieArray.length; i++) {
        var pair = cookieArray[i];
        while (pair.charAt(0) == ' ') pair = pair.substring(1, pair.length);
        if (pair.indexOf(needle) == 0) return pair.substring(needle.length, pair.length);
    }
    return null;
}

function eraseCookie(name)
{
    setCookie(name, "", -1);
}

function get_lastchild(n)
{
    x = n.lastChild;
    while (x.nodeType != 1) x = x.previousSibling;
    return x;
}
function get_firstchild(n)
{
    x = n.firstChild;
    while (x.nodeType != 1) x = x.nextSibling;
    return x;
}
function appear_timeout(i, max_i)
{
    Effect.Appear(document.getElementById('chatlist').getElementsByTagName('li')[i].id, { duration: 0.5 });
    setTimeout("document.getElementById('chatbox').scrollTop = document.getElementById('chatbox').scrollHeight;", 500);
    i++;
    if (i < max_i) setTimeout("appear_timeout(" + i + ", " + max_i + ");", 100);
}
function fade_timeout(i, max_i)
{
    Effect.Fade(document.getElementById('chatlist').getElementsByTagName('li')[i].id, { duration: 0.5 });
    i++;
    if (i < max_i) {
        setTimeout("fade_timeout(" + i + ", " + max_i + ");", 100);
    } else {
        for (i = 0; i < max_i; i++)
        setTimeout("document.getElementById('chatlist').removeChild(get_firstchild(document.getElementById('chatlist')));", (i * 100) + 100);
    }
}
function ajax_chat(type)
{
    var chatlist = document.getElementById('chatlist');
    if (type == 'new_entry') {
        var name = document.getElementById('chat_name').value;
        var text = document.getElementById('chat_text').value;
        var intern = 0;
        if (document.getElementById('chat_intern').checked) intern = 1;
        if (name != 'name' && text != 'Text' && name != '' && text != '') {
            new Ajax.Request('ajax_chat.php', {
                    method: 'post',
                    parameters: {request: type, chat_name: name, chat_text: text, chat_intern: intern},
                    onSuccess: function(transport)
                    {
                        var response = transport.responseText;
                        if (response == '1') {
                            ajax_chat('refresh');
                        } else {
                            alert(response);
                            alert('Fehler beim Eintragen! Bitte überprüfe die Eingabe.');
                        }
                    } ,
                    onFailure: function()
                    {
                        alert('Abfragefehler');
                    }
                }
                );
        }
    } else if (type == 'refresh') {
        var chatlist = document.getElementById('chatlist');
        var lastline = get_lastchild(chatlist).id.substring(4);
        new Ajax.Request('ajax_chat.php', {
                method: 'get',
                parameters: {request: type, lastline: lastline} ,
                onSuccess: function(transport)
                {
                    var response = transport.responseText;
                    if (response.length > 1) {
                        var oldlines = chatlist.getElementsByTagName('li').length;
                        chatlist.innerHTML += response;
                        var lines = chatlist.getElementsByTagName('li').length;
                        appear_timeout(oldlines, lines);
                        if (lines > 30) fade_timeout(0, lines - 30);
                    }
                } ,
                onFailure: function()
                {
                    alert('Abfragefehler');
                }
            }
            );
    } else if (type == 'init') {
        new Ajax.Request('ajax_chat.php', {
                method: 'get',
                parameters: {request: type} ,
                onSuccess: function(transport)
                {
                    var response = transport.responseText;
                    if (response.length > 1) {
                        document.getElementById('chat').innerHTML = response;
                        document.getElementById('chatbox').scrollTop = document.getElementById('chatbox').scrollHeight;
                        if (readCookie('intern') == true || readCookie('intern') == 'true') document.getElementById('chat_intern').checked = true;
                        setInterval("ajax_chat('refresh');", 10000);
                    }
                } ,
                onFailure: function()
                {
                    alert('Abfragefehler');
                }
            }
            );
    } else if (type == 'init_onload') {
        if (readCookie('show') == 1 || readCookie('show') == '1') ajax_chat('init');
    }
}