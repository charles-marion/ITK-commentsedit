<?php
include_once 'config.php';
ini_set('display_errors', E_ALL);
if(!file_exists($TMP_PATH.'/tree'))
  {
  mkdir($TMP_PATH.'/tree');
  }

set_time_limit(60*20);

require_once 'library/Git/Repository.php';
require_once 'library/Git/Command.php';
require_once 'library/Git/Exception/GitRuntimeException.php';
require_once 'library/Git/Exception/InvalidGitRepositoryDirectoryException.php';

try
  {  
  $repoMain = new Repository($GIT_PATH_MAIN, true, array('git_executable' => $GIT_EXECUTABLE));

  $repoMain->git("git checkout master"); 
  $repoMain->git("git reset --hard"); 
  $repoMain->git("git pull origin master");
  }
catch (InvalidGitRepositoryDirectoryException $exc)
  {
  echo $exc->getMessage().'<br/>';
  echo $exc->getTraceAsString();  
  exit;
  }
catch (GitRuntimeException $exc)
  {
  echo $exc->getMessage().'<br/>';
  echo $exc->getTraceAsString();  
  exit;
  }
catch (Exception $exc)
  {
  echo $exc->getMessage().'<br/>';
  echo $exc->getTraceAsString();  
  exit;
  }

if(file_exists($TMP_PATH."/tree/tree.txt"))
  {
  unlink($TMP_PATH."/tree/tree.txt");
  }

$json = json_encode(_browseDir($GIT_PATH_MAIN));
file_put_contents($TMP_PATH."/tree/tree.txt", $json);


function _browseDir($dir, $files = array())
  {
  if(is_dir($dir)) 
    {      
    $objects = scandir($dir); 
    
    foreach($objects as $object) 
      { 
      if($object != "." && $object != ".." && $object != ".git") 
        {       
        if(filetype($dir."/".$object) == "dir")
          {
          $files = _browseDir($dir."/".$object, $files);
          }
        else if(substr($object, strlen ($object)-2) == '.h')
          {
          $files[$object] = $dir."/".$object;
          }
        } 
      } 
    }

  return $files;
  }
?>
