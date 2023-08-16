<?
  require('../db.php');
  $data = json_decode(file_get_contents('php://input'));
  $id = mysqli_real_escape_string($link, $data->{'id'});
  $source = mysqli_real_escape_string($link, $data->{'source'});
  if(!$id) die();
  switch($source){
    case 'youtube':
      $cmd = "youtube-dl -x  --get-title --no-color --audio-format mp3 https://youtu.be/$id";
      //$filename = str_replace('/', '', (str_replace("\n", '', shell_exec($cmd))));
      $filename = str_replace('"','', str_replace('@', '', str_replace('/', '', (str_replace('%', '', str_replace('#', '', str_replace("'",'', str_replace("\n", '', shell_exec($cmd)))))))));
      if($filename){
        $escname = escapeshellarg("./tracks/$filename.mp3");
        //$tempname = escapeshellarg("./normalized/$filename.mkv");
        $cmd = "youtube-dl -x --write-thumbnail --audio-format mp3 -o $escname https://youtu.be/$id";
        shell_exec($cmd);
        //$cmd = "ffmpeg-normalize -f -t -5 $escname 2>&1";
        //shell_exec($cmd);
        //$cmd = "ffmpeg -y -i $tempname $escname";
        //shell_exec($cmd);
        //$cmd = "rm $tempname";
        //shell_exec($cmd);
        echo json_encode([true, "$filename.mp3"]);
        die();
      }else{
        echo [false];
        die();
      }
    break;
    case 'audiocloud':
      $sql = "SELECT * FROM audiocloudTracks WHERE id = $id";
      $res = mysqli_query($link, $sql);
      $row = mysqli_fetch_assoc($res);
      $filename = $row['trackName'];
      $sourceFile = $row['audioFile'];
      if($filename){
        $escname = escapeshellarg("./tracks/$filename.mp3");
        $cmd = "wget $sourceFile -O $escname";
        shell_exec($cmd);
        //$cmd = "ffmpeg-normalize -f -t -5 $escname 2>&1";
        //shell_exec($cmd);
        //$cmd = "ffmpeg -y -i $tempname $escname";
        //shell_exec($cmd);
        //$cmd = "rm $tempname";
        //shell_exec($cmd);
        echo json_encode([true, "$filename.mp3"]);
        die();
      }else{
        echo [false];
        die();
      }
    break;
  }
  echo ['false'];
?>

