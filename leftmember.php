		<div id="main">

			<div class="maincontents mainmembers">

<?php
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

if (isset($_GET['view']))
{
	$view = sanitizeString($_GET['view']);
	
	if ($view == $user) {

		$name = "나";
		echo "<h3>$name"."의 페이지</h3>";

	} else {

		$name = "$view";
		echo "<h3>$name"."의 페이지</h3>";
		showProfile($view);

	}
	

	
	echo "<a href='friends.php?view=$view&page=1'>$name"."의 친구</a><br />";
	echo "<a href='messages.php?view=$view&page=1'>$name"."의 Dream List!</a><br />";
}

if (isset($_GET['add']))
{
	$add = sanitizeString($_GET['add']);
	$query = "SELECT * FROM rnfriends WHERE user='$add'
			  AND friend='$user'";
	
	if (!mysql_num_rows(queryMysql($query)))
	{
		$query = "INSERT INTO rnfriends VALUES ('$add', '$user')";
		queryMysql($query);
	}
}
elseif (isset($_GET['remove']))
{
	$remove = sanitizeString($_GET['remove']);
	$query = "DELETE FROM rnfriends WHERE user='$remove'
			  AND friend='$user'";
	queryMysql($query);
}

$result = queryMysql("SELECT user FROM rnmembers ORDER BY user");
$num = mysql_num_rows($result);
echo "<h3>새로운 회원들</h3><ul class='memberul'>";

for ($j = 0 ; $j < $num ; ++$j)
{
	$row = mysql_fetch_row($result);
	if ($row[0] == $user) continue;
	
	echo "<li><a class='id' href='members.php?view=$row[0]&page=1'><span>$row[0]</span></a>";
	$query = "SELECT * FROM rnfriends WHERE user='$row[0]'
			  AND friend='$user'";
	$t1 = mysql_num_rows(queryMysql($query));
	
	$query = "SELECT * FROM rnfriends WHERE user='$user'
			  AND friend='$row[0]'";
	$t2 = mysql_num_rows(queryMysql($query));


	if (($t1 + $t2) > 1)
	{
		echo " <a class='disconnect' href='members.php?remove=".$row[0] . "&page=1'>[친구 끊기]</a>";
		echo " <span>&harr; 우린 서로 친구사이!</span>";
	}
	elseif ($t1)
	{
		echo " <a class='disconnect' href='members.php?remove=".$row[0] . "&page=1'>[친구 끊기]</a>";
		echo " <span>&larr; 내 친구인 회원이에요</span>";

	}
	elseif ($t2)
	{
		$follow = "신청을 받아줄께";
		echo " <a class='connect' href='members.php?add=".$row[0] . "&page=1'>[$follow]</a>";
		echo " <span>&rarr; 나를 친구로 추가한 회원이군요!</span>";
	}
	else
	{
		$follow = "나랑 친구할래?";
		echo " <a class='connect' href='members.php?add=".$row[0] . "'>[$follow]</a>";
	}

}
?>

			</div>

		</div>