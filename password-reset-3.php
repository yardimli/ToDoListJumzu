<?php $page="signin"; ?>
<?php require_once("/server-settings.php"); ?>
<?php include("/mini-page-header.php"); ?>

<?php

$New_Password      = "";
$HasError = 0;

if (!isset($_POST["op"]))
{
	$xsqlCommand = "SELECT * FROM users WHERE PasswordReset='".AddSlashes(Trim($_GET["q"]))."'";
	$mysqlresult = mysql_query($xsqlCommand);
	$num_rows = mysql_num_rows($mysqlresult);
	if ($num_rows==1)
	{
		$q = $_GET["q"];
	} else
	{
			header( "Location: http://".$server_domain."/password-reset-error.php" ) ;
			exit();
	}
}

if ($_POST["op"]=="reset")
{
	$New_Password      = $_POST["New_Password"];
	$q                 = $_POST["q"];

	if ($New_Password=="") { $Password_Error = "<div class=form-error>". $Password_Reset_New_Password ."</div>"; $HasError = 1; } else
	if (!valid_pass($New_Password)) {
		$HasError = 1; 
		$Password_Error = "<div class=form-error>".$signup_Password_Error2."</div>";	
	} 

	if ($HasError==0)
	{
		$New_Password_      = "'".AddSlashes(Trim(md5($New_Password.$randomword)))."'";
		$q_                 = "'".AddSlashes(Trim($q))."'";

		$xsqlCommand = "SELECT * FROM users WHERE PasswordReset=".$q_;
		//echo $xsqlCommand;
		$mysqlresult = mysql_query($xsqlCommand);
		$num_rows = mysql_num_rows($mysqlresult);
		if ($num_rows==1)
		{
			$xsqlCommand2 = "UPDATE users set Password=". $New_Password_ .",PasswordReset='' WHERE PasswordReset=".$q_;
	//		echo $xsqlCommand2;
			$mysqlresult2 = mysql_query($xsqlCommand2);

			header( "Location: http://".$server_domain."/password-reset-complete.php" ) ;
			exit();

		} else
		{
			header( "Location: http://".$server_domain."/password-reset-error.php" ) ;
			exit();
		} 
	}
}

?>

<body>
<div id="narrow_page">
	<a href="/index.php"><img src="<?php
		if ($page_subdomain=="tr") { echo "/jumzu_screen_logo-nobg-tr.jpg"; } else
		if ($page_subdomain=="tw") { echo "/jumzu_screen_logo-nobg-tw.jpg"; } else
		if ($page_subdomain=="no") { echo "/jumzu_screen_logo-nobg-no.jpg"; } else
		if ($page_subdomain=="en") { echo "/jumzu_screen_logo-nobg-en.jpg"; } else
								   { echo "/jumzu_screen_logo-nobg-en.jpg"; } ?>" style="height:60px; width:351px; margin-top:8px; margin-left:0px; margin-bottom:5px; border:0px;"></a>

 
    <div class="slimdes">
		<div class="banner">
			<h2><?php echo $Password_Reset_Email_Form_Title; ?></h2>
		</div>
		<div class="content-main">

<br>
<?php echo $Password_Reset_New_Text; ?><br>
<form id="signup_form" method=post action="/password-reset-3.php" autocomplete = "off">
<input type="hidden" name="op" value="reset">
<input type="hidden" name="q" value="<?php echo $q; ?>">
<?php echo $Login_Error; ?>
<div style="height:10px"></div>
<span>
  <label for="New_Password"><?php echo $Password_Reset_New_Password_Hint; ?></label>
  <input type="password" name="New_Password" value="" id="New_Password" class="signup-name"  style="width:400px">
</span>
<div style="margin-top:5px; margin-bottom:15px;">
<?php echo $Password_Error; ?></div>
<div style="height:10px"></div>

<input type="submit" name="join_button" value="<?php echo $Submit_Button_Txt; ?>" class="submitbutton" />
</form>
<br>


<script type='text/javascript'>
$("#signup_form label").inFieldLabels();
$('#signup_form [title]').tipsy({trigger: 'focus', fade:true, gravity: 'w'});
</script>


<!-- FOTTER -->
		
			</div>	
		</div>
		
	</div>
 </div>
<div id="lb"></div>

</body>
</html>