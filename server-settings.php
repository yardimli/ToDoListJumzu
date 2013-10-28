<?php 
if (strpos($_SERVER["SERVER_NAME"],"jumzu.com")===false)
{
//	exit;
}
//$some_name = session_name("storiclescom");
//session_set_cookie_params ( 0 , '/', '.storicles.com');

session_start(); 
ob_start(); 

header('Content-Type: text/html; charset=UTF-8');

$lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
$page_subdomain = strtolower(substr($_SERVER["SERVER_NAME"], 0, 2));
$server_domain = $_SERVER["SERVER_NAME"];


if (!(stripos($server_domain,"localhost")===false)) {
	$server_domain = "localhost/checkone";
}

$randomword = "bobblebibblechocolateccaat";

$MysqlIP = "localhost";

mysql_connect($MysqlIP,"root","Mantik77"); //B123456a
mysql_select_db("jumzu");
$mysqlresult = mysql_query("SET NAMES utf8");
$mysqlresult = mysql_query("SET CHARACTER_SET utf8");


/*$log = new Logging2();
$log->lfile('c:/php-log');
$log->lwrite('-...----------------------------------');
*/

$Turkish_letters = array("ü","Ü","ç","Ç","ğ","Ğ","ö","Ö","ş","Ş","ı","İ","'");
$Turkish_letters2= array("u","U","c","C","g","G","o","O","s","S","i","I","");

/**
 * Logging class:
 * - contains lfile, lopen and lwrite methods
 * - lfile sets path and name of log file
 * - lwrite will write message to the log file
 * - first call of the lwrite will open log file implicitly
 * - message is written with the following format: hh:mm:ss (script name) message
 */
class Logging2{
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


//$log->lwrite('REQUEST URI: '.$_SERVER["REQUEST_URI"]);
/*
if ( (stripos($_SERVER["REQUEST_URI"],"new-storicles")!== false) or (stripos($_SERVER["REQUEST_URI"],"save-storicles")!== false) or (stripos($_SERVER["REQUEST_URI"],"getid3")!== false) or (stripos($_SERVER["REQUEST_URI"],"storicles-saved")!== false) or (stripos($_SERVER["REQUEST_URI"],"process")!== false)  or (stripos($_SERVER["REQUEST_URI"],"upload")!== false) or (stripos($_SERVER["REQUEST_URI"],"search")!== false) or (stripos($_SERVER["REQUEST_URI"],"avc_settings")!== false) or (stripos($_SERVER["REQUEST_URI"],"save_audio_to_db")!== false)  ) 
{
	// do nothing
} else
{
	//sign out ananymous user
	if ($_SESSION['jumzu_UserID']=="1000055") {
		$_SESSION['jumzu_User'] = false;
		$_SESSION['jumzu_UserID'] = "";
		$_SESSION['jumzu_UserName'] = "";
		$_SESSION['jumzu_FullName'] = "";
		$_SESSION['jumzu_Picture'] = "";

		$_SESSION['twitter_oauth_token']         = "";
		$_SESSION['twitter_oauth_token_secret']  = "";
	}
}
*/
?>