<?php 

require_once('/SimpleImage.php');
require_once('/class.phpmailer.php');
require_once('/class.pop3.php'); // required for POP before SMTP
/*
include("EpiCurl.php");
include("EpiOAuth.php");
include("EpiTwitter.php");
*/

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) //if from shared
    {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //if from a proxy
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function verify_Email($Email){

    if(!preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/',$Email)){
        return false;
    } else {
        return true;
    }
}

function ATQ($inputx) { return "'".AddSlashes(Trim($inputx))."'"; }

function get_facebook_cookie($app_id, $app_secret) {
  $args = array();
  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
  ksort($args);
  $payload = '';
  foreach ($args as $key => $value) {
    if ($key != 'sig') {
      $payload .= $key . '=' . $value;
    }
  }
  if (md5($payload . $app_secret) != $args['sig']) {
    return null;
  }
  return $args;
}

function get_redirect_url($url){
	$redirect_url = null; 
 
	$url_parts = @parse_url($url);
	if (!$url_parts) return false;
	if (!isset($url_parts['host'])) return false; //can't process relative URLs
	if (!isset($url_parts['path'])) $url_parts['path'] = '/';
 
	$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
	if (!$sock) return false;
 
	$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n"; 
	$request .= 'Host: ' . $url_parts['host'] . "\r\n"; 
	$request .= "Connection: Close\r\n\r\n"; 
	fwrite($sock, $request);
	$response = '';
	while(!feof($sock)) $response .= fread($sock, 8192);
	fclose($sock);
 
	if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
		if ( substr($matches[1], 0, 1) == "/" )
			return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
		else
			return trim($matches[1]);
 
	} else {
		return false;
	}
 
}

function SendEmailVerification($Email,$FullName,$LastName,$UID)
{
	$server_domain = $_SERVER["SERVER_NAME"];
	//create email validation code and update database
	$VerificationCode = md5("mymail".$UID.time().$randomword);
	$xsqlCommand2 = "UPDATE users set VerificationCode='". $VerificationCode ."' WHERE ID=".$UID;
	$mysqlresult2 = mysql_query($xsqlCommand2);

	//send welcome and validation email

	/*  comment out following 2 lines for godaddy server */
	/*
	$pop = new POP3();
	$pop->Authorise('posta.izlenim.com', 110, 30, 'welcome@jumzu.com', 'A123456b', 1);
	*/
	$mail = new PHPMailer();

	$body             = file_get_contents(getcwd().'\\welcome-to-storicles-en.html');
	$TheSubject = "Welcome to storicles";
	$TheSenderX = "Social Talking";

	$body             = eregi_replace("[\]",'',$body);
	$body             = str_replace("***FIRSTNAME***",$FullName,$body);
	$body             = str_replace("***URL1***","http://".$server_domain."/verify-email.php?q=".$VerificationCode,$body);
	$body             = str_replace("***URL2***","http://".$server_domain."/verify-email.php?q=".$VerificationCode,$body);

	$mail->IsSMTP();
	$mail->SMTPDebug = 1;
//	$mail->Host     = 'posta.izlenim.com';
	$mail->Host     = 'p3smtpout.secureserver.net'; 

	$mail->SetFrom('welcome@jumzu.com', '=?UTF-8?B?'.base64_encode('storicles - '.$TheSenderX).'?=');
	$mail->AddReplyTo("do-not-reply@jumzu.com","storicles");
	$mail->Subject    = "=?UTF-8?B?".base64_encode($TheSubject)."?=";
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($body);

	$address = $Email;
	$mail->AddAddress($address, $FullName); //." ".$LastName

	//send email
	if(!$mail->Send()) 
	{
		return false;
//		$HasError = 1;
//		$FormMessage = "We could not reach you by email please make sure the mail you provided is correct. -- Mailer Error:".$mail->ErrorInfo;
	} else
	{
		return true;
//		header( "Location: http://www.jumzu.com/settings/profile?message=welcome" ) ;
//		exit();
	}
}

function SaveProfileLocal($Picture,$ID)
{
	$server_domain = $_SERVER["SERVER_NAME"];
	if (stripos($Picture,"fbcdn.net")!==false)
	{
		$img = getcwd()."\\site_veri\\".basename($Picture);
		file_put_contents($img, file_get_contents($Picture));
		$InFileName2 = getcwd()."\\site_veri\\".basename($Picture);
	} else
	if (stripos($Picture,"/site_veri/profile_temp/")!== false)
	{
		$InFileName = $Picture;
		$InFileName = str_replace("/site_veri/profile_temp/","",$InFileName);
		$InFileName2 = getcwd()."\\site_veri\\profile_temp\\".$InFileName;
	} else
	{
		$InFileName = $Picture;
		$InFileName = str_replace("http://".$server_domain."/","",$InFileName);
		$InFileName2 = getcwd()."\\".$InFileName;
	}

	$file_info = getimagesize($InFileName2);
	$file_type = $file_info[2];
	$file_prefix = sprintf( '%012d',$ID)."-".time();

	if( $file_type == IMAGETYPE_JPEG ) { $OutFileName = $file_prefix.".jpg"; } else
	if( $file_type == IMAGETYPE_GIF  ) { $OutFileName = $file_prefix.".gif"; } else
	if( $file_type == IMAGETYPE_PNG  ) { $OutFileName = $file_prefix.".png"; }

	$OutFileName2 = getcwd()."\\profile-images\\".$OutFileName;

	$image = new SimpleImage();
	$image->load($InFileName2);
	$image->resizeToWidth(180);
	$image->save($OutFileName2);

	$xsqlCommandPic = "UPDATE users SET picture='". AddSlashes(Trim("/profile-images/".$OutFileName)) ."' WHERE ID=".$ID;
	$mysqlresultPic = mysql_query($xsqlCommandPic);
	return "/profile-images/".$OutFileName;

}

function valid_pass($candidate) {
   $r1='/[A-Z]/';  //Uppercase
   $r2='/[a-zA-Z]/';  //lowercase
   $r3='/[\']/';  // whatever you mean by 'special char'
   $r4='/[0-9]/';  //numbers

//   if(preg_match_all($r1,$candidate, $o)<1) return FALSE;

   if(preg_match_all($r2,$candidate, $o)<1) return FALSE;

   if(preg_match_all($r3,$candidate, $o)>0) return FALSE;

   if(preg_match_all($r4,$candidate, $o)<1) return FALSE;

   if(strlen($candidate)<5) return FALSE;
   if(strlen($candidate)>20) return FALSE;

   return TRUE;
}


/**
 * Logging class:
 * - contains lfile, lopen and lwrite methods
 * - lfile sets path and name of log file
 * - lwrite will write message to the log file
 * - first call of the lwrite will open log file implicitly
 * - message is written with the following format: hh:mm:ss (script name) message
 */
class Logging{
    // define default log file
    private $log_file = '/tmp/logfile.log';
    // define file pointer
    private $fp = null;
    // set log file (path and name)
    public function lfile($path) {
        $this->log_file = $path;
    }
    // write message to the log file
    public function lwrite($message){
        // if file pointer doesn't exist, then open log file
        if (!$this->fp) $this->lopen();
        // define script name
        $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        // define current time
        $time = date('H:i:s');
        // write current time, script name and message to the log file
        fwrite($this->fp, "$time ($script_name) $message\n");
    }
    // open log file
    private function lopen(){
        // define log file path and name
        $lfile = $this->log_file;
        // define the current date (it will be appended to the log file name)
        $today = date('Y-m-d');
        // open log file for writing only; place the file pointer at the end of the file
        // if the file does not exist, attempt to create it
        $this->fp = fopen($lfile . '_' . $today, 'a') or exit("Can't open $lfile!");
    }
}

function month_nameX($month)
{
	if ($month=="01") { return $GLOBALS["signup_January"]; } else 
	if ($month=="02") { return $GLOBALS["signup_February"]; } else 
	if ($month=="03") { return $GLOBALS["signup_March"]; } else 
	if ($month=="04") { return $GLOBALS["signup_April"]; } else 
	if ($month=="05") { return $GLOBALS["signup_May"]; } else 
	if ($month=="06") { return $GLOBALS["signup_June"]; } else 
	if ($month=="07") { return $GLOBALS["signup_July"]; } else 
	if ($month=="08") { return $GLOBALS["signup_August"]; } else 
	if ($month=="09") { return $GLOBALS["signup_September"]; } else 
	if ($month=="10") { return $GLOBALS["signup_October"]; } else 
	if ($month=="11") { return $GLOBALS["signup_November"]; } else 
	if ($month=="12") { return $GLOBALS["signup_December"]; } else return "";

}

function datetotext($inputdate)
{
	$datetime1 = strtotime("now");

	$rectime = strtotime( $inputdate )-10800;
	$minutes_age = round( ($datetime1-$rectime) / 60 );

	$hours_age = round( ($datetime1-$rectime) / 3600 );
	$days_age = round( ($datetime1-$rectime) / (3600*24) );

	if (($_SESSION['jumzu_ILanguageID']=="1") or ($_SESSION['jumzu_ILanguageID']=="") or ($_SESSION['jumzu_ILanguageID']=="0")) { 
		if ($minutes_age<=3) { return " yeni eklendi"; }  else
		if ($minutes_age<=15) { return " birkaç dakika önce"; }  else
		if ($minutes_age<=60) { return $minutes_age . " dakika önce"; }  else
		if ($hours_age<=24) { return $hours_age . " saat önce"; } else
		if ($days_age<=4) { return $days_age . " gün önce"; } else
		{ 
			$day = date("d",$rectime);
			$month = date("m",$rectime);
			$year = date("Y",$rectime);
			return  $day . " ". month_nameX($month) . " " . $year;
		}
	} else
	if ($_SESSION['jumzu_ILanguageID']=="2") {
		if ($minutes_age<=3) { return " just now"; }  else
		if ($minutes_age<=15) { return " a few minutes ago"; }  else
		if ($minutes_age<=60) { return $minutes_age . " minutes ago"; }  else
		if ($hours_age<=24) { return $hours_age . " hours ago"; } else
		if ($days_age<=4) { return $days_age . " days ago"; } else
		{ 
			$day = date("d",$rectime);
			$month = date("m",$rectime);
			$year = date("Y",$rectime);
			return  $day . " ". month_nameX($month) . " " . $year;
		}
	} else
	if ($_SESSION['jumzu_ILanguageID']=="3") {
		if ($minutes_age<=3) { return " just now"; }  else
		if ($minutes_age<=15) { return " a few minutes ago"; }  else
		if ($minutes_age<=60) { return $minutes_age . " minutes ago"; }  else
		if ($hours_age<=24) { return $hours_age . " hours ago"; } else
		if ($days_age<=4) { return $days_age . " days ago"; } else
		{ 
			$day = date("d",$rectime);
			$month = date("m",$rectime);
			$year = date("Y",$rectime);
			return  $day . " ". month_nameX($month) . " " . $year;
		}
	} else
	if ($_SESSION['jumzu_ILanguageID']=="4") {
		if ($minutes_age<=3) { return " just now"; }  else
		if ($minutes_age<=15) { return " a few minutes ago"; }  else
		if ($minutes_age<=60) { return $minutes_age . " minutes ago"; }  else
		if ($hours_age<=24) { return $hours_age . " hours ago"; } else
		if ($days_age<=4) { return $days_age . " days ago"; } else
		{ 
			$day = date("d",$rectime);
			$month = date("m",$rectime);
			$year = date("Y",$rectime);
			return  $day . " ". month_nameX($month) . " " . $year;
		}
	}

}

function make_bitly_url($url, $login, $appkey, $format="xml", $history=1, $version="2.0.1")
{
	//create the URL
	$bitly = "http://api.bit.ly/shorten";
	$param = "version=" . $version . "longUrl=" . urlencode($url)."&login="	.$login."&apiKey=".$appkey."&format=".$format."&history=".$history;
	//get the url
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $bitly . "?" . $param);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	//parse depending on desired format
	if(strtolower($format) == "json") {
		$json = @json_decode($response,true);
		return $json["results"][$url]["shortUrl"];
	} else {
		$xml = simplexml_load_string($response);
		return "http://bit.ly/".$xml->results->nodeKeyVal->hash;
	}
}


function make_bitly_url_2($url,$login,$appkey,$format = 'xml',$history = 1)
{
	//create the URL
	$bitly = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).
	'&format='.$format.'&history='.$history;
	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);
	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['data']['url'];
	}
	elseif(strtolower($format) == 'xml') //xml
	{
		$xml = simplexml_load_string($response);
		return $xml->data->url;
	}
		elseif(strtolower($format) == 'txt') //text
	{
		return $response;
	}
}



function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
} 

function sec2hms ($sec, $padHours = false) 
  {

    // start with a blank string
    $hms = "";
    
    // do the hours first: there are 3600 seconds in an hour, so if we divide
    // the total number of seconds by 3600 and throw away the remainder, we're
    // left with the number of hours in those seconds
    $hours = intval(intval($sec) / 3600); 

    // add hours to $hms (with a leading 0 if asked for)
    $hms .= ($padHours) 
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
          : $hours. ":";
    
    // dividing the total seconds by 60 will give us the number of minutes
    // in total, but we're interested in *minutes past the hour* and to get
    // this, we have to divide by 60 again and then use the remainder
    $minutes = intval(($sec / 60) % 60); 

    // add minutes to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

    // seconds past the minute are found by dividing the total number of seconds
    // by 60 and using the remainder
    $seconds = intval($sec % 60); 

    // add seconds to $hms (with a leading 0 if needed)
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;
    
  }

?>