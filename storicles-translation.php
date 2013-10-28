<?php require_once("/server-settings.php"); ?>
<?php
/*
if (($_SESSION['jumzu_ILanguageID']=="1") or ($_SESSION['jumzu_ILanguageID']=="") or ($_SESSION['jumzu_ILanguageID']=="0")) {
	include('/storicles-english-language.php');
	include('/storicles-turkish-language.php');
} else

///ENGLISH
if ($_SESSION['jumzu_ILanguageID']=="2") {
	include('/storicles-english-language.php');
} else

///CHINESE
if ($_SESSION['jumzu_ILanguageID']=="3") {
	include('/storicles-english-language.php');
	include('/storicles-chinese-language.php');
} else

///NORWEGIAN
if ($_SESSION['jumzu_ILanguageID']=="4") {
	include('/storicles-english-language.php');
	include('/storicles-norwegian-langauge.php');
}
*/

include('/storicles-english-language.php');
$_SESSION['jumzu_ILanguageID']="2";


function lang_aboutme($myname,$uid)
{
	if (($_SESSION['jumzu_ILanguageID']=="1") or ($_SESSION['jumzu_ILanguageID']=="") or ($_SESSION['jumzu_ILanguageID']=="0")) { 
		$res = "<b>@". $myname ."</b>";
		if ($uid==$_SESSION['jumzu_UserID']) { $res= "<b>benim hakkımda</b>"; } else {$res= $res." hakkında"; }
	} else
	if ($_SESSION['jumzu_ILanguageID']=="2") {
		$res = "<b>@". $myname ."</b>";
		if ($uid==$_SESSION['jumzu_UserID']) { $res= "<b>about me</b>"; } else {$res= "about ".$res; }
	} else
	if ($_SESSION['jumzu_ILanguageID']=="3") {
		$res = "<b>@". $myname ."</b>";
		if ($uid==$_SESSION['jumzu_UserID']) { $res= "<b>about me</b>"; } else {$res= "about ".$res; }
	} else
	if ($_SESSION['jumzu_ILanguageID']=="4") {
		$res = "<b>@". $myname ."</b>";
		if ($uid==$_SESSION['jumzu_UserID']) { $res= "<b>about me</b>"; } else {$res= "about ".$res; }
	}

	return $res;

}

function min_rec_time($seconds)
{
	if (($_SESSION['jumzu_ILanguageID']=="1") or ($_SESSION['jumzu_ILanguageID']=="") or ($_SESSION['jumzu_ILanguageID']=="0")) { 
		$res = "Bir storicles en az 2 saniye uzunluğunda olmalı. Sizin kaydınız ".Round($seconds)." sürdü (başındaki ve sonundaki boşluklar silindikten sonra)";
	} else
	if ($_SESSION['jumzu_ILanguageID']=="2") {
		$res = "Your story must be at least 30 second. Your recording was ".Round($seconds)." seconds after triming.";
	} else
	if ($_SESSION['jumzu_ILanguageID']=="3") {
		$res = "Your story must be at least 30 second. Your recording was ".Round($seconds)." seconds after triming.";
	} else
	if ($_SESSION['jumzu_ILanguageID']=="4") {
		$res = "Your story must be at least 30 second. Your recording was ".Round($seconds)." seconds after triming.";
	}
	return $res;

}

function echo_by($username)
{
	if (($_SESSION['jumzu_ILanguageID']=="1") or ($_SESSION['jumzu_ILanguageID']=="") or ($_SESSION['jumzu_ILanguageID']=="0")) { 
		$res = $username . " tarafından yankılandı!";
	} else
	if ($_SESSION['jumzu_ILanguageID']=="2") {
		$res = "echoed by ". $username;
	} else
	if ($_SESSION['jumzu_ILanguageID']=="3") {
		$res = "echoed by ". $username;
	} else
	if ($_SESSION['jumzu_ILanguageID']=="4") {
		$res = "echoed by ". $username;
	}
	return $res;
}

?>