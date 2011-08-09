<?php
include_once 'config.php';

$foldersArray=explode(";",$FOLDERS);
for($i = 0; $i<count($foldersArray);$i++)
  {
  $content = file_get_contents($GITORIOUS_PATH.'/trees/master/Code/'.$foldersArray[$i]);
  preg_match_all ("/\w*\.h/" , $content ,  $matches  );
  $matches = array_unique($matches[0]);
  sort($matches);
  $Fnm = "tmp/tree/".$foldersArray[$i];
  @unlink($Fnm);
  $inF = fopen($Fnm,"w");
  fwrite($inF,join(";",$matches));
  fclose($inF);
  }

?>
