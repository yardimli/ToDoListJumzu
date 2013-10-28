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
}

$HasError = 0;
$PasswordOld_Error = "";
$PasswordNew1_Error = "";
$PasswordNew2_Error = "";

if ($_POST["op"]=="update_password")
{
	$Password_Old  = $_POST["Password_Old"];
	$Password_New1 = $_POST["Password_New1"];
	$Password_New2 = $_POST["Password_New2"];

	if ($Password_Old=="") { $PasswordOld_Error = "<div class=form-error>".$Settings_Password_Error1."</div>"; $HasError = 1; } else
	{
		$Password2   = "'".AddSlashes(Trim(md5($Password_Old.$randomword)))."'";

		$xsqlCommand = "SELECT * FROM users WHERE ID=".$_SESSION['jumzu_UserID']." AND Password=".$Password2;
//		echo $xsqlCommand;

		$mysqlresult = mysql_query($xsqlCommand);
		$num_rows = mysql_num_rows($mysqlresult);
		if ($num_rows==0)
		{
			$PasswordOld_Error = "<div class=form-error>".$Settings_Password_Error1."</div>"; $HasError = 1;
		}
	}

	if ($Password_New1=="") { $PasswordNew1_Error = "<div class=form-error>".$Settings_Account_NewPassword_Error1."</div>"; $HasError = 1; } else
	if (!valid_pass($Password_New1)) {
	  $HasError = 1; 
	  $PasswordNew1_Error = "<div class=form-error>". $signup_Password_Error2 ."</div>";	
	} 

	if ($Password_New2=="") { $PasswordNew2_Error = "<div class=form-error>".$Settings_Account_NewPassword_Error2."</div>"; $HasError = 1; } else
	if ($Password_New1!=$Password_New2) { $PasswordNew2_Error = "<div class=form-error>".$Settings_Account_NewPassword_Error3."</div>"; $HasError = 1; }

	if ($HasError==1)
	{
		$Update_Error_Message = $signup_Error;
	}

	if ($HasError==0)
	{
		$NewPassword   = "'".AddSlashes(Trim(md5($Password_New1.$randomword)))."'";
		$xsqlCommand = "UPDATE users SET password =".$NewPassword." WHERE ID=".$_SESSION['jumzu_UserID'];
		//echo $xsqlCommand;
		$mysqlresult1 = mysql_query($xsqlCommand);
		if ($mysqlresult1 != "1") { $Update_Error_Message=$Settings_Account_db_Error." (1)"; } else
		{
			$Update_Message=$Settings_Account_Update_Message;
		}
	}
}

?>




<script type="text/javascript" charset="utf-8">
	$(function(){ $("#signup_form label").inFieldLabels(); });
</script>

<div class="content-main " >

<div class="content-section">

<?php if ($Update_Error_Message!="") {?>
<div class="errorbox" style="align:center; margin:0 auto; margin-top:5px; margin-bottom:25px;"><?php echo $Update_Error_Message; ?>

<?php echo $PasswordOld_Error; ?>
<?php echo $PasswordNew1_Error; ?>
<?php echo $PasswordNew2_Error; ?>

</div>
<?php } else
if ($Update_Message!="") {?>
<div class="successbox" style="align:center; margin:0 auto; margin-top:5px; margin-bottom:25px;"><?php echo $Update_Message; ?></div>
<?php } ?>


<form id="signup_form" method=post action="my-settings-password.php"  autocomplete = "off">
<input type="hidden" name="op" value="update_password">

<?php echo $FormError; ?>

<div style="height:10px"></div>
<span>
  <label for="Password_Old"><?php echo $Settings_Password_Current; ?></label>
  <input type="password" name="Password_Old" value="" id="Password_Old" class="signup-name" style="width:400px">
</span>
<br>
<div style="margin-top:5px; margin-bottom:15px;"><span style="font-size:90%"><!--Please type your current password.--></span></div>
<div style="height:10px"></div>

<span>
  <label for="Password_New1"><?php echo $Settings_Password_New; ?></label>
  <input type="password" name="Password_New1" id="Password_New1" value="" class="signup-name" title="<?php echo $signup_Password_Error2; ?>"  style="width:400px">
</span>
<br>
<div style="margin-top:5px; margin-bottom:15px;"><span style="font-size:90%"><!--Please type your new password.--></span></div>
<div style="height:10px"></div>

<span>
  <label for="Password_New2"><?php echo $Settings_Password_Retype; ?></label>
  <input type="password" name="Password_New2" id="Password_New2" class="signup-name" style="width:400px">
</span>
<br>
<div style="margin-top:5px; margin-bottom:15px;"><span style="font-size:90%"><!--Please re-type your new password.--></span></div>
<div style="height:10px"></div>

<br><br><br><br><br><br>

<div class="actions-div" id="save" style="padding-left: 10px;">
	<input type="submit" name="join_button" value="<?php echo $Settings_Account_Update_Password; ?>" class="submitbutton" />
</div>
</form>

<script type='text/javascript'>

    $(function() {
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

