<?php

/*
 * Класс работы с MySQL
 */

class MySQL {
	function __construct ( ) {
		$this -> connect = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_BASE ) or die('Ошибка соединения с MySQL'); 
		//mysqli_query ( $this -> connect, 'SET character_set_client  = utf8' );
		//mysqli_query ( $this -> connect, 'SET character_set_results = utf8' );
		//mysqli_query ( $this -> connect, 'SET collation_connection  = utf8_general_ci' );
	}
	function __destruct ( ) {
		mysqli_close ( $this -> connect );
	}
	function exec ( $query ) {
		return ( mysqli_query ( $this -> connect, $query ) );
	}
	function data ( $query, $is_array = true ) {
		unset ( $this -> result );
		unset ( $this -> object );	
		$this -> query = mysqli_query ( $this -> connect, $query );
		if ( $is_array ) {
			while ( $this -> object = mysqli_fetch_object ( $this -> query ) ) {
				$this -> result [ ] = $this -> object;
			}
		} else {
			$this -> result = mysqli_fetch_object ( $this -> query );
		}
		return ( $this -> result );
	}
	function num ( $query ) {
		return ( mysqli_num_rows ( mysqli_query ( $this -> connect, $query ) ) );
	}
	function escape ( $string, $tags = false ) {
		return mysqli_real_escape_string ( $this -> connect, $string );
	}
	function insertid() {
		return mysqli_insert_id($this -> connect);
	}
}

?>