jQuery(function($)  
{
if($("div.title").html().indexOf("Class Template Reference")!=-1)
  {
  $("div.summary").prepend('<a href="http://www.itk.org/editdoc/editcomments.php?file='+$("code a.el:first").html()+'">Edit comments</a> |');
  }
});