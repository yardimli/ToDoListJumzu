<?php 
	session_start(); 
	ob_start(); 

	$MysqlIP = "localhost";

	mysql_connect($MysqlIP,"root","Mantik77"); //B123456a
	mysql_select_db("jumzu");
	$mysqlresult = mysql_query("SET NAMES utf8");
	$mysqlresult = mysql_query("SET CHARACTER_SET utf8");

	if (($_SESSION['jumzu_UserID']=="") or ($_SESSION['jumzu_UserID']=="0")) { $jumzu_UserID = "1";	} else { $jumzu_UserID = $_SESSION['jumzu_UserID']; }	
	
	//insert new list
	$query = mysql_query('INSERT INTO todolists (userID,listName,listVersion,CreateDate) VALUES ('.$jumzu_UserID.',"New List",1,now())');

	//select new list 
	$query = mysql_query('SELECT * FROM todolists WHERE userID='.$jumzu_UserID.' ORDER BY ID DESC LIMIT 1');

	$ListID = mysql_result($query,0,"ID");

	//insert dummy items into new list
	
	$query = mysql_query('INSERT INTO todoitems (userID,parentID,OrderNumber,ItemID,hasChildren,ListID,ListVersion,ToDoText,CreateDate) VALUES ('.$jumzu_UserID.',0,1,1,0,'.$ListID.',1,"New Todo List",now())');
	$query = mysql_query('INSERT INTO todoitems (userID,parentID,OrderNumber,ItemID,hasChildren,ListID,ListVersion,ToDoText,CreateDate) VALUES ('.$jumzu_UserID.',1,2,2,0,'.$ListID.',1,"New Todo Child Element",now())');
	$query = mysql_query('INSERT INTO todoitems (userID,parentID,OrderNumber,ItemID,hasChildren,ListID,ListVersion,ToDoText,CreateDate) VALUES ('.$jumzu_UserID.',1,3,3,0,'.$ListID.',1,"New Todo Child Element",now())');


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

?>