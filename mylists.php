<?php 
	session_start(); 
	ob_start(); 

	$MysqlIP = "localhost";

	mysql_connect($MysqlIP,"root","Mantik77"); //B123456a
	mysql_select_db("jumzu");
	$mysqlresult = mysql_query("SET NAMES utf8");
	$mysqlresult = mysql_query("SET CHARACTER_SET utf8");
	
	if (($_SESSION['jumzu_UserID']=="") or ($_SESSION['jumzu_UserID']=="0")) { $jumzu_UserID = "1";	} else { $jumzu_UserID = $_SESSION['jumzu_UserID']; }	

	if ($_POST["op"]=="del")
	{
		$query = mysql_query('SELECT * FROM todolists WHERE userID='.$jumzu_UserID.' AND isDeleted=0 ORDER BY ID Asc');
		$num_rows = mysql_num_rows($query);
		if ($num_rows==1) 
		{
			//do nothing can't delete last list
		} else
		{
			$query = mysql_query('UPDATE todolists SET isDeleted=1 WHERE userID='.$jumzu_UserID.' AND ID='.$_SESSION["ListID"].'');
		}
	}

	$query = mysql_query('SELECT * FROM todolists WHERE userID='.$jumzu_UserID.'  AND isDeleted=0 ORDER BY ID Asc');
	$num_rows = mysql_num_rows($query);

	$i=0;
	while ($i<$num_rows)
	{
		echo "<li class=\"my-todo-lists-li\" data-ListID=\"". mysql_result($query,$i,"ID") ."\"><div class=\"my-todo-lists-class\">
			  <img align=\"left\" style=\"margin-top:1px\" class=\"controls-matrix\" src=\"images/blank.gif\" alt=\"\"><span>". mysql_result($query,$i,"ListName") ."</span></div></li>\n";
		$i++;
	}

	echo "<li class=\"my-todo-lists-li\" data-ListID=\"0\"><div class=\"my-todo-lists-class\">
		  <img align=\"left\" style=\"margin-top:1px\" class=\"controls-formcollapse\" src=\"images/blank.gif\" alt=\"\"><span>Add New Todo List</span></div></li>\n";

	echo '<script type="text/javascript"> ';
	echo '	$(\'[data-listid="\'+LastListDiv+\'"]\').css({"font-weight":"bold"}); 

		</script> ';

?>