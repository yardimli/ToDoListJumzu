<?php require_once("server-settings.php"); ?>
<?php include("ortak-header.php"); ?>

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
						<span id="todo_list_title"><?php echo $signup_sign_in; ?>
							<div style="float:right; display: inline; color:#555"><?php echo $signin_dont_have_account; ?></div>
						</span>
						
					</div>
				</div>

<?php

$Email       = "";
$Password    = "";
$FormMessage = "";

$HasError = 0;
$PasswordError = 0;

if ($_POST["op"]=="signin_popup")
{
	$Email      = $_POST["SignIn_Email"];
	$Password   = $_POST["SignIn_Password"];
	$remember_me= $_POST["remember_me"];
}

if ($_POST["op"]=="signin")
{
	$Email      = $_POST["Email"];
	$Password   = $_POST["Password"];
	$remember_me= $_POST["remember_me"];
}

if ( ($_POST["op"]=="signin_popup") or ($_POST["op"]=="signin"))
{
	if ($Email=="") { $Email_Error = "<div class=form-error>". $signup_Email_Error . "</div>"; $HasError = 1; } else
 	if (verify_Email($Email)) {} else { $Email_Error = "<div class=form-error>". $signup_Email_Error2 . "</div>"; $HasError = 1; }


	if ($Password=="") { $Password_Error = "<div class=form-error>" . $signup_Password_Error ."</div>"; $HasError = 1; } 

	if ($HasError==0)
	{

		$Email_      = "'".AddSlashes(Trim($Email))."'";
		$Password_   = "'".AddSlashes(Trim(md5($Password.$randomword)))."'";

		$xsqlCommand = "SELECT * FROM users WHERE Email=".$Email_." AND Password=".$Password_;
//		echo $xsqlCommand;

		$mysqlresult = mysql_query($xsqlCommand);
		$num_rows = mysql_num_rows($mysqlresult);
		if ($num_rows==1)
		{
			if ($remember_me =="1")
			{
				$xsqlCommand2 = "UPDATE users SET userID_MD5='".md5(mysql_result($mysqlresult,0,"ID").$randomword)."' WHERE Email=".$Email_." AND Password=".$Password_;
				$mysqlresult2 = mysql_query($xsqlCommand2);

				setcookie("userID", md5(mysql_result($mysqlresult,0,"ID").$randomword), time()+(3600*24*31));
				setcookie("remember_me", "true", time()+(3600*24*31));
			}

			$_SESSION['jumzu_User'] = true;
			$_SESSION['jumzu_AccountVerified'] = mysql_result($mysqlresult,0,"AccountVerified");
			$_SESSION['jumzu_UserID'] = mysql_result($mysqlresult,0,"ID");
			$_SESSION['jumzu_UserName'] = mysql_result($mysqlresult,0,"Username");
			$_SESSION['jumzu_FullName'] = mysql_result($mysqlresult,0,"FirstName");
			$_SESSION['jumzu_Picture'] = mysql_result($mysqlresult,0,"Picture");
			$_SESSION['jumzu_Location']   = mysql_result($mysqlresult,0,"Location");
			$_SESSION['jumzu_Email']   = mysql_result($mysqlresult,0,"Email");
			$_SESSION['Facebook_uid']   = mysql_result($mysqlresult,0,"Facebook_uid");

			header( "Location: http://".$server_domain."/" ) ; //u/".$_SESSION['jumzu_UserName']
			exit();

		} else
		{
			$HasError = 1;
			$FormMessage = $signin_form_error;
		}
	} else
	{
			$FormMessage = $signin_form_generic;
	}
}

?>


<script type="text/javascript" charset="utf-8">
	$(function(){ $("#signup_form label").inFieldLabels(); });
</script>



<body>

<?php require_once('/fb-connect-code.php'); ?>

		<div class="content-main-3">

<?php if ($_GET["q"]=="session_end") {?>
<div class="successbox" style="align:center; margin:0 auto; margin-top:5px; margin-bottom:15px;"><center><?php echo $signin_session_expire; ?></center></div>
<?php } ?>

		        
<?php if ($FormMessage!="") {?>
<div class="errorbox" style="align:center; margin:0 auto; margin-top:5px; margin-bottom:15px;">
<?php echo $FormMessage; ?>
<div style="height:5px;"></div>
<span style="line-height:1.8em;">
<?php echo $Email_Error; ?>
<?php echo $Password_Error; ?>
</span>
</div>
<?php } ?>


<form id="signup_form" method=post  autocomplete = "off">
<input type="hidden" name="op" value="signin">
<div style="height:10px"></div>
<span>
  <label for="Email"><?php echo $signup_Email; ?></label>
  <input type="text" name="Email" value="<?php echo $Email; ?>" id="Email" class="signup-name" title="<?php echo $signup_Email_tip; ?>"  style="width:400px">
</span>
<div style="margin-top:5px; margin-bottom:15px;"></div>


<span>
  <label for="Password"><?php echo $signup_Password; ?></label>
  <input type="password" name="Password" value="" id="Password" class="signup-name" title="<?php echo $signup_Password_tip_2; ?>"  style="width:400px">
</span>
<div style="margin-top:5px; margin-bottom:15px;"></div>


<input id="remember_me" name="remember_me" value="1" type="checkbox">
<label for="remember_me"><?php echo $Common_header_remember_me; ?></label>

<div style="height:10px"></div>

<input type="submit" name="join_button" value="<?php echo $Common_header_home_signin_button; ?>" class="submitbutton" />
</form>

<br>
<a href="password-reset.php" id="resend_password_link"><?php echo $Common_header_forgot_pwd; ?></a>


<script type='text/javascript'>
	$(function() {	 $('#signup_form [title]').tipsy({trigger: 'focus', fade:true, gravity: 'w'});    });
</script>

<hr/> 
<div style="margin-bottom:10px"><?php echo $signin_facebook; ?></div>

  <a id="facebookButton-small" onclick="facebookSignin(); return false;" href="#"><font color=white>facebook</font></a>


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