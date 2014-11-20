<?php 
include 'rnfunctions.php';
session_start();
if (isset($_SESSION['user']))
{
	$user = $_SESSION['user'];
	$loggedin = TRUE;


}
else $loggedin = FALSE;
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<?php
echo "<title>$appname";
if ($loggedin) echo " ($user)";
echo "</title>";
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

	<div id="header">
		<ul id="menu">
<?php
	if ($loggedin)
	{
		echo "<li><a href='index.php?view=$user'>처음화면</a> |</li>
			<li><a href='members.php'>새로운 친구들</a> |</li>
			<li><a href='friends.php'>나만의 친구들</a> |</li>
			<li><a href='messages.php?page=1'><strong>My List</strong></a> |</li>
			<li><a href='profile.php'>내 정보</a> |</li>
			<li><a href='logout.php'>Log out</a></li>";
	}
	else
	{
		echo "<li><a href='index.php'>처음화면</a> |</li>
			 <li><a href='signup.php'>가입하기</a></li>";
	}
?>
		</ul>
		<p class="title">design &nbsp; solutions</p>																																																																	
	</div>

