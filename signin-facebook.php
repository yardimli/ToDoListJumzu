<?php $page="signin"; ?>
<?php require_once("server-settings.php"); ?>
<?php include("php-functions.php"); ?>
<?php

$page_subdomain = strtolower(substr($_SERVER["SERVER_NAME"], 0, 2));

$access_token= $_COOKIE["access_token"];
//echo $access_token;
//print_r($_COOKIE);
//exit();

if (($access_token=="") && ($_GET["code"]!="")) {
	$token_url = "https://graph.facebook.com/oauth/access_token?"
   . "client_id=261798160586392&redirect_uri=" . urlencode("http://".$server_domain."/signin-facebook.php")
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
} else
{
	$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
	$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=261798160586392&redirect_uri=" . urlencode("http://".$server_domain."/signin-facebook.php") . "&state=". $_SESSION['state'];

//		echo("<script> top.location.href='" . $dialog_url . "'</script>");
//		echo $dialog_url;
	header( "Location: ".$dialog_url ) ;
	exit();
}


if ($user->id=="") {
	header( "Location: http://".$server_domain."/index.php?r=5" ) ;
	exit();

}
$xsqlCommand = "SELECT * FROM users WHERE Facebook_uid = '".AddSlashes(Trim( $user->id ))."' ORDER BY ID DESC LIMIT 1";
//		echo $xsqlCommand;
$mysqlresult = mysql_query($xsqlCommand);
$num_rows = mysql_num_rows($mysqlresult);
if ($num_rows==1)
{
	$_SESSION['jumzu_User'] = true;
	$_SESSION['jumzu_AccountVerified'] = mysql_result($mysqlresult,0,"AccountVerified");
	$_SESSION['jumzu_UserID'] = mysql_result($mysqlresult,0,"ID");
	$_SESSION['jumzu_UserName'] = mysql_result($mysqlresult,0,"Username");
	$_SESSION['jumzu_FullName'] = mysql_result($mysqlresult,0,"FirstName");
	$_SESSION['jumzu_Picture'] = mysql_result($mysqlresult,0,"Picture");
	$_SESSION['jumzu_Location']   = mysql_result($mysqlresult,0,"Location");
	$_SESSION['jumzu_Email']   = mysql_result($mysqlresult,0,"Email");
	$_SESSION['jumzu_ILanguageID'] = mysql_result($mysqlresult,0,"ILanguageID");

	if (($server_domain=="tr.jumzu.com") && ($_SESSION['jumzu_ILanguageID']!="1")) { $_SESSION['jumzu_ILanguageID']="1"; } else
	if ( 
		(($server_domain=="en.jumzu.com") && ($_SESSION['jumzu_ILanguageID']!="2")) or
		(($server_domain=="tw.jumzu.com") && ($_SESSION['jumzu_ILanguageID']!="3")) or
		(($server_domain=="no.jumzu.com") && ($_SESSION['jumzu_ILanguageID']!="4")) )
		{ 
			if ($_SESSION['jumzu_ILanguageID']=="2") { $server_domain="en.jumzu.com"; }
			if ($_SESSION['jumzu_ILanguageID']=="3") { $server_domain="tw.jumzu.com"; }
			if ($_SESSION['jumzu_ILanguageID']=="4") { $server_domain="no.jumzu.com"; }
		}


	$_SESSION['twitter_oauth_token']         = mysql_result($mysqlresult,0,"twitter_oauth_token");
	$_SESSION['twitter_oauth_token_secret']  = mysql_result($mysqlresult,0,"twitter_oauth_token_secret");

	$_SESSION['Facebook_uid']   = mysql_result($mysqlresult,0,"Facebook_uid");

	//header( "Location: http://".$server_domain."/u/".$_SESSION['jumzu_UserName'] ) ;
	header( "Location: http://".$server_domain."/" ) ;
	exit();
} else 
{
	header( "Location: http://" . $server_domain . "/signup-facebook.php" ) ;
	exit();
}

?>
</body>
</html>