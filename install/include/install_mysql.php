<?php
if(!defined('IN_SHANGTAO')) {
	exit('Access Denied');
}

class Mysql {
	var $tablepre;
	function connect($dbhost, $db_port,$dbuser, $dbpw, $dbname = '', $dbcharset, $tablepre='') {
		$this->tablepre = $tablepre;
		if(!$this->link = mysqli_connect($dbhost, $dbuser, $dbpw,'',$db_port)) {
			die('{status:-1,msg:"Can not connect to MySQL server"}');
		}
	    if($dbname) {
			$db_selected = mysqli_select_db($this->link,$dbname);
			if (!$db_selected) {
				$sql="CREATE DATABASE $dbname DEFAULT CHARACTER SET utf8;";
				self::query($sql);
				mysqli_select_db($this->link,$dbname);
				
			}
		}
	}
	function query($sql) {
		return mysqli_query($this->link,$sql);
	}
	function excute($sql){
		if(!isset($sql) || empty($sql)) return;
		$sql = str_replace("\r", "\n", str_replace(' `'.TABLEPRE, ' `'.$this->tablepre, $sql));
		$ret = array();
		$num = 0;
		foreach(explode(";\n", trim($sql)) as $query) {
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			foreach($queries as $query) {
				$ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
			}
			$num++;
		}
		unset($sql);
		foreach($ret as $query) {
			$query = trim($query);
			if($query) {
				if(strtoupper(substr($query, 0, 12)) == 'CREATE TABLE') {
					$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $query));
		            $query = preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $query)." ENGINE=InnoDB DEFAULT CHARSET=utf8";
				}
				self::query('set names utf8');
				self::query($query);
			}
		}
	}
	function version() {
		return mysqli_get_server_info($this->link);
	}
	function close() {
		return mysqli_close($this->link);
	}
}

?>