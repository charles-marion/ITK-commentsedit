<?php
if (!isset($_GET['file']))
  {
  echo "Please set a file path";
  exit;
  }
$file = $_GET['file'];
include_once 'config.php';
$found="";
$foldersArray=explode(";",$FOLDERS);
$i=0;
if(!file_exists('tmp/tree/'.$foldersArray[0]))
  {
  include("gettree.php");
  }
while($found==""&&$i<count($foldersArray))
  {
  $content=file_get_contents('tmp/tree/'.$foldersArray[$i]);
  if(strpos($content, $file)!==false)
    {
    $found=$foldersArray[$i];
    break;
    }
  $i++;
  }
if($found=="")
  {
  echo "-2";
  exit;
  }
$content = file_get_contents($GITORIOUS_PATH.'/blobs/raw/master/Code/'.$found.'/'.$file);
if ($content === false||$content=='')
  {
  if (false == file_get_contents($GITORIOUS_PATH))
    {
    echo "-1";//The source repository is unavailable, please try again later
    }
  else
    {
    echo "-2";//Unable to find the source file. Please check if the file exists and try again.
    }
  exit;
}
echo $content;
?>