
<?php
$_POST['source']=stripslashes($_POST['source']);
@session_start();
if ($_POST['s3capcha'] == $_SESSION['s3capcha'] && $_POST['s3capcha'] != '')
  {
  unset($_SESSION['s3capcha']);

  include_once 'config.php';

  $found = "";
  $foldersArray = explode(";", $FOLDERS);
  $i = 0;
  if (!file_exists('tmp/tree/' . $foldersArray[0]))
    {
    include("gettree.php");
    }
  while ($found == "" && $i < count($foldersArray))
    {
    $content = file_get_contents('tmp/tree/' . $foldersArray[$i]);
    if (strpos($content, $_POST['filename']) !== false)
      {
      $found = $foldersArray[$i];
      break;
      }
    $i++;
    }
  if ($found == "")
    {
    echo "-2";
    exit;
    }

  $defaultRepertory=getcwd();
  chdir($GIT_PATH);

  system("git reset --hard HEAD");
  system("git pull");
  chdir($defaultRepertory);

  $filePath = $GIT_PATH."/Code/".$found."/" . $_POST['filename'];

  $initialFile = "initial_".rand();
  while (file_exists("tmp/".$initialFile))
    {
    $initialFile = "initial_".rand();
    }

  $newFile = "new_".rand();
  while (file_exists("tmp/".$newFile))
    {
    $newFile = "new_".rand();
    }

  $resultFile = "result_".rand();
  while (file_exists("tmp/".$resultFile))
    {
    $resultFile = "result__".rand();
    }

  $FI = "tmp/".$initialFile;
  $FN = "tmp/".$newFile;

  $inFI = fopen($FI, "w");
  $inFN = fopen($FN, "w");

  fwrite($inFI, file_get_contents($filePath));
  fwrite($inFN, str_replace("\r", "", $_POST['source']));

  fclose($inFI);
  fclose($inFN);

  system("diff -u ".getcwd()."/tmp/".$newFile." ".$filePath." > ".getcwd()."/tmp/".$resultFile);

  echo "<pre>".file_get_contents("tmp/".$resultFile)."</pre>";

  chdir($GIT_PATH."/Code/".$found);
  system("patch -R -p0 < $defaultRepertory/$resultFile",$retval);
  chdir($defaultRepertory);
  unlink($FI);
  unlink($FN);
  unlink("tmp/".$resultFile);
  }
else
  {
    include_once 'config.php';
    @session_start();
    $values     = array('apple','strawberry','lemon','cherry','pear'); // image names //   // array('house','folder','monitor','man','woman','lock','rss'); -> for general theme
    $imageExt   = 'jpg'; // image extensions //
    $imagePath  = 's3icons/fruit/'; // image path //  // images/general/ -> for general theme
    $imageW     = '33'; // icon width // 35 -> for general theme
    $imageH     = '33'; // icon height // 35 -> for general theme
    $rand       = mt_rand(0,(sizeof($values)-1));
    shuffle($values);
    $s3Capcha = '<p>Verify that you are a human, please choose <strong>'.$values[$rand]."</strong></p>\n";
    for($i=0;$i<sizeof($values);$i++) {
        $value2[$i] = mt_rand();
        $s3Capcha .= '<div><span>'.$values[$i].' <input type="radio" name="s3capcha" value="'.$value2[$i].'"></span><div style="background: url('.$imagePath.$values[$i].'.'.$imageExt.') bottom left no-repeat; width:'.$imageW.'px; height:'.$imageH.'px;cursor:pointer;display:none;" class="img" /></div></div>'."\n";
    }
    $_SESSION['s3capcha'] = $value2[$rand];
    $s3Capcha;
 ?>
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
  <script type="text/javascript">
  $(document).ready(function() { 
   $('#s3capcha').s3Capcha();
});</script>  
  </head>
  <body>
  

  <center>
    <a href="<?php echo $DOXYGEN_URL?>index.html" class="qindex">Main Page</a>&nbsp;&nbsp; 
    <a href="<?php echo $DOXYGEN_URL?>modules.html" class="qindex">Groups</a>&nbsp;&nbsp;
    <a href="<?php echo $DOXYGEN_URL?>namespaces.html" class="qindex">Namespace List</a>&nbsp;&nbsp;
    
    <a href="<?php echo $DOXYGEN_URL?>hierarchy.html" class="qindex">Class Hierarchy</a>&nbsp;&nbsp;
    <a href="<?php echo $DOXYGEN_URL?>classes.html" class="qindex">Alphabetical List</a>&nbsp;&nbsp;
    <a href="<?php echo $DOXYGEN_URL?>annotated.html" class="qindex">Compound List</a>&nbsp;&nbsp; 
    <a href="<?php echo $DOXYGEN_URL?>files.html" class="qindex">File List</a>&nbsp;&nbsp; 
    <a href="<?php echo $DOXYGEN_URL?>namespacemembers.html" class="qindex">Namespace Members</a>&nbsp;&nbsp;
    <a href="<?php echo $DOXYGEN_URL?>functions.html" class="qindex">Compound Members</a>&nbsp;&nbsp; 
    <a href="<?php echo $DOXYGEN_URL?>globals.html" class="qindex">File Members</a>&nbsp;&nbsp;
    <a href="<?php echo $DOXYGEN_URL?>pages.html" class="qindex">Concepts</a>
  </center>
  
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