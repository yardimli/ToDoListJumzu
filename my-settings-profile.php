<?php require_once("server-settings.php"); ?>
<?php require_once("ortak-header.php"); ?>
<?php require_once("check-user-login.php"); ?>

<script type="text/javascript">
$(document).ready(function(){ 
	content_height = $('#content').height();
	if (content_height<700) {content_height = 700;}

	newHeight = content_height;
	$('#content').css('height',newHeight+"px" );

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

$(window).resize(function() {
	content_height = $('#content').height();
	if (content_height<700) {content_height = 700;}

	newHeight = content_height;
	$('#content').css('height',newHeight+"px" );

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
</script>

<style type="text/css">
.ui-autocomplete-loading { background: white url('css/images/ui-anim_basic_16x16.gif') right center no-repeat; }
</style>

	<div class="glow" style="display:none">
		<div class="glow-top">&nbsp;</div>
		<div id="glow-mid" class="glow-mid" style="height: 456px;">&nbsp;</div>
		<div class="glow-bottom">&nbsp;</div>
	</div>


	<div class="page ">
		<div id="main" class="main" >
			<div id="content" style="z-index:20; position:relative;">

				<div class="title-bar index-title-bg">
					<div>
						<span id="todo_list_title">
							<img src="slir/w24-h24-c1.1/http://<?php echo $server_domain.$_SESSION['jumzu_Picture']; ?>" height=24 style="vertical-align:top; "> <?php echo $_SESSION['jumzu_FullName'];?><?php echo $Settings_Title ?>						</span>
						
					</div>
				</div>


				<div style="padding:10px">

<?php


if ($_POST["op"]=="")
{
	$xsqlCommand = "SELECT * FROM users WHERE ID=".$_SESSION['jumzu_UserID'];
	$mysqlresult = mysql_query($xsqlCommand);
	$num_rows = mysql_num_rows($mysqlresult);
	if ($num_rows==1)
	{
		if (mysql_result($mysqlresult,0,"facebook_access_token")!="")
		{	

			if (mysql_result($mysqlresult,0,"Facebook_username")!="") {
				$userpic = get_redirect_url('https://graph.facebook.com/'.mysql_result($mysqlresult,0,"Facebook_username").'/picture?type=large');
			} else
			if (mysql_result($mysqlresult,0,"Facebook_uid")!="") {
				$userpic = get_redirect_url('https://graph.facebook.com/'.mysql_result($mysqlresult,0,"Facebook_uid").'/picture?type=large');
			}

			

			/*
			$cookie = get_facebook_cookie('146170835459093', '26338bd4a7850c2af95dd62a68ce5989');
			$user = json_decode(file_get_contents('https://graph.facebook.com/'. mysql_result($mysqlresult,0,"Facebook_uid") .'?access_token=' . mysql_result($mysqlresult,0,"facebook_access_token") ) );

			//print_r ($user);
			//$facebook_bio = $user->bio;

			$facebook_username = $user->username;
			$userpic = get_redirect_url('https://graph.facebook.com/'.$facebook_username.'/picture?type=normal');
			*/
		}
		

		$FullName   = mysql_result($mysqlresult,0,"FirstName");
		$Location   = mysql_result($mysqlresult,0,"Location");
		$Picture    = mysql_result($mysqlresult,0,"Picture");

//		if ($userpic!=$Picture) { $Picture = $userpic; }

	}

} else
{
	$xsqlCommand = "SELECT * FROM users WHERE ID=".$_SESSION['jumzu_UserID'];
	$mysqlresult = mysql_query($xsqlCommand);
	$num_rows = mysql_num_rows($mysqlresult);
	if ($num_rows==1)
	{
		if (mysql_result($mysqlresult,0,"facebook_access_token")!="")
		{
			$userpic = get_redirect_url('https://graph.facebook.com/'.mysql_result($mysqlresult,0,"Facebook_username").'/picture?type=normal');
		}
	}
}

$HasError = 0;
$NameError = "";


if ($_POST["op"]=="update_profile")
{
	$FullName   = $_POST["FullName"];
	$Location   = $_POST["Location"];
	$Picture    = $_POST["Picture"];

	if ($FullName=="") { $FullNameError = "<div class=form-error>". $Settings_Profile_Error_FullName. "</div>"; $HasError = 1; }
	if ($Location=="") { $LocationError = "<div class=form-error>". $Settings_Profile_Error_Location. "</div>"; $HasError = 1; }

	if ($HasError==1)
	{
		$Update_Error_Message = $signup_Error;
	}

	if ($HasError==0)
	{
		if ($Picture!=$_SESSION['jumzu_Picture'])
		{
			$Picture = SaveProfileLocal($Picture,$_SESSION['jumzu_UserID']);
		}

		//insert into db
		$FullName_   = "'".AddSlashes(Trim($FullName))."'";
		$Location_   = "'".AddSlashes(Trim($Location))."'";
		$Homepage_   = "'".AddSlashes(Trim($Homepage))."'";
		$Picture_    = "'".AddSlashes(Trim($Picture))."'";
		$UserBio_    = "'".AddSlashes(Trim($UserBio))."'";

		$xsqlCommand1 = "SELECT * FROM city JOIN Country ON city.CountryCode = Country.Code WHERE concat(City.Name,\", \",LocalName) = ".$Location_;
		$mysqlresult1 = mysql_query($xsqlCommand1);

		if (mysql_num_rows($mysqlresult1)>0) { //if location doesnt exist in database dont save it
			$xsqlCommand = "UPDATE users SET FirstName=".$FullName_ ." , Location=". $Location_ .", Picture = ". $Picture_ . " WHERE ID=".$_SESSION['jumzu_UserID'];
		} else {
			$xsqlCommand = "UPDATE users SET FirstName=".$FullName_ ." , Picture = ". $Picture_ . " WHERE ID=".$_SESSION['jumzu_UserID'];
		}
//		echo $xsqlCommand;
		$mysqlresult1 = mysql_query($xsqlCommand);

		$xsqlCommand = "UPDATE userprofiles SET Homepage=".$Homepage_ ." , UserBio=".$UserBio_ ." WHERE UserID=".$_SESSION['jumzu_UserID']; 
//		echo $xsqlCommand;
		$mysqlresult2 = mysql_query($xsqlCommand);


		if ($mysqlresult1 != "1") { $Update_Error_Message=$Settings_Account_db_Error. " (3)"; } else
		if ($mysqlresult2 != "1") { $Update_Error_Message=$Settings_Account_db_Error. " (4)"; } else
		{
			$_SESSION['jumzu_FullName']   = $FullName;
			$_SESSION['jumzu_Picture']    = $Picture;
			$_SESSION['jumzu_Location']   = $Location;

			$Update_Message=$Settings_Profile_Update_Msg;
		}
	}
}

?>

<script type="text/javascript">
		var swfu;
		window.onload = function () {
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "upload-profile-picture.php",
				post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},

				// File Upload Settings
				file_size_limit : "1 MB",	// 2MB
				file_types : "*.jpg;*.png",
				file_types_description : "<?php echo $Settings_Profile_Img_Types; ?>",
				file_upload_limit : "0",
				file_queue_limit : 1,

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "images/SmallSpyGlassWithTransperancy_17x18.png",
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 168,
				button_height: 18,
				button_text : '<span class="flash-button"><?php echo $Settings_Profile_Img_Select; ?></span>',
				button_text_style : '.flash-button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; width:180px; } .flash-buttonSmall { font-size: 10pt; }',
				button_text_top_padding: 0,
				button_text_left_padding: 18,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "swfupload/swfupload.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer"
				},
				
				// Debug Settings
				debug: true
			});
		};
	</script>

<script type="text/javascript" charset="utf-8">
	$(function(){ $("#signup_form label").inFieldLabels(); });
</script>

<script type="text/javascript"> 

  //<![CDATA[
  window.fbAsyncInit = function() {
    FB.init({ appId: jumzu_fbAppId, status: true, cookie: true, xfbml: true });
  };
 
  //]]>
</script>

<div class="content-main " >

<div class="content-section">

<?php if ($Update_Error_Message!="") {?>
<div class="errorbox" style="align:center; margin:0 auto; margin-top:5px; margin-bottom:25px;"><?php echo $Update_Error_Message; ?>
<?php echo $FormError; ?>
<?php echo $FullNameError; ?>
<?php echo $LocationError; ?>
</div>

<?php } else
if ($Update_Message!="") {?>
<div class="successbox" style="align:center; margin:0 auto; margin-top:5px; margin-bottom:25px;"><?php echo $Update_Message; ?></div>
<?php } else
if ($_GET["message"]=="welcome") { ?>
<div class="successbox" style="width:600px; align:center; margin:0 auto; margin-top:5px; margin-bottom:25px;">
<?php echo $Settings_Profile_Welcome; ?></div>
<?php }?>



<form id="signup_form" method=post action="my-settings-profile.php" autocomplete = "off">
<input type="hidden" name="op" value="update_profile">
<input name="Picture" type="hidden" class="form" id="Picture" value="<?php echo $Picture; ?>" size="240" />

<div style="float:right; margin-top:0px;">
<div id="image_file_preview" name="image_file_preview" style="margin-right:5px; "><?php if ($Picture!="") { echo "<img src=\"http://".$server_domain.$_SESSION['jumzu_Picture']."\" style=\"width:180px;  padding:1px;   border:0px solid #000000;   background-color:#dddddd; \">"; } ?></div>

<?php 
if ($_SESSION['Facebook_uid']!="") {
?>
<div style="margin-top:5px">
<input name="update-facebook-photo" type="button" class="submitbutton" style="width:182px; padding:2px" id="update-facebook-photo" value="<?php echo $Settings_Profile_Img_fb_Update; ?>" onClick="javascript:update_facebook_photo()" /> 
</div>
<?php
}
?>

<div style="margin-top:10px">
		<div style="display: inline; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 6px;">
			<span id="spanButtonPlaceholder"></span>
		</div>
	<div id="divFileProgressContainer" style="height: 45px;"></div>
</div>


</div>

<span>
<label for="FullName"><?php echo $Settings_Profile_Full_Name; ?></label>
  <input type="text" name="FullName" value="<?php echo $FullName; ?>" id="FullName" class="signup-name"  style="width:481px">
</span>
<div style="margin-top:5px; margin-bottom:15px;"><span style="font-size:90%"><?php echo $Settings_Profile_Full_Name_Tip; ?></span>

<div style="margin-top:5px; margin-bottom:15px;"><span style="font-size:90%"><?php echo $Settings_Profile_Your; ?> <a href="http://<?php echo $server_domain; ?>/u/<?php echo $_SESSION['jumzu_UserName']; ?>">http://<?php echo $server_domain; ?>/<span><?php echo $_SESSION['jumzu_UserName']; ?></span></a> <?php echo $Settings_Profile_Change; ?></span></div>
</div>
<div style="height:10px"></div>

<span>
  <label for="Location"><?php echo $Settings_Profile_Location; ?></label>
  <input type="text" name="Location" value="<?php echo $Location; ?>" id="Location" class="signup-name-nobackground" style="width:481px">
</span>
<div style="margin-top:5px; margin-bottom:15px;"><span style="font-size:90%"><?php echo $Settings_Profile_Location_Tip; ?></span></div>
<div style="height:10px"></div>

<script type="text/javascript">
$( "#Location" )
	.bind( "keydown", function( event ) {
					if ( (event.keyCode==27) ) {
						return false;
					}
					if ( (event.keyCode==13) ) {
						return false;
					}

	})
	.autocomplete({
	source: function( request, response ) {
//			console.log("begin search");
		$.ajax({
			url: "/search-places.php", 
			data: { term:escape(request.term) },
			dataType: "json",
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function( data ) {
				response( $.map( data, function( item ) {
					return {
						label: item.value,
						value: item.value
					}
				}));
			}
		});
	},

	minLength: 2,
	select: function( event, ui ) {
	  jQuery("#Location").val(ui.item.value);
	  //this.autocomplete('close');
	},
	open: function() {
		$(this).data('is_open',true);
		$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
	},
	close: function() {
		$(this).data('is_open',false);
		$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
	}
});
</script>

<br>

<script type='text/javascript'>
	function update_facebook_photo()
	{
		el=document.getElementById('image_file_preview');
		el.innerHTML='<img src=\'<?php echo $userpic; ?>\'  style=\'width:180px;  padding:1px;   border:0px solid #000000;   background-color:#dddddd; \'>';
		jQuery("input[name='Picture']").val('<?php echo $userpic; ?>');
	}
</script>


<div class="actions-div" id="save" style="padding-left: 10px;">

	<input type="submit" name="join_button" value="<?php echo $generic_save_changes; ?>" class="submitbutton" />

</div>
</form>



<script type='text/javascript'>
    $(function() {
	 $('.settingTip').tipsy({trigger: 'hover', html:true, fade:true, gravity: 's'});
	 $('#signup_form [title]').tipsy({trigger: 'focus', fade:true, gravity: 'w'});
    });
</script>


</div>


<!-- FOTTER -->
		
				<div class="clear"></div>
			</div>	
		</div>
		
	</div>
 </div>
<div id="lb"></div>


		
				</div>


				
			</div>
		</div>
	</div>

<?php include("fotter.php"); ?>