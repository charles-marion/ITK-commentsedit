<?php
if (!isset($_GET['file']))
  {
  echo "Please set a file path";
  exit;
  }
$path = $_GET['file'];
include_once 'config.php';

//captcha
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
  <?php
echo $LIBRAIRY_NAME.": ".basename($path);
?>
  </title>
  <link rel="stylesheet" media="screen" type="text/css" title="Design" href="css/main.css" />
  <link href="<?php echo $DOXYGEN_URL?>/DoxygenStyle.css" rel="stylesheet" type="text/css">
  <script src="js/jquery-1.4.3.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/s3Capcha.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/jquery.editinplace.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/jquery-syntax/jquery.syntax.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/main.js" type="text/javascript" charset="utf-8"></script>
  
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

<div class="contents"> 
<br/>
<h1><?php echo basename($path);?></h1>

<table align="center">
  <tr>
  	<td valign="top" width="400">
  	
  	<p>This page allow you to modify the comments of the librairy. Your request will create a request of patch which will be reviewed by Kitware. </p>
  	<p>To modify the comment, just click on what you want to modify. You can also add or delete comment.</p>
  	
  	<p>When you are done, please submit this form to send your request.</p>
  	
  	
  	
  	<form onsubmit="return checkSubmit();" id="send" name="send" action="submit.php" method="post">
  	<b>Your email: <span style="color:red;">*</span></b><br/>
  	<input class="mainFormInput" id="emailMainForm" type="text" name="email" />
  	<br/>
  	<b>Comments:</b><br/>
  	<textarea class="mainFormInput" name="comment"></textarea> <br/>
	<div id="s3capcha"><?php echo $s3Capcha ?> </div>
    <input type="hidden" name="filename" value="<?php echo @$_GET['file']?>" />
    <textarea id="dataSourceCode" style="display:none;"  name="source" ></textarea>
    <textarea id="dataSourceCodeInitial" style="display:none;" name="sourceInitial" ></textarea>
    <input class="mainFormInput"  id="submitMainForm" type="submit" value="Send >>"/>
    </form>
    <br/>    
     <div class="warning"><img src="img/warning.jpg" /> You modified the code. Please double check your comments before triing to send your request.<br/></div>	
     
     <a class="undo" href="javascript:;" style="display: none;cursor:pointer;" >> Undo last change</a>
     
     <br/>
     <br/>
     <a href="javascript:;" onclick="$('.quickGuide').toggle();">> Quick command guide</a>
     <br/>
     <a href="http://www.stack.nl/~dimitri/doxygen/commands.html" target="_new">>Full command guide</a>
     
     <br/>
     <div class="quickGuide" style="display:none;">
     <br/>
     <b>Quick Guide:</b>
     <p><strong>\class</strong> to document a C++<br>
      <strong>\struct</strong> to document a C-struct.<br>
      <strong>\enum</strong> to document an enumeration type.<br>
      <strong>\fn</strong> to document a function.<br>
      <strong>\var</strong> to document a variable or <tt>typedef</tt> or <tt>enum</tt> value.<br>
      <strong>\def</strong> to document a <tt>#define</tt> (macros).<br>
      <strong>\typedef</strong> to document a type definition.<br>
      <strong>\file</strong> to document a file.<br>
      <strong>\namespace</strong> to document a namespace.<br>
     </div>
  	</td>
  	<td>   
      <div  class="codeContainer">  
        <pre id="sourceCode" class="syntax brush-clang">
  			<img src="img/loading.gif" />
        </pre>
      </div>
      </div> 
    </td>
   </tr>
</table>
  </body>
</html>
