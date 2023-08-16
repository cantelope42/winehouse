<?
  require('../db.php');
  $data = json_decode(file_get_contents('php://input'));
  $trackName = mysqli_real_escape_string($link, $data->{'trackName'});
  if(!$trackName) die();
  $trackName = './tracks/' . $trackName;
  $trackName = escapeshellarg($trackName);
  $output = shell_exec("rm $trackName");
  $ret = [true, $output];
  echo json_encode($ret);
?>
