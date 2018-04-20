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

error_reporting('0');
date_default_timezone_set('Europe/Moscow');

if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
}
if (strstr($_SERVER['REMOTE_ADDR'], ',')) {
    $_SERVER['REMOTE_ADDR'] = substr($_SERVER['REMOTE_ADDR'], '0', strpos($_SERVER['REMOTE_ADDR'], ','));
}
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

define ( 'FRAMEWORK', dirname ( __FILE__ ) );
define ( 'MYSQL_HOST', 'localhost' );
define ( 'MYSQL_PORT', '3306' );
define ( 'MYSQL_USER', 'fonero' );
define ( 'MYSQL_PASS', 'PASSWORD' );
define ( 'MYSQL_BASE', 'fonero' );

require_once ( FRAMEWORK . '/libraries/mysql.php' );

function rub ( $data ) {
	$data = trim ( $data );
	if ( is_numeric ( $data ) ) {
		$data = trim ( str_replace ( array ( ',', ' ' ), array ( '.', '' ), $data ) );
	} else {
		$data = '0';
	}
	return number_format ( $data, '2', '.', '' );
}

function percent ( $data, $percent, $is_accuracy = true ) {
	$data = trim ( $data );
	if ( is_numeric ( $data ) ) {
		$data = trim ( str_replace ( array ( ',', ' ' ), array ( '.', '' ), $data ) );
	} else {
		$data = '0';
	}
	if ( $is_accuracy ) {
		return bcmul ( $data,  bcdiv ( $percent, '100', '2' ), '2' );
	} else {
		return number_format ( ( $data * ( $percent / '100' ) ), '2', '.', '' );
	}
}

function factor ( $from, $to ) {
	$data = round ( ( ( $from / $to ) * '100' ), '2' );
	$data = trim ( $data );
	if ( is_numeric ( $data ) ) {
		$data = trim ( str_replace ( array ( ',', ' ' ), array ( '.', '' ), $data ) );
	} else {
		$data = '0';
	}
	return number_format ( $data, '2', '.', '' );
}

function unique () {
	return md5(mt_rand('1', '9999999') . mt_rand('1', '9999999') . mt_rand('1', '9999999') . mt_rand('1', '9999999') . mt_rand('1', '9999999') . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime() . microtime());
}

?>