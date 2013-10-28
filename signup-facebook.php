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
						<span id="todo_list_title"><?php echo $signup_header; ?>
							<div style="float:right; display: inline; color:#555"><?php echo $signup_already_have_an_account; ?> <a href="signin.php"><?php echo $signup_sign_in; ?></a></div>
						</span>
						
					</div>
				</div>


				<div style="padding:10px">
<?php

//SET USERNAME
$Turkish_letters = array("ü","Ü","ç","Ç","ğ","Ğ","ö","Ö","ş","Ş","ı","İ","'");
$Turkish_letters2= array("u","U","c","C","g","G","o","O","s","S","i","I","");

if ($_GET["facebook"]=="no")
{
} else
{
	//$cookie = get_facebook_cookie('367933513261330', '546302f61e8b07abdf3584909f4d533c');
	$access_token= $cookie['access_token'];

	if (($access_token=="") && ($_GET["code"]!="")) {
		$token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=261798160586392&redirect_uri=" . urlencode("http://".$server_domain."/signup-facebook.php")
       . "&client_secret=f71600d1dddb2ec8b454a6af1c443be2&code=" . $_GET["code"];

		$response = file_get_contents($token_url);
		$params = null;
		parse_str($response, $params);
		setcookie("access_token",$params['access_token'],time()+60*60*24*30,"/",".jumzu.com");
		$access_token= $params['access_token'];
	}

	if ($access_token!="") 
	{
		$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $access_token));
		$facebook_username = $user->username;
		$facebook_uid = $user->id;
		if ($facebook_uid=="") { $facebook_uid = "0000000000"; }

		if ($facebook_username!="") {
			$userpic = get_redirect_url('https://graph.facebook.com/'.$facebook_username.'/picture?type=large');
		} else
		{
			$userpic = get_redirect_url('https://graph.facebook.com/'.$facebook_uid.'/picture?type=large');
		}

		//search mysql if user is already a member if so log them in and take them to the profile page

		$sqlcommand = "SELECT * FROM users WHERE (facebook_uid='" . AddSlashes(Trim($facebook_uid)) . "')";

		$mysqlresult = mysql_query($sqlcommand);
		$num_rows = mysql_num_rows($mysqlresult);

		if ($num_rows>0) 
		{ 
			header( "Location: http://".$server_domain."/signin-facebook.php" ) ;
			exit();
		}
	} else
	{
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=261798160586392&redirect_uri=" . urlencode("http://".$server_domain."/signup-facebook.php") . "&state=". $_SESSION['state'];

//		echo("<script> top.location.href='" . $dialog_url . "'</script>");
//		echo $dialog_url;
		header( "Location: ".$dialog_url ) ;
		exit();
	}
}

$FirstName  = "";
$MiddleName = "";
$LastName   = "";
$Email      = "";
$Password   = "";
$Location   = "";
$Gender     = "1";

$FormMessage ="";

$Picture    = "placeholder-girl.jpg";
if ($facebook_uid!="")
{
	$facebook_uid = $user->id;
	if ($user->middle_name!="")
	{
		$FirstName  = trim($user->first_name." ".$user->middle_name." ".$user->last_name);
	} else
	{
		$FirstName  = trim($user->first_name." ".$user->last_name);
	}
	$MiddleName = $user->middle_name;
	$LastName   = $user->last_name;
	$UserName   = $user->username;
	$Email      = $user->email;
	$UserBio    = $user->bio;
	$Homepage   = $user->website;
	$Password   = "";
	$Location   = $user->location->name;
	$Gender     = "0";
	if ($user->gender=="female") { $Gender     = "1"; }
	if ($user->gender=="male") { $Gender     = "2"; }
	
	$Picture    = $userpic;

	$Username = $facebook_username;
	$Username = str_replace($Turkish_letters,$Turkish_letters2,$Username);
	$Username = strtolower($Username);
	if (strlen($Username)>20) $Username = substr($Username,0,20);
}

$HasError = 0;
$PasswordError = 0;

if ($_POST["op"]=="save")
{
	$FirstName  = $_POST["FirstName"];
	$LastName   = $_POST["LastName"];
	$MiddleName = $_POST["MiddleName"];
	$Email      = $_POST["Email"];
	$Password   = $_POST["Password"];
	$Location   = $_POST["Location"];
	$Gender     = $_POST["Gender"];
	$Picture    = $_POST["Picture"];

	$Username   = $_POST["Username"];

	$Username = str_replace($Turkish_letters,$Turkish_letters2,$Username);


	if (($FirstName=="") or (strlen($FirstName)<2)) { $FirstName_Error = "<div class=form-error>".$signup_FirstName_Error."</div>"; $HasError = 1; }

	if ($Email=="") { $Email_Error = "<div class=form-error>".$signup_Email_Error."</div>"; $HasError = 1; } else
 	if (verify_Email($Email)) {} else { $Email_Error = "<div class=form-error>".$signup_Email_Error2."</div>"; $HasError = 1; }

	$xsqlCommand = "SELECT * FROM users WHERE Email = '".AddSlashes(Trim($Email))."'";
//		echo $xsqlCommand;
	$mysqlresult = mysql_query($xsqlCommand);
	$num_rows = mysql_num_rows($mysqlresult);
	if ($num_rows>0) { 
		$Email_Error = "<div class=form-error>".$signup_Email_Error3."</div>"; 
		$HasError = 1; 
	}


	if ($Password=="") { $Password_Error = "<div class=form-error>".$signup_Password_Error."</div>"; $HasError = 1; $PasswordError = 1; } else
	if (!valid_pass($Password)) {
		$HasError = 1;
		$PasswordError = 1;
		  $Password_Error = "<div class=form-error>".$signup_Password_Error2."</div>";	
	}

//	if ($Location=="") { $Location_Error = "<div class=form-error>".$signup_Location_Error."</div>"; $HasError = 1; }

	if ($HasError==0)
	{
		//insert into db
		$FirstName_  = "'".AddSlashes(Trim($FirstName))."'";
		$LastName_   = "'".AddSlashes(Trim($LastName))."'";
		$MiddleName_ = "'".AddSlashes(Trim($MiddleName))."'";
		$Email_      = "'".AddSlashes(Trim($Email))."'";
		$Password_   = "'".AddSlashes(Trim(md5($Password.$randomword)))."'";
		$Location_   = "'".AddSlashes(Trim($Location))."'";
		$Gender_     = "'".AddSlashes(Trim($Gender))."'";
		$Picture_    = "'".AddSlashes(Trim($Picture))."'";

		$xsqlCommand = "INSERT INTO users (FirstName,LastName,Email,Password,Location,Gender,Picture,SignupDate,SignupIP,facebook_uid,facebook_username,facebook_access_token,MiddleName,UserName) VALUES (".$FirstName_.",".$LastName_.",".$Email_.",".$Password_.",".$Location_.",".$Gender_.",".$Picture_.",now(),'".getUserIpAddr()."','".AddSlashes(Trim($facebook_uid))."','".AddSlashes(Trim($facebook_username))."','".AddSlashes(Trim($access_token))."',".$MiddleName_.",'".AddSlashes(Trim($Username))."')";

		echo $xsqlCommand;

		$mysqlresult = mysql_query($xsqlCommand);

		$xsqlCommand = "SELECT * FROM users WHERE Email=".$Email_;
		$mysqlresult = mysql_query($xsqlCommand);
		$num_rows = mysql_num_rows($mysqlresult);
		if ($num_rows==1)
		{

			$Picture = SaveProfileLocal($Picture,mysql_result($mysqlresult,0,"ID"));

			$xsqlCommand1 = "INSERT INTO userprofiles (UserID,homepage,UserBio,backgroundImage,backgroundColor,textColor,linkColor,headerColor,TextBackgroundColor,tileBackground) VALUES (".mysql_result($mysqlresult,0,"ID") . ",'" . AddSlashes(Trim($Homepage)) ."','" . AddSlashes(Trim($UserBio)) ."','/bg/theme_default_modify.jpg','#DDF3FC','#272323','#333333','#000000','#90D4EA',0)";
			$mysqlresult1 = mysql_query($xsqlCommand1);

			$xsqlCommand1 = "INSERT INTO usersettings (UserID,LanguageID,TimezoneID) VALUES (".mysql_result($mysqlresult,0,"ID") .",1,67)";
			$mysqlresult1 = mysql_query($xsqlCommand1);

			$xsqlCommand1 = "INSERT INTO usernotifications (UserID) VALUES (".mysql_result($mysqlresult,0,"ID") .")";
			$mysqlresult1 = mysql_query($xsqlCommand1);
			
			//insert new list
			$query = mysql_query('INSERT INTO todolists (userID,listName,listVersion,CreateDate) VALUES ('.mysql_result($mysqlresult,0,"ID").',"New List",1,now())');

			//select new list 
			$query = mysql_query('SELECT * FROM todolists WHERE userID='.mysql_result($mysqlresult,0,"ID").' ORDER BY ID DESC LIMIT 1');

			$ListID = mysql_result($query,0,"ID");

			//insert dummy items into new list

			$query = mysql_query('INSERT INTO todoitems (userID,parentID,OrderNumber,ItemID,hasChildren,ListID,ListVersion,ToDoText,CreateDate) VALUES ('.mysql_result($mysqlresult,0,"ID").',0,1,1,0,'.$ListID.',1,"New Todo List",now())');
			$query = mysql_query('INSERT INTO todoitems (userID,parentID,OrderNumber,ItemID,hasChildren,ListID,ListVersion,ToDoText,CreateDate) VALUES ('.mysql_result($mysqlresult,0,"ID").',1,2,2,0,'.$ListID.',1,"New Todo Child Element",now())');
			$query = mysql_query('INSERT INTO todoitems (userID,parentID,OrderNumber,ItemID,hasChildren,ListID,ListVersion,ToDoText,CreateDate) VALUES ('.mysql_result($mysqlresult,0,"ID").',1,3,3,0,'.$ListID.',1,"New Todo Child Element",now())');
			

			$_SESSION['jumzu_User'] = true;
			$_SESSION['jumzu_AccountVerified'] = "0";
			$_SESSION['jumzu_UserID']     = mysql_result($mysqlresult,0,"ID");
			$_SESSION['jumzu_FullName']   = mysql_result($mysqlresult,0,"FirstName"); 
			$_SESSION['jumzu_Picture']    = $Picture;
			$_SESSION['jumzu_Email']      = mysql_result($mysqlresult,0,"Email");
			$_SESSION['Facebook_uid']     = mysql_result($mysqlresult,0,"Facebook_uid");

			if (!SendEmailVerification(mysql_result($mysqlresult,0,"Email"), mysql_result($mysqlresult,0,"FirstName"), "", mysql_result($mysqlresult,0,"ID")))
			{
				header( "Location: http://".$server_domain."/my-settings-account.php?message=welcome-email-error" ) ;
				exit();
			} else
			{
				header( "Location: http://".$server_domain."/my-settings-account.php?message=welcome" ) ;
				exit();
			}

		} else
		{
			$FormMessage = $signup_generic;
		}
	} else
	{
		$FormMessage = $signup_Error;
	}
}

?>

<script type="text/javascript" charset="utf-8">
	$(function(){ $("#signup_form label").inFieldLabels(); });
</script>

<div class="content-main-3">



			        

<?php if ($FormMessage!="") {?>
<div class="errorbox" style="margin-top:5px; margin-bottom:15px;">
<?php echo $FormMessage; ?>
<div style="height:5px;"></div>
<span style="line-height:1.8em;">
<?php echo $FirstName_Error; ?>
<?php echo $Email_Error; ?>
<?php echo $Password_Error; ?>
</span>
</div>
<?php } else { ?>
<p class="register-test-control">
<?php echo $signup_subtitle; ?>
</p>
<br/>
<?php }?>

<form name="signup_form" id="signup_form" method=post  autocomplete = "off">
<input type="hidden" name="op" value="save">
<input name="Picture" type="hidden" class="form" id="Picture" value="<?php echo $Picture; ?>" size="240" />
<input type="hidden" name="MiddleName" value="<?php echo $MiddleName; ?>" id="MiddleName" >
<input type="hidden" name="LastName" value="<?php echo $LastName; ?>" id="LastName">
<input type="hidden" name="UserName" value="<?php echo $UserName; ?>" id="UserName">

<span>
<label for="FirstName"><?php echo $signup_FirstName; ?></label>
  <input type="text" name="FirstName" value="<?php echo $FirstName; ?>" id="FirstName" class="signup-name" onBlur="makeusername();"; style="width:400px" title="<?php echo $signup_LastName_tip; ?>">
</span>
<div style="height:15px"></div>

<span>
  <label for="Email"><?php echo $signup_Email; ?></label>
  <input type="text" name="Email" value="<?php echo $Email; ?>" id="Email" class="signup-name" title="<?php echo $signup_Email_tip; ?>"  style="width:400px">
</span>
<div style="height:15px"></div>
<span>
  <label for="Password"><?php echo $signup_Password; ?></label>
  <input type="password" name="Password" value="" id="Password" class="signup-name" title="<?php echo $signup_Password_tip; ?>"  style="width:400px">
</span>

<input type="hidden" name="Location" value="<?php echo $Location; ?>" id="Location">

<input type="hidden" name="Gender" value="<?php echo $Gender; ?>" id="Gender">

<div style="height:15px"></div>
<input type="submit" name="join_button" value="<?php echo $signup_join_button; ?>" class="submitbutton" />
</form>
<br>

<p class="smalltext gry" style="line-height:1.8em">
    <?php echo $signup_join_text; ?>
</p>

<br>
<br>

  <a id="facebookButton" onclick="facebookLogin(); return false;" href="#"><font color=white><?php echo $index_page_signin_up_fb_txt; ?></font></a>


<script type='text/javascript'>

    $(function() {
	 $('#signup_form [title]').tipsy({trigger: 'focus', fade:true, gravity: 'w'});
    });


    $(function() {
		$(".example8").colorbox({width:"300px", inline:true, speed:100, initialWidth:'100px', initialHeight:'100px', opacity:'0.2', speed:350, transition:"elastic" , href:"#inline_example1"});
    });

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