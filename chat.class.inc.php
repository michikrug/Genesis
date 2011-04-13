<?php

class chat {
    // Klasseninterne Variablen
    var $db;
    var $error;

    function chat ()
    {
        $this -> db = null;
        $this -> error = array();
    }

    function get_lines($lastline = 0)
    {
        $this -> db -> query("(SELECT *, round(id/10) as time FROM gen_chat WHERE id > '" . $this -> db -> escapeNumberForQuery($lastline) . "' AND intern = '0' ORDER BY id DESC LIMIT 30) UNION (SELECT *, round(id/10) as time FROM gen_chat WHERE id > '" . $this -> db -> escapeNumberForQuery($lastline) . "' ORDER BY id DESC LIMIT 30) ORDER BY id DESC");
        return array_reverse($this -> db -> get_2d_array());
    }

     function new_entry($id, $name, $text, $intern = 0)
    {
        $name = $this -> db -> escapeStringForQuery($name);
        $text = $this -> db -> escapeStringForQuery($text);
        if ($name != ""  && $text != "") {
            $new_id = round((time() + microtime()) * 10);
            $this -> db -> query("INSERT INTO gen_chat (id, user_id, name, text, intern) VALUES ('" . $new_id . "','" . $this -> db -> escapeIdForQuery($id) . "','" . $name . "','" . $text . "','" . $this -> db -> escapeNumberForQuery($intern) . "');");
            return $this -> db -> get_affected_rows();
        }
        return 0;
    }
}

?>