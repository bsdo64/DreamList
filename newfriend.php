				<div class="block">
					<h5>새로운 친구들!</h5>

<?php
include_once 'rnheader.php';

$result = queryMysql("SELECT user FROM rnmembers ORDER BY time DESC LIMIT 4");
$num = mysql_num_rows($result);
echo "<ul>";

for ($j = 0 ; $j < $num ; ++$j)
{
	$row = mysql_fetch_row($result);
	if ($row[0] == $user) continue;
	
	echo "<li><a href='members.php?view=$row[0]'>$row[0]</a></li>";

}
?>

				</div>