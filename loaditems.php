<?php

session_start();
ob_start();

if (($_SESSION['jumzu_UserID'] == "") or ($_SESSION['jumzu_UserID'] == "0")) {
	$jumzu_UserID = "1";
} else {
	$jumzu_UserID = $_SESSION['jumzu_UserID'];
}

$MysqlIP = "localhost";

mysql_connect($MysqlIP, "root", "Mantik77"); //B123456a
mysql_select_db("jumzu");
$mysqlresult = mysql_query("SET NAMES utf8");
$mysqlresult = mysql_query("SET CHARACTER_SET utf8");

function build_tree($array) {
	$tree = array();
	foreach ($array as $id => &$node) {
		if ($node['parent'] == 0) {
			$tree[$id] = &$node;
		} else {
			if (!isset($array[$node['parent']]['children'])) {
				$array[$node['parent']]['children'] = array();
			}
			$array[$node['parent']]['children'][$id] = &$node;
		}
	}
	return build_html_tree($tree);
}

function build_html_tree($array) {
	$output = '';
	foreach ($array as $item) {

		$xChecked = "";
		$StrikThrough = "";

		if ($item['xdone'] == "1") {
			$xChecked = " CHECKED ";
			$StrikThrough = "text-decoration:line-through";
		}

		if (isset($item['children'])) {

			$output .= '<li id="list_' . $item['id'] . '"><div id="list_' . $item['id'] . '_div" class="sortable-div"><input type="checkbox" id="list_' . $item['id'] . '_check" ' . $xChecked . ' /><span class="checkLabel" style="' . $StrikThrough . '" id="list_' . $item['id'] . '_text">' . $item['value'] . '</span></div>';
			$output .='<ol>' . build_html_tree($item['children']) . '</ol></li>';
		} else {
			$output .= '<li id="list_' . $item['id'] . '"><div id="list_' . $item['id'] . '_div" class="sortable-div"><input type="checkbox" id="list_' . $item['id'] . '_check" ' . $xChecked . ' /><span class="checkLabel" style="' . $StrikThrough . '" id="list_' . $item['id'] . '_text">' . $item['value'] . '</span></div></li>';
		}
	}
	return $output;
}

$ListID = intval($_POST["ListID"], 10);

if ($ListID == "0") {
	$query = mysql_query('SELECT * FROM todolists WHERE userID=' . $jumzu_UserID . ' AND isDeleted=0 ORDER BY ID DESC');
	$_SESSION["ListID"] = mysql_result($query, 0, "ID");
} else {
	$_SESSION["ListID"] = $ListID;
	$query = mysql_query('SELECT * FROM todolists WHERE userID=' . $jumzu_UserID . ' AND isDeleted=0 and ID=' . $_SESSION["ListID"]);

	if (mysql_num_rows($query) == 0) {
		$query = mysql_query('SELECT * FROM todolists WHERE userID=' . $jumzu_UserID . ' AND isDeleted=0 ORDER BY ID DESC');
		$_SESSION["ListID"] = mysql_result($query, 0, "ID");
	}
}

$query = mysql_query('SELECT * FROM todolists WHERE userID=' . $jumzu_UserID . ' and ID=' . $_SESSION["ListID"] . ' AND isDeleted=0');
$ListVersion = mysql_result($query, 0, "ListVersion");
$ListName = mysql_result($query, 0, "ListName");

$query = mysql_query('SELECT * FROM todoitems WHERE userID=' . $jumzu_UserID . ' and ListID=' . $_SESSION["ListID"] . ' AND ListVersion=' . $ListVersion . ' ORDER BY ItemID ASC');
$unsorted = array();
while ($item = mysql_fetch_array($query)) {
	$unsorted[$item['ItemID']] = array(
		'id' => $item['ItemID'],
		'parent' => $item['ParentID'],
		'value' => $item['ToDoText'],
		'xdone' => $item['IsDone'],
	);
}

echo '<ol id="todolist1" class="sortable ui-sortable">' . build_tree($unsorted) . '</ol>';
echo '<script type="text/javascript"> $("#todo_list_title").text("' . $ListName . '"); ';

echo '	$(\'[data-listid="\'+LastListDiv+\'"]\').css({"font-weight":"normal"}); LastListDiv = "' . $_SESSION["ListID"] . '"; $(\'[data-listid="' . $_SESSION["ListID"] . '"]\').css({"font-weight":"bold"});

		</script> ';
?>