<?php 


function utf8_urldecode($str) {
    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    return html_entity_decode($str,null,'UTF-8');;
  }

	session_start(); 
	ob_start(); 

	if (($_SESSION['jumzu_UserID']=="") or ($_SESSION['jumzu_UserID']=="0")) { $jumzu_UserID = "1";	} else { $jumzu_UserID = $_SESSION['jumzu_UserID']; }	

	$MysqlIP = "localhost";

	mysql_connect($MysqlIP,"root","Mantik77"); //B123456a
	mysql_select_db("jumzu");
	$mysqlresult = mysql_query("SET NAMES utf8");
	$mysqlresult = mysql_query("SET CHARACTER_SET utf8");


//	echo ( $_POST["test"]." ". utf8_urldecode($_POST["inputXML"]) );

	$data = simplexml_load_string( $_POST["inputXML"] );
	//print_r($data);

	$query = mysql_query('UPDATE todolists SET ListName = "' . str_replace('"','\"',( $_POST["ToDoTitle"]) ) . '" WHERE userID='.$jumzu_UserID.' and ID='.$_SESSION["ListID"]);

	
	$query = mysql_query('SELECT * FROM todolists WHERE userID='.$jumzu_UserID.' and ID='.$_SESSION["ListID"]);
	$ListVersion = mysql_result($query,0,"ListVersion");
	$ListVersion++;


	foreach($data as $ToDoLine){ //->todoitem->todoline
		
		$IsDone = (string)$ToDoLine->XDone;
		$ItemID = (string)$ToDoLine->XLine;
		$ParentID = (string)$ToDoLine->XParent;
		$ToDoText = (string)$ToDoLine->XText;
		$HasChildren = (string)$ToDoLine->HasChildren;

		$query = mysql_query('INSERT INTO todoitems (userID,listID,ListVersion,IsDone,ParentID,OrderNumber,ItemID,hasChildren,ToDoText,CREATEDATE) VALUES '.
			'(' . $jumzu_UserID . ',' . $_SESSION["ListID"] . ',' . $ListVersion . ',' . $IsDone . ',' . $ParentID . ',' . $ItemID . ',' . $ItemID . ',' . $HasChildren . ',"' . str_replace('"','\"',$ToDoText) . '",now())');

	}
	
	$query = mysql_query('UPDATE todolists set ListVersion = '. $ListVersion . ' WHERE userID='.$jumzu_UserID.' and ID='.$_SESSION["ListID"]);

	

	echo "SAVED BY THE BELL";
?>