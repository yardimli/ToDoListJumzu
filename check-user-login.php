<?php require_once("/server-settings.php"); ?>
<?php
if ( (!isset($_SESSION['jumzu_UserID'])) or ($_SESSION['jumzu_UserID']=="") or ($_SESSION['jumzu_UserID']=="0") ) 
{
	header( "Location: http://".$server_domain."/signin.php?q=session_end" ) ;
	exit();
}
?>