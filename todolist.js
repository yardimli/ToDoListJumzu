var LastHoverItem = "";
var InsertType ="";
var InsertPosition = "";
var InsertPos = "";
var NewItemAddMode = "";
var AddingControl = false;
var MovingControl = false;
var EditingContorl = false;
var IsDeleteContorl = false;
var ToDoCounter = 100;
var newOrderData= "";
var XLevel = 0;
var XLine = 0;
var XParent = 0;
var newXML = "";
var newNestedXML = "";
var ListChanged = 0;
var ListNameChanged = 0;
var thisX2;
var IgnoreSearch = false;
var enableHandler = false;
var LeftMenuInAnimation = false;
var LastOpenMenu = "";
var DeleteItemX ="";
var LastListDiv = "";
var EditingTitle = false;


$(document).ready(function(){ 
	// Dialog Link
	$('#dialog_link').click(function(){ 
		
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		
		$('#dialog-themes').dialog({
				modal:true,
				width: 430,
				height: 390,
				buttons: {
					"Ok": function() { $(this).dialog("close"); },
					"Cancel": function() { $(this).dialog("close"); }
				}
			});		
		return false; 
	});

	$(".panel-bar").click( function () {
		if (!LeftMenuInAnimation)
		{
			if (LastOpenMenu!="") { LeftMenuInAnimation=true;  $(LastOpenMenu).next().hide({animation:'slideUp',duration:500,easing:"swing",queue: false , complete:function(){ LeftMenuInAnimation = false; } } ); }

			if ( $(this).next().is(":visible") ) { LeftMenuInAnimation=true; LastOpenMenu=""; $(this).next().hide({animation:'slideUp',duration:500,easing:"swing",queue: false, complete:function(){  LeftMenuInAnimation = false; } } );  } else
												 { LeftMenuInAnimation=true; LastOpenMenu=this; $(this).next().show({animation:'slideDown',duration:500,easing:"swing",queue: false, complete:function() { LeftMenuInAnimation = false; } } );  }
		}
	});


	$(".theme-info").bind("click",function() { if ( $(this).next("div").css("visibility") == "visible" ) {	$(this).next("div").css({"visibility":"hidden"}); } else { $(this).next("div").css({"visibility":"visible"}); }	});


	function HoverPlaceholder(e)
	{
		mouseX = parseInt(e.pageX);
		mouseY = parseInt(e.pageY);

		mouseX = parseInt( $("#list_999999").offset().left );
		//mouseY = parseInt( $("#list_999999").offset().top );
		

		TheThingFound = false;
		IgnoreSearch = false;
		//loop through all items find the last one the mouse is on top of
		$('#todolist1').find('li').each(function(index){
			xPos1 = parseInt( $(this).offset().left );
			xPos2 = parseInt( $(this).offset().left + $(this).width() );
			yPos1 = parseInt( $(this).offset().top );
			yPos2 = parseInt( $(this).offset().top + $(this).height()+5 );

			//console.log(parseInt(e.pageX)+" "+mouseX+" , " +parseInt(e.pageY)+" "+ mouseY + ") "+$(this).attr("id")+" "+yPos1+" "+(yPos2)+" "+xPos1+" "+(xPos2));

			if ( (mouseY >= yPos1) && (mouseY<=yPos2) && (mouseX >= xPos1) && (mouseX<=xPos2) && ( ($(this).attr("id")!="list_888888") && ($(this).attr("id")!="list_888880") ) && ($(this).attr("id").indexOf("list_")==0) )
			{

				theID = $(this).attr("id");
				thisX2 = $(this);
				TheThingFound = true;
				//console.log("found:"+theID);

				ZxPos1 = parseInt( $(this).offset().left );
				ZxPos2 = parseInt( $(this).offset().left + $(this).width() );
				ZyPos1 = parseInt( $(this).offset().top );
				ZyPos2 = parseInt( $(this).offset().top + $(this).height() );
			}
		});

		if ( $('#todolist1').children().length == 0 ) {
			
			$('#todolist1').css({"width":"99%","height":"400px"});
			thisX2 = $('#todolist1');
			LastHoverItem = "todolist1";
			thisX2.append('<li id="list_888888" class="placeholder" style="height:31px"><div id="list_888888_div" class="sortable-div1">add under</div></li>');
			NewItemAddMode = "AddFirst";
		} else

		if ((!TheThingFound) && (NewItemAddMode!="AddFirst"))
		{
			$("#list_888888").remove();
		} else

		if ((TheThingFound) && (!IgnoreSearch))
		{
			InsertPos = "";

			if ( (mouseY > (ZyPos1)) && (mouseY<=ZyPos2) )
			{
				InsertPos = "bellow";
			}

			if ( (mouseY >= ZyPos1) && (mouseY<=ZyPos2 ) && (mouseX>=(xPos1+25))  )
			{
				InsertPos = "addChild";
			}
			
			if ( ((LastHoverItem!=theID) || (InsertPosition!=InsertPos)) && (InsertPos!="") )
			{
				InsertPosition = InsertPos;

				$("#list_888888").remove();

				//$("#"+LastHoverItem).removeClass("sortable-div-2");
				LastHoverItem = theID;
				//$("#"+LastHoverItem).addClass("sortable-div-2");
				//console.log(InsertPosition);

				if (InsertPosition=="addChild")
				{
					if (thisX2.has("ol").length)
					{
						thisX2.find("ol:first").prepend('<li id="list_888888" class="placeholder" style="height:31px"><div id="list_888888_div" class="sortable-div1">insert into list under</div></li>');
						NewItemAddMode = "InsertUnderChild";
					} else
					{
						thisX2.append('<ol id="list_888888"><li id="list_888880" class="placeholder" style="height:31px"><div id="list_888888_div" class="sortable-div1">create list under</div></li></ol>');
						NewItemAddMode = "CreateUnder";
					}
				} else
				
				if (InsertPosition=="bellow")
				{
					if (thisX2.has("ol").length)
					{
						thisX2.find("ol:first").prepend('<li id="list_888888" class="placeholder" style="height:31px"><div id="list_888888_div" class="sortable-div1">insert into list under 2</div></li>');
						NewItemAddMode = "InsertUnder";
					} else
					{
						thisX2.append('<li id="list_888888" class="placeholder" style="height:31px"><div id="list_888888_div" class="sortable-div1">add under</div></li>');
						NewItemAddMode = "AddUnder";
					}
				}
			}
		}				
	}


	$(document).mousemove(function(e){
		if (enableHandler) {
			if (AddingControl) { HoverPlaceholder(e); }
		}	
		enableHandler = false;
	});


	//------------------------------------------ ON READY INITS
	$("#acordion_menu_1_child").hide();
	//$("#acordion_menu_2_child").hide();
	LastOpenMenu = $("#acordion_menu_2").children("div");

	LoadUserToDoLists();
	LoadToDoItems(0);
	checkToSave();

	timer = window.setInterval(function() {  enableHandler = true;  }, 100);
});

//----------------------------------------------------FUNCTIONS OUTSIDE READY FOR SCOPE TO WORK THESE HAVE TO BE HERE


$.fn.textWidth = function(text){
  var org = $(this)
  var html = $('<span style="postion:absolute;width:auto;left:-9999px">' + (text || org.html()) + '</span>');
  if (!text) {
    html.css("font-family", org.css("font-family"));
    html.css("font-size", org.css("font-size"));
  }
  $('body').append(html);
  var width = html.width();
  html.remove();
  return width;
}


//------------------------------------------------------------------------------
function initEdits()
{
	$('input[type=checkbox]').live('change', function() { 
		var TempID = $(this).attr("id");
		if (TempID!="rememebr_me")
		{
			ListChanged=1; 
			TempID = TempID.replace("_check","_text");


			if ($(this).is(":checked"))
			{
				$("#"+ TempID ).css({"text-decoration":"line-through"} );
			} else
			{
				$("#"+ TempID ).css({"text-decoration":"none"} );
			}
		}
		
	});

	$('input[type=checkbox]').checkbox({cls:'jquery-safari-checkbox'});

	$('.checkLabel').hover( 
		function() { 
			if ($(this).textWidth() > $(this).width() ) {$(this).attr("title", $(this).text() ); }  
		} , 
		function() { 
			$(this).attr("title",""); 
		} );

	$('.checkLabel').editable(function(value, settings) { 
		 //console.log(this);
		 //console.log(value);
		 //console.log(settings);
		 ListChanged = 1;
		 return(value);
	  }, { 
		 type    : 'text',
		 cssclass: 'itemeditclass2',
		 submit  : '',
	 });

	$('#todo_list_title').editable(function(value, settings) { 
		//console.log(this);
		//console.log(value);
		//console.log(settings);
		ListChanged = 1;
		ListNameChanged = 1;
		return(value);
	}, { 
		type    : 'text',
		cssclass: 'itemeditclass3',
		submit  : '',
	});


/*
	ol_sortable_height = $('ol.sortable').height();
	if (ol_sortable_height<600) {ol_sortable_height = 600;}

	newHeight = ol_sortable_height+100;
	$('#stage').css('height',newHeight+"px" );

	newHeightGlow = ol_sortable_height-78;
	$('#glow-mid').css('height',newHeightGlow+"px" );

	newTop = ol_sortable_height+150;
	$('#footer').css('top',newTop+"px" );

	$('#newfooter').css('z-index',"100" );
*/	

	content_height = $('#content').height();
	
	content_height2 = $('#todolist1').height()+100;
	if (content_height<content_height2) {content_height=content_height2;}
	
	if (content_height2<content_height) {content_height=content_height2;}
	if (content_height<700) {content_height = 700;}

	newHeight = content_height;
	$('#content').css('height',newHeight+"px" );
	$('#stage').css('height',(newHeight-36)+"px" );
	

	newHeightGlow = content_height-208;
	$('#glow-mid').css('height',newHeightGlow+"px" );

	newTop = $('#content').height();
	$('#footer').css('top',newTop+"px" );
	$('#footer').css('height',($(window).height()-$('#content').height()-60) +"px" );
	$('#newfooter').css('z-index',"100" );
	

	$('.glow').css('top',$('#content').offset().top-115 );
	$('.glow').css('left',$('#content').offset().left-128 );
	$('.glow').show();
}

$(window).resize(function() {
	content_height = $('#content').height();
	
	content_height2 = $('#todolist1').height()+100;
	if (content_height<content_height2) {content_height=content_height2;}
	
	if (content_height2<content_height) {content_height=content_height2;}
	if (content_height<700) {content_height = 700;}

	newHeight = content_height;
	$('#content').css('height',newHeight+"px" );
	$('#stage').css('height',(newHeight-36)+"px" );
	

	newHeightGlow = content_height-208;
	$('#glow-mid').css('height',newHeightGlow+"px" );

	newTop = $('#content').height();
	$('#footer').css('top',newTop+"px" );
	$('#footer').css('height',($(window).height()-$('#content').height()-60) +"px" );
	$('#newfooter').css('z-index',"100" );
	

	$('.glow').css('top',$('#content').offset().top-115 );
	$('.glow').css('left',$('#content').offset().left-128 );
	$('.glow').show();
});




//------------------------------------------------------------------------------
function handleDragStart( event, ui ) {
	AddingControl = true;
}

//------------------------------------------------------------------------------
function handleDragStop( event, ui ) {
	AddingControl = false;
	$("#list_888888").remove();
}

//------------------------------------------------------------------------------
function myHelper( event ) {
  return '<div id="list_999999" class="sortable-div" style="width:500px; opacity:0.6; margin-left:0px"><input type="checkbox" id="list_999999_check" /><label class="checkLabel" for="list_999999_check">New ToDo Item</label></div>';

}	


//------------------------------------------------------------------------------
function sortChanged(event,ui) {
	/*console.log("change");*/
	ListChanged = 1;
	
}

//------------------------------------------------------------------------------
function initList()
{
	$('#todolist1').droppable({
		accept: "#controls-checkbox",
		drop: function( event, ui ) {
			if (AddingControl==true)
			{
				ToDoCounter++;

				if (NewItemAddMode=="InsertUnderChild")
				{
					$('ol.sortable').find("#"+LastHoverItem).find("ol:first").prepend('<li id="list_'+ToDoCounter+'"><div id="list_'+ToDoCounter+'_div" class="sortable-div"><input type="checkbox" id="list_'+ToDoCounter+'_check" /><span class="checkLabel" id="list_'+ToDoCounter+'_text" style="width:90%; display:inline-block;">New ToDo Item</span></div></li>');
				} else
				if (NewItemAddMode=="AddUnder")
				{
					$('ol.sortable').find("#"+LastHoverItem).after('<li id="list_'+ToDoCounter+'"><div id="list_'+ToDoCounter+'_div" class="sortable-div"><input type="checkbox" id="list_'+ToDoCounter+'_check" /><span class="checkLabel" id="list_'+ToDoCounter+'_text" style="width:90%; display:inline-block;">New ToDo Item</span></div></li>');
				} else
				if (NewItemAddMode=="InsertUnder")
				{
					$('ol.sortable').find("#"+LastHoverItem).find("ol:first").prepend('<li id="list_'+ToDoCounter+'"><div id="list_'+ToDoCounter+'_div" class="sortable-div"><input type="checkbox" id="list_'+ToDoCounter+'_check" /><span class="checkLabel" id="list_'+ToDoCounter+'_text" style="width:90%; display:inline-block;">New ToDo Item</span></div></li>');
				} else
				if (NewItemAddMode=="CreateUnder")
				{
					$('ol.sortable').find("#"+LastHoverItem).append('<ol><li id="list_'+ToDoCounter+'"><div id="list_'+ToDoCounter+'_div" class="sortable-div"><input type="checkbox" id="list_'+ToDoCounter+'_check" /><span class="checkLabel" id="list_'+ToDoCounter+'_text" style="width:90%; display:inline-block;">New ToDo Item</span></div></li></ol>');
				} else
				if (NewItemAddMode=="AddFirst")
				{
					$('ol.sortable').append('<li id="list_'+ToDoCounter+'"><div id="list_'+ToDoCounter+'_div" class="sortable-div"><input type="checkbox" id="list_'+ToDoCounter+'_check" /><span class="checkLabel" id="list_'+ToDoCounter+'_text" style="width:90%; display:inline-block;">New ToDo Item</span></div></li>');
				}
				
				initEdits();

				ListChanged = 1;
			} 
		}
	});

	$('#todolist1').nestedSortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		maxLevels: 10,
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div',
		update : sortChanged,
		activate : function (event,ui) {/*console.log("start");*/ MovingControl=true; },
		deactivate : function (event,ui) {/*console.log("stop");*/ MovingControl=false; }
	});

/*
	$('#trash').droppable({
		over : function(event, ui) { 
			$("#trash").css({"opacity":"1"}); 
			$(".placeholder").hide();
			$('#todolist1').nestedSortable({revert:0});
		},
		out  : function(event, ui) { 
			$("#trash").css({"opacity":"0.7"}); 
			$(".placeholder").show();
			$('#todolist1').nestedSortable({revert:250});
		},

		greedy: true,
		tolerance: 'pointer',
		accept: "#todolist1 li",

		drop : function ( event , ui ) 
				{
					DeleteItemX = $(ui.draggable).attr("id");
					//$("#"+DeleteItemX).fadeIn('slow', function(){  $("#"+DeleteItemX).fadeOut('slow', function(){ $("#"+DeleteItemX).remove(); ListChanged = 1; console.log("delete done"); DeleteItemX=""; }  ); } );
					
					$("#"+DeleteItemX).remove(); 
					ListChanged = 1; 
					
					//$(".placeholder").show();
					$('#todolist1').nestedSortable({revert:250});

					DeleteItemX="";
				}
	});
*/	
	$( "#controls-checkbox" ).draggable({
							helper: myHelper,
							revert: "invalid",
							start:handleDragStart,
							stop: handleDragStop,
							addClasses: false,
							zIndex: 100000
						});

	$( "#control_image,#control_datetime,#control_text,#control_fileupload,#control_button,#controls-survey_tools,#controls-slider" ).click( function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog-notyet" ).dialog({
			resizable: false,
			height:200,
			modal: true,
			buttons: {
				"Ok": function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});

	$( "#control_image,#control_datetime,#control_text,#control_fileupload,#control_button,#controls-survey_tools,#controls-slider" ).draggable( {
		revert: "invalid",
		addClasses: false,
		stop: function() { 
			$( "#dialog:ui-dialog" ).dialog( "destroy" );

			$( "#dialog-notyet" ).dialog({
				resizable: false,
				height:200,
				modal: true,
				buttons: {
					"Ok": function() {
						$( this ).dialog( "close" );
					}
				}
			});
		}
	});
	
	initEdits();
}

//------------------------------------------------------------------------------
function TrashThis(thisx)
{
	var DeleteObject = $(thisx).closest('li');
	if ($(thisx).parent().parent().attr("id")=="todo_list_title")
	{
		IsDeleteContorl = true;
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#dialog-list-confirm" ).dialog({
			resizable: false,
			height:200,
			modal: true,
			buttons: {
				"Yes": function() {
					$( this ).dialog( "close" );

					ListNameChanged=0;

					$("#user-todo-lists").load("mylists.php", {op:'del'}, function() {  $(".my-todo-lists-li").click( function() { clickList(this); } ); LoadToDoItems(0); IsDeleteContorl = false; }  );
				},
				"Cancel": function() {
					IsDeleteContorl = false;
					$( this ).dialog( "close" );
				}
			}
		});
	} else
	{
		IsDeleteContorl = true;
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:200,
			modal: true,
			buttons: {
				"Yes": function() {
					$( this ).dialog( "close" );
					$(DeleteObject).hide(
						{animation:'slideUp',duration:500,easing:"swing",queue: false , complete: function() { $(DeleteObject).remove(); ListChanged = 1; IsDeleteContorl = false; } } );
				},
				"Cancel": function() {
					IsDeleteContorl = false;
					$( this ).dialog( "close" );
				}
			}
		});
	}

//	alert("click "+$(thisx).parent().parent().attr("id"));
}

//------------------------------------------------------------------------------
function clickList(thisx)
{
	//console.debug(thisx); 
//	alert("click "+ $(thisx).data('listid') ); 

	//create new todo list
	if ( $(thisx).data('listid') == "0" )
	{
		//create new list
		$("#user-todo-lists").load("newlist.php", function() {  $(".my-todo-lists-li").click( function() { clickList(this); } )  }  );

		//check if current list needs saving and then load the new list by passing 0 as listID which will load the newest list created
		if (ListChanged==1) 
		{
			ListChanged=0;

			$("#savingX").show();
			newNestedXML = "<?xml version=\"1.0\" ?><todolist>\n";
			newXML = "<?xml version=\"1.0\" ?><todolist>\n";

			var parentId = 0;
			XLevel       = 0;
			XLine        = 0;
			XParent      = 0;

			processChildren( XLine, $("#todolist1") );
			newNestedXML += "</todolist>";
			newXML += "</todolist>";

			//console.log(newXML);

			$.ajax({ 
				url: "saveitems.php", 
				data: {ToDoTitle : $("#todo_list_title").text(), inputXML : newXML, test:"hello" },
				type: "POST", 
				success: function(msg) { 
						$("#savingX").hide(); 
						ListChanged=0; 
						if (ListNameChanged==1) { LoadUserToDoLists(); }

						$("#stage").load("loaditems.php", {ListID:0} , function() { initList(); } ); 
					},
				error : function (xhr, ajaxOptions, thrownError){ $("#savingX").html(xhr.status + " --- " + thrownError); } 
			});
		} else
		{
			$("#stage").load("loaditems.php", {ListID:0} , function() { initList(); } ); 
		}
	} else
	//load todo list
	{
		LoadToDoItems( $(thisx).data('listid') );
	}
}


//------------------------------------------------------------------------------
function LoadUserToDoLists()
{
	ListNameChanged=0;
	$("#user-todo-lists").load("mylists.php", function() {  $(".my-todo-lists-li").click( function() { clickList(this); } )  }  );
}


//------------------------------------------------------------------------------
function LoadToDoItems(ListIDX)
{
	//console.log("loading list: "+ListIDX);
	//check if current list needs saving
	if (ListChanged==1) 
	{
		ListChanged=0;

		$("#savingX").show();
		newNestedXML = "<?xml version=\"1.0\" ?><todolist>\n";
		newXML = "<?xml version=\"1.0\" ?><todolist>\n";

		var parentId = 0;
		XLevel       = 0;
		XLine        = 0;
		XParent      = 0;

		processChildren( XLine, $("#todolist1") );
		newNestedXML += "</todolist>";
		newXML += "</todolist>";

		//console.log(newXML);

		$.ajax({ 
			url: "saveitems.php", 
			data: {ToDoTitle : $("#todo_list_title").text(), inputXML : newXML, test:"hello" },
			type: "POST", 
			success: function(msg) { 
					$("#savingX").hide(); 
					ListChanged=0; 
					if (ListNameChanged==1) { LoadUserToDoLists(); }
					$("#stage").load("loaditems.php", {ListID:ListIDX} , function() { initList(); } ); 
				},
			error : function (xhr, ajaxOptions, thrownError){ $("#savingX").html(xhr.status + " --- " + thrownError); } 
		});
	} else
	{
		$("#stage").load("loaditems.php", {ListID:ListIDX} , function() { initList(); } ); 
	}
}


//------------------------------------------------------------------------------
function SaveToDoList()
{
	$("#savingX").show();
	newNestedXML = "<?xml version=\"1.0\" ?><todolist>\n";
	newXML = "<?xml version=\"1.0\" ?><todolist>\n";

	var parentId = 0;
	XLevel       = 0;
	XLine        = 0;
	XParent      = 0;

	processChildren( XLine, $("#todolist1") );
	newNestedXML += "</todolist>";
	newXML += "</todolist>";

	//console.log(newXML);

	$.ajax({ 
		url: "saveitems.php", 
		data: {ToDoTitle : $("#todo_list_title").text(), inputXML : newXML, test:"hello" },
		type: "POST", 
		success: function(msg) { $("#savingX").hide(); ListChanged=0; /*console.log("saved");*/  if (ListNameChanged==1) { LoadUserToDoLists(); } },
		error : function (xhr, ajaxOptions, thrownError){ $("#savingX").html(xhr.status + " --- " + thrownError); } 
	});

}

//------------------------------------------------------------------------------
function processChildren(YParent, parentId) 
{
	$(parentId).children("li").each(function () {
		XLine++;
		
		if ($(this).has("ol").length) { HasChildren = "1"; } else { HasChildren = "0"; }
		//console.log(XLine+" "+YParent+" "+XLevel+") "+$(this).attr("id")+" "+$( "#" + $(this).attr("id") + "_div" ).text() );

		CheckStatus = 0;
		if ( $("#"+ $(this).attr("id") + "_check" ).is(":checked") )
		{
			CheckStatus=1;
		}

		newNestedXML += "<todoitem><todoline><XLine>" + XLine + "</XLine><XParent>" + YParent + "</XParent><XDone>" + CheckStatus + "</XDone><XText>" + $("#"+ $(this).attr("id") + "_div" ).text() + "</XText><HasChildren>" + HasChildren + "</HasChildren></todoline>";
		newXML += "<todoline><XLine>" + XLine + "</XLine><XParent>" + YParent + "</XParent><XDone>" + CheckStatus + "</XDone><XText>" + $("#"+ $(this).attr("id") + "_div" ).text() + "</XText><HasChildren>" + HasChildren + "</HasChildren></todoline>\n";
			
		if ($(this).has("ol").length) {
			XLevel++;
			newNestedXML += "\n";
			processChildren(XLine,$(this).children("ol"));
			newNestedXML += "</todoitem>\n";
		} else 
		{
			newNestedXML += "</todoitem>\n";
		}
	});
	XLevel--;
} 


//------------------------------------------------------------------------------
function doCheckSaveList(){
	if ((ListChanged==1) && (!AddingControl) && (!MovingControl) && (!EditingContorl) && (!IsDeleteContorl) && (DeleteItemX==""))
	{
		SaveToDoList();
	}
	checkToSave();
}

//------------------------------------------------------------------------------
function checkToSave(){
	setTimeout("doCheckSaveList()", 10000);
}
