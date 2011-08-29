<?php include_once 'config.php';
var_dump($_POST['source']);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <title>
   Submit Request Error
  </title>
  

  <link rel="stylesheet" media="screen" type="text/css" title="Design" href="css/main.css" />
  <link href="<?php echo $DOXYGEN_URL?>/DoxygenStyle.css" rel="stylesheet" type="text/css">
  <script src="js/jquery-1.4.3.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/s3Capcha.js" type="text/javascript" charset="utf-8"></script>

  </head>
  <body>

  <?php 
  include 'header.php';
  ?>
  
<?php
ini_set('display_errors', E_ALL);

//$_POST['source']=stripslashes($_POST['source']);
$json = file_get_contents($TMP_PATH."/tree/tree.txt");
$files = json_decode($json);
$file = $_POST['filename'];
session_start();
if(isset($_POST['check']) && file_exists($TMP_PATH."/doxygen/".$_POST['check']))
  {
  set_time_limit(60*20);

  require_once 'library/Git/Repository.php';
  require_once 'library/Git/Command.php';
  require_once 'library/Git/Exception/GitRuntimeException.php';
  require_once 'library/Git/Exception/InvalidGitRepositoryDirectoryException.php';
  try
    {  
    $repoMain = new Repository($GIT_PATH_MAIN, false, array('git_executable' => $GIT_EXECUTABLE));
    $repoMain->git("git checkout master");
    $branch = "EditDoxygen_".substr($_POST['filename'], 3, strlen($_POST['filename']) - 5)."_".time();
    $repoMain->git("git checkout -b ".$branch); 
    unlink($files->$file);
    $inF = fopen($files->$file,"w");     
    fwrite($inF, substr($_POST['source'], 0, strlen($_POST['source'])-2));
    fclose($inF);
    exec('perl -pi -e \'s/\r\n/\n/\' '.$files->$file);

    file_put_contents($TMP_PATH.'/message.txt', "STYLE: Edit Documentation class: ".substr($_POST['filename'], 3, strlen($_POST['filename']) - 5)."\n\nAuthor: ".$_POST['email']."\n".$_POST['comment']);
    $repoMain->git("git commit -a -F ".$TMP_PATH.'/message.txt'); 
    //$repoMain->git("git gerrit-push");
    file_put_contents($TMP_PATH.'/todo.txt', $branch.';'.$GIT_PATH_MAIN, FILE_APPEND);
    $repoMain->git("git checkout master");
    echo "<br/>";
    echo "The change will be posted to gerrit in the next 15 minutes: <a href='http://review.source.kitware.com'>http://review.source.kitware.com</a>";
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
  }
else if ($_POST['s3capcha'] == $_SESSION['s3capcha'] && $_POST['s3capcha'] != '')
  {
  unset($_SESSION['s3capcha']);

  $time = time();
  $json = file_get_contents($TMP_PATH."/tree/tree.txt");
  $files = json_decode($json);
  $file = $_POST['filename'];

  if(!file_exists($TMP_PATH."/doxygen"))
    {
    mkdir($TMP_PATH."/doxygen");
    }
    
    
  $path = $TMP_PATH."/doxygen/".$time;
  mkdir($path);
  copy('editcomment.conf.txt', $path.'/editcomment.conf');
  $inF = fopen($path.'/'.$file,"w");     
  fwrite($inF, substr($_POST['source'], 0, strlen($_POST['source'])-2));
  fclose($inF);
  exec('perl -pi -e \'s/\r\n/\n/\' '.$path.'/'.$file);

  $defaultRepertory = getcwd();
  chdir($path);
  exec('doxygen editcomment.conf');
  $preview = '';
  if(file_exists($path.'/html/classitk_1_1'.substr($file, 3, strlen($file) - 5).'.html'))
    {
    $preview = file_get_contents($path.'/html/classitk_1_1'.substr($file, 3, strlen($file) - 5).'.html');      
    $preview = str_replace('tabs.css', '', $preview);
    $preview = str_replace('search/search.css', '', $preview);
    $preview = str_replace('search/search.js', '', $preview);
    $preview = str_replace('doxygen.css', '', $preview);
    $preview = str_replace('search/close.png', '', $preview);
    $preview = str_replace('search/mag_sel.png', '', $preview);
    $preview = str_replace('doxygen.png', '', $preview);
    }
  exec('rm -R *');
  chdir($defaultRepertory);
  ?>
    <script type="text/javascript">
      $(document).ready(function(){
        $('div#preview #top').hide();
        $('div#preview').show();
      });
    </script>
    
    <h2> Preview</h2>
    <form id="send" name="send" action="submit.php" method="post">
      <input type="hidden" name="comment" value="<?php echo $_POST['comment']?>" />
      <input type="hidden" name="check" value="<?php echo $time?>" />
      <input type="hidden" name="email" value="<?php echo $_POST['email']?>" />
      <input type="hidden" name="filename" value="<?php echo $_POST['filename']?>" />
      <textarea id="dataSourceCode" type="hidden" style="display:none;" name="source"><?php echo $_POST['source']?></textarea>
      <textarea id="dataSourceCodeInitial" type="hidden" style="display:none;" name="sourceInitial"><?php echo $_POST['sourceInitial']?></textarea>
        
      <input style="width:300px;" type="submit" value="Submit to the reviewing process (gerrit) >>"/>
      <br/>	<br/>
    </form>
    <div id ="preview" style="border:1px solid black; width:80%;height: 500px;overflow: scroll;display:none;">
      <?php echo $preview?>
    </div>
  <?php
  }
else
  {
    include_once 'config.php';
    $values     = array('apple','strawberry','lemon','cherry','pear'); // image names //   // array('house','folder','monitor','man','woman','lock','rss'); -> for general theme
    $imageExt   = 'jpg'; // image extensions //
    $imagePath  = 's3icons/fruit/'; // image path //  // images/general/ -> for general theme
    $imageW     = '33'; // icon width // 35 -> for general theme
    $imageH     = '33'; // icon height // 35 -> for general theme
    $rand       = mt_rand(0,(sizeof($values)-1));
    shuffle($values);
    $s3Capcha = '<p>Verify that you are a human, please choose <strong>'.$values[$rand]."</strong></p>\n";
    for($i=0;$i<sizeof($values);$i++) {
        $value2[$i] = mt_rand(0,1000);
        $s3Capcha .= '<div><span>'.$values[$i].' <input type="radio" name="s3capcha" value="'.$value2[$i].'"></span><div style="background: url('.$imagePath.$values[$i].'.'.$imageExt.') bottom left no-repeat; width:'.$imageW.'px; height:'.$imageH.'px;cursor:pointer;display:none;" class="img" /></div></div>'."\n";
    }
    $_SESSION['s3capcha'] = "ttt".$value2[$rand];
    $s3Capcha;
 ?>
  <script type="text/javascript">
  $(document).ready(function() { 
   $('#s3capcha').s3Capcha();
});</script>  
  <form id="send" name="send" action="submit.php" method="post">
	<div id="s3capcha"><?php echo $s3Capcha ?> </div>
		<input type="hidden" name="comment" value="<?php echo $_POST['comment']?>" />
		<input type="hidden" name="email" value="<?php echo $_POST['email']?>" />
    <input type="hidden" name="filename" value="<?php echo $_POST['filename']?>" />
    <textarea id="dataSourceCode" type="hidden" style="display:none;" name="source"><?php echo $_POST['source']?></textarea>
    <textarea id="dataSourceCodeInitial" type="hidden" style="display:none;" name="sourceInitial"><?php echo $_POST['sourceInitial']?></textarea>
			<br/>	<br/>
    <input style="width:100px;" type="submit" value="Send >>"/>
    	</form>
   </body>
</html>
 <?php
  }
?>