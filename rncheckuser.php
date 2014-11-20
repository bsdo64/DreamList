<?php // rncheckuser.php
include_once 'rnfunctions.php';

if (isset($_POST['user']))
{
	$user = sanitizeString($_POST['user']);
	$query = "SELECT * FROM rnmembers WHERE user='$user'";

	if (mysql_num_rows(queryMysql($query)))
		echo "<span class='imposs'>&nbsp;&larr;죄송합니다. 이미 사용중인 아이디입니다.</span>";
	else
		echo "<span class='avail'>&nbsp;&larr;사용가능한 아이디 입니다</span>";
}
?>
