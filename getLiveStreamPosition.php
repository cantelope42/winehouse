<?
function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}

function unicode_decode($str) {
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  require('../db.php');
  $data = json_decode(file_get_contents('php://input'));
  $id = mysqli_real_escape_string($link, $data->{'id'});
  if(!$id) $id = explode('?id=', $_SERVER['REQUEST_URI'])[1];
	if($id){
    $url = 'https://youtu.be/' . $id . '&autoplay=1';
    $ch = curl_init(str_replace(" ","%20",$url));
    //curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch,CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36');
    $original = curl_exec($ch);
    //usleep(1000000*11);
    curl_close($ch);
    $initcwndbps = explode("\u0026", explode('initcwndbps=', $original)[1])[0];
		$res = explode(',"url":"https:', $original); 
	  $streamURL = unicode_decode(urldecode('https:' . explode('"',  $res[sizeof($res)-4])[0]));
		echo json_encode([$initcwndbps, $streamURL]);
  }
?>
