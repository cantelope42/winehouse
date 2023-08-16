<?
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$url = explode('?id=', $_SERVER['REQUEST_URI'])[1] ;
//$url = 'https://cantelope.org/public_playlists/.base/temp.html';
$ch = curl_init(str_replace(" ","%20",$url));
  //curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch,CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36 Edg/102.0.1245.33');
  $original = curl_exec($ch);
  curl_close($ch);
  echo explode("\u0026", explode('initcwndbps=', $original)[1])[0];
?>
