
<?php // rnfunctions.php
$dbhost  = 'localhost';    // Unlikely to require changing
$dbname  = 'bsdo3'; // Modify these...
$dbuser  = 'bsdo3';     // ...variables according
$dbpass  = 'dkbs!@13579';     // ...to your installation
$appname = "My dream List"; // ...and preference

mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

function createTable($name, $query)
{
	if (tableExists($name))
	{
		echo "Table '$name' already exists<br />";
	}
	else
	{
		queryMysql("CREATE TABLE $name($query)");
		echo "Table '$name' created<br />";
	}
}

function tableExists($name)
{
	$result = queryMysql("SHOW TABLES LIKE '$name'");
	return mysql_num_rows($result);
}

function queryMysql($query)
{
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}

function destroySession()
{
	$_SESSION=array();
	
	if (session_id() != "" || isset($_COOKIE[session_name()]))
	    setcookie(session_name(), '', time()-2592000, '/');
		
	session_destroy();
}

function sanitizeString($var)
{
	$var = strip_tags($var);
	$var = stripslashes($var);
	return mysql_real_escape_string($var);
}

function showProfile($user)
{
	if (is_file("images/profile/$user.jpg"))
		echo "<img class='profileimg' src='images/profile/$user.jpg' border='1' align='left' />";
		
	$result = queryMysql("SELECT * FROM rnprofiles WHERE user='$user'");
	
	if (mysql_num_rows($result))
	{
		$row = mysql_fetch_row($result);
		echo '<fieldset><legend>프로필소개</legend><p>';
		echo stripslashes($row[1]) . "<br clear=left />";
		echo '</p></fieldset>';
	}
}
?>
