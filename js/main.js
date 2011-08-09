  var content;
  var initialContent;
  var contentArray = new Array();
  var initialContentArray = new Array();
  var lastContentArray=new Array();
  jQuery(function($)             {
	  $('#s3capcha').s3Capcha();
	  $('.undo').bind("click",function(){undo();});
		getSourceCode("getcontent_ajax.php?file="+$("input[name=filename]").val());
          });

  function getSourceCode(path)
      {	
	  $.ajax(  {
		  url: path,
		  timeout: 5000,
		  success: function(data)   {
			  if(data=='-1')
			      {
				  $('#sourceCode').html("<img class='warningImg' src='img/warning.jpg' />The source repository is unavailable, please try again later");
			      }
			  else if (data=='-2')
			      {
				  $('#sourceCode').html("<img class='warningImg' src='img/warning.jpg' />Unable to find the source file. Please check if the file exists and try again.");
			      }
			  else
			      {
				  content=data;
				  initialContent=data;
				  contentArray=data.split('\n');
				  lastContentArray=contentArray.slice();
				  initialContentArray=data.split('\n');
				  $('#dataSourceCodeInitial').val(content);
				  updateSyntaxField(contentArray);
			      }
			    
		    },
		   error: function(XMLHttpRequest, textStatus, errorThrown)   {
			   $('#sourceCode').html("<img class='warningImg' src='img/warning.jpg' /> The source repository is unavailable, please try again later");
       		  }
		  }); 
  	$.get(path, function(data)     {	  
	  
	    });
      }

  function updateSyntaxField()
    {
	  if(contentArray.join('')==lastContentArray.join(''))
		  {
		  $(".undo").hide();
		  }
	  else
		  {
		  $(".undo").show();
		  }
	  content='';
	  $.each(contentArray, function(index, value) { 
		  content+=value+"\n";
		});
	  $('#dataSourceCode').val(content);
	  content='<div class="action"></div><pre id="sourceCode" class="syntax brush-clang">'+content+"</pre>";
	  $('.codeContainer').html(content);
	  
	  $.syntax(            {root: "js/jquery-syntax/"});
	  createActionList();
	  if($.browser.msie&&(jQuery.browser.version<8))
        {
        $('.edit').show();
        }
      else
        {
        $('.edit').hide();
        }
	  checkCode();
    	$(".comment[edit='true']").editInPlace({
    		callback: function(unused, enteredText) {
    			lastContentArray=contentArray.slice();
    			if(enteredText.length==enteredText.lastIndexOf("\n")+1)
    				{
    				enteredText=enteredText.substring(0,enteredText.length-1);
    				}
        		line=$(this).parents('li').attr('class');        	
        		line=line.replace(" alt", "");
        		line=line.replace("line ln", "");
        		line=parseInt(line);
        		var enteredArray=enteredText.split("\n");
        		var i = 0;
        		 $.each(enteredArray, function(index, value) { 
             		if(value.length>80)
            		{
             			var j =0;
                		var tmpText = value.substr(j,80);
                		contentArray[line+i]=tmpText;
                		while(i*80<value.length)
                		{
                    		i++;
                    		j++;
                    		value = value.substr(j*80,80);
                    		contentArray=addContent(line+i,value);
                		}            		
            		}
            		else
                	{
                    	if(i==0)contentArray[line+i]=value;
                    	else
                    		{
                    		contentArray=addContent(line+i,value);
                    		}
                	}
             		i++;
        			});
        		
        		updateSyntaxField();
        		return "";
        		},
    		// url: "./server.php",
    		bg_over: "#cff",
    		field_type: "textarea"
    	});
    }

  function undo()
  {
	  contentArray=lastContentArray.slice();
	  updateSyntaxField();
  }
  
  function addContent(line,text)
  {
	  var tmpArray= new Array();
	  var i=0;
	  $.each(contentArray, function(index, value) { 
		  if(line==index+i)
		  {
			  tmpArray[index+i]=text;
			  i++;
		  }
		  tmpArray[index+i]=value;
		});
    if($.browser.msie&&(jQuery.browser.version<8))
        {
        $('.edit').show();
        }
      else
        {
        $('.edit').hide();
        }
	return tmpArray;
  }

  function deleteLine(line)
  {
	  var tmpArray= new Array();
	  var i=0;
	  $.each(contentArray, function(index, value) { 
		  if(line==index)
		  {
			i--;
		  }
		  tmpArray[index+i]=value;
		});
	  if($.browser.msie&&(jQuery.browser.version<8))
        {
        $('.edit').show();
        }
      else
        {
        $('.edit').hide();
        }
	return tmpArray;
  }
  function sendModification()
  {
	  content='';
	  $.each(contentArray, function(index, value) { 
		  content+=value+"\n";
		});
	  alert(content);
	  return false;	  
  }

function var_dump(obj) {
   if(typeof obj == "object") {
      return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
   } else {
      return "Type: "+typeof(obj)+"\nValue: "+obj+"\nlongeur: "+obj.length;
   }
}//end function var_dump
  function createActionList()
  {
  var disclaimer=true;
	  $('span.clang').each(function(index) {
        if(disclaimer==true)
          {
          if($(this).children('.comment').text().indexOf("=====================================*/")!=-1)
            {
            $(this).children('.comment').attr('edit', 'false');
            disclaimer=false;
            }
          return;
          }
        $(this).children('.comment').attr('edit', 'true');
		    if($(this).children('.comment').length)
		    {
		    	$(this).append("<div class='edit' style='display:none;' ><span class='editSpan'><img class='addLine' alt='Add Line' src='img/add.png' /><img class='editLine' alt='Edit Line' src='img/edit.png' /><img class='deleteLine' alt='Delete Line' src='img/drop.png' /></span></div>");
		    }
		    else
		    {
			    $(this).append("<div class='edit' style='display:none;' ><span class='editSpan' ><img class='addLine' alt='Add Line' src='img/add.png' /></span></div>");	
		    }
		  });
      if($.browser.msie&&(jQuery.browser.version<8))
        {
          $(this).children().children().children('.edit').show();
          if($.browser.msie&&(jQuery.browser.version<7))
            {
              $('body').css("position","relative");
              $('.editSpan').css("left","-650px");
              $('.editSpan').css("top","-138px");
            }
          $('.edit').each(function(index)
          {
    
            if($(this).parents('span').text().length==1)
              {
                $(this).html("");
              }
          });
        }
      else
        {
        $("li").bind("mouseover",function(){
        $(this).children().children().children('.edit').show();});
        $("li").bind("mouseout",function(){
        $(this).children().children().children('.edit').hide();});
        }

      $(".deleteLine").bind("click",function(){
    	  line=$(this).parents('li').attr('class');        	
    	  line=line.replace(" alt", "");
    	  line=line.replace("line ln", "");
    	  line=parseInt(line);
          contentArray=deleteLine(line);
          updateSyntaxField();});
      $(".addLine").bind("click",function(){
    	  line=$(this).parents('li').attr('class');        	
    	  line=line.replace(" alt", "");
    	  line=line.replace("line ln", "");
    	  line=parseInt(line);
          contentArray=addContent(line,'  //');
          updateSyntaxField();});
  }
  
  function checkCode()
  {
	  if(cleanCode(contentArray)!=cleanCode(initialContentArray))
		  {
		  $(".warning").show();
		  $("#submitMainForm").attr('disabled', 'disabled');
		  return false;
		  }
	  $(".warning").hide();
	  $("#submitMainForm").attr('disabled', '');
	  return true;
  }
  
  function checkSubmit()
  {
	  if(!checkCode())
		  {
		  return false;
		  }
	  if(contentArray.join('')==initialContentArray.join(''))
		  {
		  alert('No changes noticed');
		  return false;
		  }
	  if($('#emailMainForm').val()=='')
		  {
		  alert('Please set en e-mail');
		  return false;
		  }
	 return true;
  }
  
  function cleanCode(codeArray)
  {
	  var code='';
	  var disable=false;
	  $.each(codeArray, function(index, value) {	
		  
		  if(value.indexOf("/*")!=-1)
			  {
			  code+=value.substring(0,value.indexOf("/*"));
			  disable=true;
			  }
		  if(!disable&&value.indexOf("//")==-1)
			  {			  
			  code+=value;
			  }
		  if(!disable&&value.indexOf("//")!=-1)
			  {
			  code+=value.substring(0,value.indexOf("//"));
			  }
		  if(disable&&value.indexOf("*/")!=-1)
			  {

			  code+=value.substring(value.indexOf("*/")+2);
			  disable=false;
			  }
		});
	  return code.split(' ').join('').split('\n').join('');
  }