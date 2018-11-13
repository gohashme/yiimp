<?php
//https://tradeogre.com/api/v1/markets
function tradeogre_api_query($method, $params='')
{
	$uri = "https://tradeogre.com/api/v1/{$method}";
	if (!empty($params)) $uri .= "/{$params}";
 	$ch = curl_init($uri);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 	$execResult = strip_tags(curl_exec($ch));
 	$obj = json_decode($execResult, true);
 	return $obj;
}

function tradeogre_api_query_get($method, $req = array())
{
    require_once('/etc/yiimp/keys.php');
    if (!defined('EXCH_TRADEOGRE_SECRET')) return false;
    
	$uri="https://tradeogre.com/api/v1/$method?" . http_build_query($req,'','&');
	echo $uri;
	$ch = curl_init($uri);
 	$key = EXCH_TRADEOGRE_KEY;
	$secret = EXCH_TRADEOGRE_SECRET;
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERPWD, $key. ":" .$secret);
    $execResult = curl_exec($ch);
    $resData = json_decode($execResult);
     #print_r ($resData);
     return $resData;
}

function tradeogre_api_query_post($method, $req = array())
{
    require_once('/etc/yiimp/keys.php');
    if (!defined('EXCH_TRADEOGRE_SECRET')) return false;
    
	$uri = "https://tradeogre.com/api/v1/{$method}";
 	$postData = http_build_query($req,'','&');
	print_r ($postData);
	$ch = curl_init($uri);
 	$key = EXCH_TRADEOGRE_KEY;
	$secret = EXCH_TRADEOGRE_SECRET;
 	curl_setopt($ch, CURLOPT_VERBOSE, true);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch,CURLINFO_HEADER_OUT,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERPWD, $key. ":" .$secret);
     $execResult = curl_exec($ch);
    $resData = json_decode($execResult);
     return $resData;
}

function getTradeogreBalances()
{
    debuglog(__FUNCTION__);

	$exchange = 'tradeogre';
	if (exchange_get($exchange, 'disabled')) return;

	$savebalance = getdbosql('db_balances', "name='$exchange'");
	if (is_object($savebalance)) {
		$balances = tradeogre_api_query_get('account/balances');
		if (is_array($balances)) {
            debuglog($balances);

			// $savebalance->balance = arraySafeVal($balances, 'btc_balance',0.) - arraySafeVal($balances, 'btc_reserved');
			// $savebalance->onsell = arraySafeVal($balances, 'btc_reserved');
			// $savebalance->save();
		}
	}
}