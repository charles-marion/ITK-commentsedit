jQuery(function($)  
{
if($("h1:first").html().indexOf("Class Template Reference")!=-1)
  {
  $("h1:first").after('<div style="float:right;"><a href="http://www.itk.org/editdoc/editcomments.php?file='+$("code a.el:first").html()+'">Edit comments</a></div>');
  }
});