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
						<span id="todo_list_title"><?php echo $Password_Reset_Email_Form_Title; ?></span>
					</div>
				</div>


				<div style="padding:10px">
<?php

$Email      = "";

$HasError = 0;

if ($_POST["op"]=="reset") {
	$Email      = $_POST["Email"];
}


if ($_POST["op"]=="reset") {
	if ($Email=="") { $Email_Error = $signup_Email_Error; $HasError = 1; } else
 	if (verify_Email($Email)) {} else { $Email_Error = $signup_Email_Error2; $HasError = 1; }


	if ($HasError==0) {
		$Email_      = "'".AddSlashes(Trim($Email))."'";

		$xsqlCommand = "SELECT * FROM users WHERE Email=".$Email_;
//		echo $xsqlCommand;

		$mysqlresult = mysql_query($xsqlCommand);
		$num_rows = mysql_num_rows($mysqlresult);
		if ($num_rows==1)
		{
			$PasswordReset = md5(mysql_result($mysqlresult,0,"ID").time().$randomword);
			$xsqlCommand2 = "UPDATE users set PasswordReset='". $PasswordReset ."' WHERE ID=".mysql_result($mysqlresult,0,"ID");
	//		echo $xsqlCommand2;
			$mysqlresult2 = mysql_query($xsqlCommand2);

//			$pop = new POP3();
//			$pop->Authorise('posta.izlenim.com', 110, 30, 'reset@storicles.com', 'A123456b', 1);

			$mail = new PHPMailer();
			
			$body             = file_get_contents('password-reset-en.html');
			$TheSubject = "Jumzu Password Reset Request";

			//$body             = preg_replace("/[\]/i","",$body);
			$body             = str_replace("***URL1***","http://".$server_domain."/password-reset-3.php?q=".$PasswordReset,$body);
			$body             = str_replace("***URL2***","http://".$server_domain."/password-reset-3.php?q=".$PasswordReset,$body);
//			echo $body;
//			exit();

			$mail->IsSMTP();
			$mail->SMTPDebug = 1;
			$mail->Host     = 'posta.izlenim.com';
//			$mail->Host     = 'p3smtpout.secureserver.net'; //posta.izlenim.com
	
			$mail->SetFrom('reset@jumzu.com', 'Jumzu To-Do');
			$mail->AddReplyTo("do-not-reply@jumzu.com","Jumzu To-Do");
			$mail->Subject    = '=?UTF-8?B?'.base64_encode($TheSubject)."?=";
			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

			$mail->MsgHTML($body);

			$address = mysql_result($mysqlresult,0,"Email");
			$mail->AddAddress($address, mysql_result($mysqlresult,0,"FirstName"));

			//$mail->AddAttachment("images/phpmailer.gif");      // attachment
			//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment


			if(!$mail->Send()) 
			{
				$HasError = 1;
				$Email_Error=$Password_Reset_Email_Form_Error1 . $mail->ErrorInfo .$Password_Reset_Email_Form_Try_Again;
			} else 
			{
				header( "Location: http://".$server_domain."/password-reset-2.php" ) ;
				exit();
			}

			//send email

		} else
		{
			$HasError = 1;
			$Email_Error=$Password_Reset_Email_Form_Error2;
		}
	}
}

?>

<body>
		<div class="content-main-3">


<?php if ($Email_Error!="") {?>
<div class="errorbox" style="margin-top:5px; margin-bottom:25px;"><?php echo $Email_Error; ?></div>
<?php }?>

<?php echo $Password_Reset_Email_Form_Email_Txt; ?><br>
<form id="signup_form" method=post action="password-reset.php" autocomplete = "off">
<input type="hidden" name="op" value="reset">
<div style="height:10px"></div>
<span>
  <label for="Email"><?php echo $Common_header_email; ?></label>
  <input type="text" name="Email" value="<?php echo $Email; ?>" id="Email" class="signup-name" title="<?php echo $Password_Reset_Email_Form_Tip; ?>"  style="width:400px">
</span>
<div style="margin-top:5px; margin-bottom:15px;"></div>
<div style="height:10px"></div>

<input type="submit" name="join_button" value="<?php echo $Submit_Button_Txt; ?>" class="submitbutton" />
</form>
<br>


<br><br>

<script type='text/javascript'>
$('#signup_form [title]').tipsy({trigger: 'focus', fade:true, gravity: 'w'});
$("#signup_form label").inFieldLabels();
</script>


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