<?php
class comDb {
	var $connection_exists = false;
	var $connection_handler = false;
	var $error = false;
	var $errorMessage = "";
	var $queryCount = 0;
	var $queries = "";
	var $debugQueries = true;

	function comDb($dbname = "genesis", $host = "localhost", $user = "root", $password = "")
	{
		$this -> connection_link = mysql_connect ($host, $user, $password) or die('Konnte MySQL-Server nicht erreichen');
		mysql_select_db($dbname) or die('Konnte Datenbank "' . $dbname . '" nicht auswählen');
		$this -> connection_exists = true;
	}
	function query($sql, $echo = false)
	{
		if ($echo) {
			echo '<div style="font-size:16pt;">';
			echo nl2br(htmlentities($sql));
			echo '</div>';
		}
		$tmpTime = 0;
		if ($this -> connection_exists) {
			$tmpTime = microtime();
			$this -> connection_handler = mysql_query($sql);
			$tmpTime = microtime() - $tmpTime;
			if (!$this -> connection_handler) {
				$this -> error = true;
				$this -> errorMessage .= "Fehlerhafter Query: " . $sql . "<br />\nMySQL-Fehlermeldung: " . mysql_error();
				die($this -> errorMessage);
			}
		} else {
			die('Es wurde noch keine Verbindung hergestellt.');
		}
		if ($this -> debugQueries) {
			++$this -> queryCount;
			$this -> queries .= $this -> queryCount;
			$this -> queries .= ": " . $sql . "<br />\n";
			$this -> queries .= "<br />Time taken: " . $tmpTime . "s<br />\n";
		}
	}
	function get_1d_array()
	{
		if (!$this -> connection_handler) {
			$this -> error = true;
			$this -> errorMessage .= "Es wurde kein Query abgeschickt";
			return false;
		}
		return mysql_fetch_assoc($this -> connection_handler);
	}
	function get_2d_array()
	{
		if (!$this -> connection_handler) {
			$this -> error = true;
			$this -> errorMessage .= "Es wurde kein Query abgeschickt";
			return false;
		}
		$tmp_super = array();
		$tmp_row = array();
		while ($tmp_row = mysql_fetch_assoc($this -> connection_handler)) {
			$tmp_super[] = $tmp_row;
		}
		return $tmp_super;
	}
	function get_affected_rows()
	{
		if (!$this -> connection_handler) {
			$this -> error = true;
			$this -> errorMessage .= "Es wurde kein Query abgeschickt";
			return false;
		}
		return mysql_affected_rows($this -> connection_link);
	}
	function get_num_rows()
	{
		if (!$this -> connection_handler) {
			$this -> error = true;
			$this -> errorMessage .= "Es wurde kein Query abgeschickt";
			return false;
		}
		return mysql_num_rows($this -> connection_handler);
	}
	function get_inserted_id()
	{
		if (!$this -> connection_handler) {
			$this -> error = true;
			$this -> errorMessage .= "Es wurde kein Query abgeschickt";
			return false;
		}
		return mysql_insert_id();
	}
	function escapeNumberForQuery($number)
	{
		if (is_numeric($number)) {
			return $number;
		} else {
			return 0;
		}
	}
	function escapeIdForQuery($id)
	{
		return intval($id);
	}
	function escapeStringForQuery($string)
	{
		return mysql_real_escape_string(strip_tags($string));
	}
	function escapeHtmlForQuery($string)
	{
		return mysql_real_escape_string($string);
	}
	function destroy()
	{
		if ($this -> debugQueries) {
			echo '<div style="text-align:left;">', nl2br($this -> queries), '</div>', "<br />";
			echo '<br /><span style="font-size:12pt;">Anzahl der Queries: ' . $this -> queryCount . '</span><br />';
		}
		mysql_close($this -> connection_link);
		$this -> connection_link = null;
		$this -> connection_exists = false;
	}
}

?>