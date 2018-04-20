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

if($mysql->data('SELECT COUNT(faucet_id) as count FROM web_faucets WHERE faucet_status = "waited"', false)->count > '0') {
    $items = $mysql->data('SELECT * FROM web_faucets WHERE faucet_status = "waited"');
    $payments = array();
    foreach ($items as $item) {
        if(substr($item->faucet_amount, '0', '1') == '0') {
            $item->faucet_amount = str_replace('0.', '', $item->faucet_amount) . '0000000000';
        } else {
            $item->faucet_amount = str_replace('.', '', $item->faucet_amount) . '0000000000';
        }
        $payments[] = '{"amount":' . trim($item->faucet_amount) . ',"address":"' . trim($item->faucet_wallet) . '"}';
    }
    $json = json_decode(file_get_contents('http://127.0.0.1:18182/json_rpc', false, stream_context_create(array('http' =>array('method'  => 'POST', 'header'  => "Content-Type: application/json\r\n", 'content' => '{"jsonrpc":"2.0","id":"0","method":"transfer_split","params":{"destinations":[' . implode(',', $payments) . '],"mixin":5,"get_tx_key":true}}')))));

    echo '{"jsonrpc":"2.0","id":"0","method":"transfer_split","params":{"destinations":[' . implode(',', $payments) . '],"get_tx_key":true}}' . "\n";
    print_r($json);

    if(isset($json->result->tx_hash_list) && count($json->result->tx_hash_list) > '0') {
        $tx_hash_list = implode('|', (array)$json->result->tx_hash_list);
        foreach ($items as $item) {
            $mysql->exec('UPDATE web_faucets SET faucet_status = "successed", faucet_txid = "' . $mysql->escape($tx_hash_list) . '" WHERE faucet_id = "' . $mysql->escape($item->faucet_id) . '"');
        }
    }
    $item = null;
}

?>