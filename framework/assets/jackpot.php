<?php

if (!function_exists('bcmul')) {
  function bcmul($_ro, $_lo, $_scale='0') {
    return round($_ro*$_lo, $_scale);
  }
}
  
if (!function_exists('bcdiv')) {
  function bcdiv($_ro, $_lo, $_scale='0') {
    return round($_ro/$_lo, $_scale);
  }
}

/*
 * Класс работы с MySQL
 */

class MySQL {
	function __construct ($host, $login, $password, $database) {
		$this -> connect = mysqli_connect ($host, $login, $password, $database) or die('Ошибка соединения с MySQL'); 
		mysqli_query ( $this -> connect, 'SET character_set_client  = utf8' );
		mysqli_query ( $this -> connect, 'SET character_set_results = utf8' );
		mysqli_query ( $this -> connect, 'SET collation_connection  = utf8_general_ci' );
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

function unique () {
	return md5(mt_rand('1', '9999999') . mt_rand('1', '9999999') . mt_rand('1', '9999999') . mt_rand('1', '9999999') . mt_rand('1', '9999999') . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime());
}

$mysql = new MySQL('localhost', 'fonero', 'PASSWORD', 'fonero');

if($mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets WHERE faucet_date = "' . $mysql->escape(date('Y-m-d', strtotime('-1 day'))) . '"', false )->count > '0') {
    $item = $mysql->data('SELECT * FROM web_faucets WHERE faucet_date = "' . $mysql->escape(date('Y-m-d', strtotime('-1 day'))) . '" ORDER BY RAND() LIMIT 1', false);
    $mysql->exec( 'INSERT INTO web_faucets (faucet_id, faucet_date, faucet_time, faucet_hash, faucet_addr, faucet_wallet, faucet_amount, faucet_txid, faucet_jackpot, faucet_status) VALUES (NULL, "' . $mysql -> escape (date('Y-m-d', time())) . '", "' . $mysql -> escape (date('H:i:s', time())) . '", "' . $mysql->escape(md5($item->faucet_wallet . $item->faucet_addr)) . '", "' . $mysql->escape($item->faucet_addr) . '", "' . $mysql->escape($item->faucet_wallet) . '", "100.00", "-", "1", "waited");' );
}

?>