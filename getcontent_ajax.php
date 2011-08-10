<?php
if (!isset($_GET['file']))
  {
  echo "Please set a file path";
  exit;
  }
$file = $_GET['file'];
include_once 'config.php';
if(!file_exists($TMP_PATH."/tree/tree.txt"))
  {
  echo "-2";//Unable to find the source file. Please check if the file exists and try again.
  exit;
  }
$json = file_get_contents($TMP_PATH."/tree/tree.txt");
$files = json_decode($json);
if(!isset($files->$file) || !file_exists($files->$file))
  {
  echo "-1";//The source repository is unavailable, please try again later
  exit;
  }

echo file_get_contents($files->$file);
?>