		<div id="main">

			<div class="maincontents mainfriends">

<?php // rnfriends.php
include_once 'rnheader.php';

if (!isset($_SESSION['user']))

{
?>
			 <script>
				alert("먼저 가입후 로그인하시기 바랍니다");
			    location.href="index.php?";
			 </script>

<?php
}

	
$user = $_SESSION['user'];

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else $view = $user;

if ($view == $user)
{
	$name1 = "나의";
	$name2 = "나의";
	$name3 = "내";
}
else
{
	$name1 = "$view";
	$name2 = "$view";
	$name3 = "$view";
}

echo "<h3>$name1 친구들</h3>";

$followers = array(); $following = array();

$query  = "SELECT * FROM rnfriends WHERE user='$view'";
$result = queryMysql($query);
$num    = mysql_num_rows($result); 

for ($j = 0 ; $j < $num ; ++$j)
{
	$row = mysql_fetch_row($result);
	$followers[$j] = $row[1];
}

$query  = "SELECT * FROM rnfriends WHERE friend='$view'";
$result = queryMysql($query);
$num    = mysql_num_rows($result);

for ($j = 0 ; $j < $num ; ++$j)
{
	$row = mysql_fetch_row($result);
	$following[$j] = $row[0];
}

$mutual    = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);
$friends   = FALSE;

if (sizeof($mutual))
{
	echo "<h4>서로 추가한 친구들</h4><ul>";
	foreach($mutual as $friend)
		echo "<li><a href='members.php?view=$friend&page=1'>$friend</a>";
	echo "</ul>";
	$friends = TRUE;
}

if (sizeof($followers))
{
	echo "<h4>$name2"." 팔로워들</h4><ul>";
	foreach($followers as $friend)
		echo "<li><a href='members.php?view=$friend&page=1'>$friend</a>";
	echo "</ul>";
	$friends = TRUE;
}

if (sizeof($following))
{
	echo "<h4>$name3"."가 추가한 친구</h4><ul>";
	foreach($following as $friend)
		echo "<li><a href='members.php?view=$friend&page=1'>$friend</a>";
	$friends = TRUE;
}

if (!$friends) echo "<ul><li>아직 아무 친구도 없어요 친구를 추가해보는건 어떨까요?";

echo "</ul><br /><a href='messages.php?view=$view&page=1'>$name2 Dream List!</a>";
?>


			</div>

		</div>