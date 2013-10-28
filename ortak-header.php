<?php require_once("server-settings.php"); ?>
<?php require_once('storicles-translation.php'); ?>
<?php require_once('php-functions.php'); ?>
<?php

if (($_SESSION['jumzu_UserID']=="") or ($_SESSION['jumzu_UserID']=="0")) { $jumzu_UserID = "1";	} else { $jumzu_UserID = $_SESSION['jumzu_UserID']; }	

$xsqlCommand3 = "SELECT * FROM userprofiles WHERE UserID=".$jumzu_UserID;

$mysqlresult3 = mysql_query($xsqlCommand3);

$_SESSION['tileBackground']   = mysql_result($mysqlresult3,0,"tileBackground");
$_SESSION['backgroundImage']  = mysql_result($mysqlresult3,0,"backgroundImage");
$_SESSION['backgroundColor']  = mysql_result($mysqlresult3,0,"backgroundColor");

if (($_COOKIE["remember_me"]=="true") && ($_COOKIE["userID"]!="") && ($_SESSION['jumzu_User']!=true))
{
		$xsqlCommand = "SELECT * FROM users WHERE UserID_MD5='" . AddSlashes(Trim($_COOKIE["userID"])) . "'";
//		echo $xsqlCommand;

		$mysqlresult = mysql_query($xsqlCommand);
		$num_rows = mysql_num_rows($mysqlresult);
		if ($num_rows==1)
		{
			$_SESSION['jumzu_User'] = true;
			$_SESSION['jumzu_UserID'] = mysql_result($mysqlresult,0,"ID");
			$_SESSION['jumzu_UserName'] = mysql_result($mysqlresult,0,"Username");
			$_SESSION['jumzu_FullName'] = mysql_result($mysqlresult,0,"FirstName");
			$_SESSION['jumzu_Picture'] = mysql_result($mysqlresult,0,"Picture");
			$_SESSION['jumzu_Location']   = mysql_result($mysqlresult,0,"Location");
			$_SESSION['jumzu_Email']   = mysql_result($mysqlresult,0,"Email");

			header( "Location: http://".$server_domain."/settings/profile" ) ;
			exit();
		} else
		{ //something went wrong delete cookie from host
			setcookie("userID", "", time()-(3600*24));
			setcookie("remember_me", "", time()-(3600*24));
		}
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="en-us">
<meta name="title" content="Jumzu - ToDo">
<meta name="description" content="Jumzu is a new mico-blogging platform based on 60-second voice clips. Record or upload and share your voice or audio files easily. You can also share your Jumzus, on twitter and facebook.">
<meta name="keywords" content="Jumzu, voice tweet, micro-blog, voice micro-blog, voice community, voice city, talk to share,sesli mesaj, ses, kayÄ±t, mikrofon, twitter">
<meta name="robots" content="all">
<meta name="copyright" content="Copyright (c) 2011 Elo Robot Ltd.">
<meta property="og:title" content="Jumzu - Cloud of Voice"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://jumzu.com/"/>
<meta property="og:image" content="http://jumzu.com/Jumzu-logo-75.png"/>
<meta property="og:site_name" content="Jumzu"/>
<meta property="fb:admins" content="550737484"/>
<meta property="fb:app_id" content="146170835459093"/>
<meta property="og:description" content=""/>

<link rel="SHORTCUT ICON" href="http://<?php echo $server_domain; ?>/favicon.ico"> 


<title>Jumzu - Todo list expert</title>
	

<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>

<script type="text/javascript" src="js/js-functions.js"></script>
<script type="text/javascript" src="js/jquery.mjs.nestedSortable.js"></script>
<script type="text/javascript" src="js/jquery.checkbox.min.js"></script>
<script type="text/javascript" src="js/jquery.jeditable.js"></script>


<link type="text/css" href="stiller.css" rel="stylesheet" />

<link type="text/css" href="custom.css" rel="stylesheet" />
<link type="text/css" href="toolbarbuttons.css" rel="stylesheet" />

<link type="text/css" href="jquery.safari-checkbox.css" rel="stylesheet" />

<meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

<script src="js/jquery.infieldlabel.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.tipsy.js" type="text/javascript"></script>

<script src="js/jquery.ps-color-picker.js" type="text/javascript"></script>

<link href="css/swfupload-default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/swfupload.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>

<script src="js-functions.js" type="text/javascript"></script>

<link href="css/colorbox.css" media="screen" rel="stylesheet" />
<script src="js/jquery.colorbox.js"></script>

</head>

<body>


<script type="text/javascript">
$(document).ready(function(){ 
	<?php
	$mysqlresult = mysql_query("SELECT * FROM sitethemes ORDER BY ID ASC");
	$num_rows = mysql_num_rows($mysqlresult);
	$i = 0;
	WHILE ($i<$num_rows) {
		if (mysql_result($mysqlresult,$i,"ThemeName")=="Dark Green")
		{
		?>
			$('body').css({"background":"url(<?php echo mysql_result($mysqlresult,$i,"bgImage"); ?>) <?php mysql_result($mysqlresult,$i,"bgRepeat"); ?> <?php echo mysql_result($mysqlresult,$i,"BgColor"); ?>"});
		<?php
		}
		?>
		$("#theme<?php echo mysql_result($mysqlresult,$i,"ID"); ?>").click( function(){ 
			$('body').css({"background":"url(<?php echo mysql_result($mysqlresult,$i,"bgImage"); ?>) <?php mysql_result($mysqlresult,$i,"bgRepeat"); ?> <?php echo mysql_result($mysqlresult,$i,"BgColor"); ?>"});
			return false;
		});
	<?php
		$i++;
	}
	?>
});

</script>
	
	
<?php require_once('fb-connect-code.php'); ?>











<div style="width:100%;height:43px;background-image:url(cloudofvoice_screen_bg.png);background-repeat:repeat-x;z-index:100; top:0px; border:0px; solid #ccc; margin-bottom:8px; position: fixed; top:0; " id="navigator-bar">


	<a href="index.php" style="display:inline; width:200px; height:0px;"><img src="jumzu-logo-1.png" style="height:40px; margin-top:0px; border:0px;"></a>


		
		
		

	<?php if ($_SESSION['jumzu_User']==true) { 
			?>


	<img id="dialog_link" align="top" src="images/block.png" style="margin-top:12px; margin-right:10px; float:right"/>
	<img src="slir/w32-h32-c1.1/http://<?php echo $server_domain.$_SESSION['jumzu_Picture']; ?>" height=32 style="padding-right:25px; padding-left:5px; margin-top:3px; float:right"/>

	<span style="margin-top:7px; float:right; margin-right:14px; ">
	<div id="user_menu"><a href="signin.php" class="signin"><span><?php echo $_SESSION['jumzu_UserName']; ?> settings</span></a></div>
	</span>
			
	

	<div id="signin_menu_container" style="position: absolute; width: 200px; height: 400px; z-index: 5000; overflow: hidden;">
		<div id="signin_menu">
			<a class="user_menu_item_style" href="my-settings-account.php"><?php echo $Settings_Acconut; ?></a>
			<a class="user_menu_item_style" href="my-settings-profile.php"><?php echo $Settings_Profile; ?></a>
			<a class="user_menu_item_style" href="my-settings-password.php"><?php echo $Settings_Password; ?></a>
			<a class="user_menu_item_style" href="help.php"><?php echo $Common_header_help; ?></a>
			<a class="user_menu_item_style" href="signout.php"><?php echo $Common_header_signout; ?></a>
		</div>
	</div>

<script type="text/javascript">
        $(document).ready(function() {

			$("#signin_menu_container").css({"top":$("#user_menu").position().top+21 });			
			$("#signin_menu_container").css({"left":$("#user_menu").position().left-$("#signin_menu_container").width()+$("#user_menu").width()+6 });

			$ht = $('#signin_menu').height();

			$("#signin_menu_container").css({"height":$('#signin_menu').height()+30 });
			$('#signin_menu').hide();
			

            $("#user_menu").click(function(e) {          
				e.preventDefault();
				if (!($("#user_menu").hasClass('menu-open')))
				{
					$("#user_menu").toggleClass("menu-open");
					$("#signin_menu").slideUp(300, function() {  });
				} else
				{
					$("#signin_menu").slideDown(300, function() { $("#user_menu").removeClass("menu-open"); });
				}
            });
			
			$(document).mouseup(function(e) {
				if ( ($(e.target).parent("#user_menu").length==0) || ($("#user_menu").hasClass('menu-open')) ) {
					e.preventDefault();
					


				}
			});			
			
        });
</script>
			


			<?php
	}
if ($_SESSION['jumzu_User']!=true)
{ ?>
				
	<img id="dialog_link" align="top" src="images/block.png" style="margin-top:12px; margin-right:10px; float:right">
	<span style="margin-top:7px; float:right; margin-right:14px; ">
			<a href="signup-facebook.php?facebook=no" style="margin-right:0px; margin-left:0px; margin-top:0px; display:inline-block; color:black"><?php echo $Common_header_joinnow; ?></a>
			&nbsp;&nbsp;<span style="color:black;"><?php echo $Common_header_or; ?></span><div id="topnav-signin" class="topnav-signin"><a href="signin.php" class="signin"><span><?php echo $Common_header_login_text; ?></span></a> </div>
	</span>
	

  <div id="signin_menu_container" style="position: absolute; width: 200px; height: 400px; z-index: 5000; overflow: hidden;">
  <div id="signin_menu">
   <a id="facebookButton-small" onclick="facebookSignin(); return false;" href="#"><font color=white><?php echo $Common_header_fb_login; ?></font></a>

    <form method="post" id="signin" action="signin.php">
	<input type="hidden" name="op" value="signin_popup">
      <label for="SignIn_Email"><?php echo $Common_header_email; ?></label>
      <input id="SignIn_Email" name="SignIn_Email" value="" title="<?php echo $Common_header_email_tip; ?>" tabindex="4" type="text">
      </p>
      <p>
        <label for="SignIn_Password"><?php echo $Common_header_password; ?></label>
        <input id="SignIn_Password" name="SignIn_Password" value="" title="<?php echo $Common_header_password_tip; ?>" tabindex="5" type="Password">
      </p>
      <p class="remember">
        <input id="signin_submit" value="<?php echo $Common_header_home_signin_button; ?>" tabindex="6" type="submit">
        <input id="remember_me" name="remember_me" value="1" tabindex="7" type="checkbox">
        <label for="remember_me"><?php echo $Common_header_remember_me; ?></label>
      </p>
      <p class="forgot"> <a href="password-reset.php" id="resend_password_link"><?php echo $Common_header_forgot_pwd; ?></a> </p>
    </form>
  </div>
  </div>

<script type="text/javascript">
        $(document).ready(function() {

			$("#signin_menu_container").css({"top":$("#topnav-signin").position().top+21 });
			$("#signin_menu_container").css({"left":$("#topnav-signin").position().left-$("#signin_menu_container").width()+$("#topnav-signin").width()+6 });

			$ht = $('#signin_menu').height();

			$("#signin_menu_container").css({"height":$('#signin_menu').height()+30 });

			$('#signin_menu').css({
				'top':'-'+$ht+'px',
				'opacity': 0,
				'display': 'block'
			});


            $(".signin").click(function(e) {          
				e.preventDefault();
				if (!($(".signin").hasClass('menu-open')))
				{
					$(".signin").toggleClass("menu-open");
					$("#signin_menu").animate({
										top: '0',
										opacity: 1
										},
										300,  // slow the opening of the drawer
										function(){  }
									);
				}
            });
			
			$("#signin_menu").mouseup(function() {	return false  });

			$(document).mouseup(function(e) {
				if ( ($(e.target).parent("a.signin").length==0) || ($(".signin").hasClass('menu-open')) ) {
					e.preventDefault();
					
					$("#signin_menu").animate({
						top: '-'+$ht+'px',
						opacity: 0
						},
						300, function() { $(".signin").removeClass("menu-open"); }
		            );

				}
			});			
			
        });
</script>

<?php	}	?>

</div>
</div>

<div style="height:50px"></div>

<?php if (($_SESSION['jumzu_User']==true) and ($_SESSION['jumzu_AccountVerified']=="0") and ($page=="my-storicles") and ($_GET["message"]!="welcome") ) { ?>
<div class="successbox" style="width:800px; align:center; margin:0 auto; margin-top:0px; margin-bottom:10px; padding:10px; "><center><?php echo $Common_header_verify_email; ?></center></div>
<?php } ?>