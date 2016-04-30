<?php
if (!defined('IN_MEDIA')) die("Hacking attempt");
class mysql { 
	var $link_id;
	var $log_file = 'logs.txt';
	var $log_error = 1;
	
	function connect($db_host, $db_username, $db_password, $db_name) {
		$this->link_id = mysqli_connect($db_host, $db_username, $db_password, $db_name);
		if (!$this->link_id)
		{
			$this->show_error('Unable to connect to MySQL server. MySQL reported: '.mysqli_connect_errno() . PHP_EOL);
		}
	}
	
	function query($input){
		$q = mysqli_query($this->link_id, $input) or $this->show_error("<b>Error MySQL Query</b> : ".mysqli_error($this->link_id),$input);
		return $q;
	}
	
	function fetch_array($query_id, $type=MYSQLI_BOTH){
		$fa = mysqli_fetch_array($query_id,$type);
		return $fa;
	}
	
	function num_rows($query_id) {
		$nr = mysqli_num_rows($query_id);
		return $nr;
	}
	
	function result($query_id, $row=0, $field) {
		$r = mysqli_result($query_id, $row, $field);
		return $r;
	}
	
	function insert_id() {
		return mysqli_insert_id($this->link_id);
	}
	function show_error($input,$q){
		if ($this->log_error) {
			$file_name = $this->log_file;
			$fp = fopen($file_name,'a');
			flock($fp,2);
			fwrite($fp,"### ".date('H:s:i d-m-Y',NOW)." ###\n");
			fwrite($fp,$input."\n");
			fwrite($fp,"QUERY : ".$q."\n");
			flock($fp,1);
			fclose($fp);
		}
		die($input);
	}
}
?>