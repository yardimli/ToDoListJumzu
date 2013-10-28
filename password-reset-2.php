<?php require_once("/server-settings.php"); ?>
<?php $page="password-reset-2"; ?>
<?php include("/mini-page-header.php"); ?>

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
<?php echo $Password_Reset_Email_Form_2; ?>

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