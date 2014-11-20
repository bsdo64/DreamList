<?php
require_once 'rnheader.php';


	$query = "SELECT * FROM rnmessages WHERE pm=0";
	$result = queryMysql($query);
	$all_message = mysql_num_rows($result);
	
?>
			
			<div class="pagenumber">

<?php

	$pagenum = (int) ($all_message/10 + 1);
	if(!isset($_GET['page']))
		$pagenow=1;
	else
		$pagenow=$_GET['page'];

	if(isset($pagenow)&& $pagenow>1)
		$prepage = $pagenow-1; else $prepage=1;
	if($pagenow!=$pagenum)
		$nextpage = $pagenow+1; else $nextpage=$pagenum;

	echo "<a href=index.php?page=$prepage><img class='page' src='images/prepage.png'></a>";
	for($i=0; $i<5; $i++)
	{
		$linknum=$pagenow+($i-2);
		if($linknum>=1 && $linknum<=$pagenum)
		{
			if($linknum==$pagenow) $now='page'; else $now='';
			echo "<span class=$now><a href='index.php?page=$linknum'>$linknum</a></span>";
		}
	}
	echo "<a href=index.php?page=$nextpage><img class='page' src='images/nextpage.png'></a>";
	echo "<p><span>페이지 : $pagenum page</span><span>총 List의 수 : $all_message</span>";

?>

			</div>

<?php
	$page=10*($_GET['page']-1);
	if(!isset($_GET['page']))
		$page=1;
	$query = "SELECT * FROM rnmessages WHERE pm=0 ORDER BY time DESC LIMIT $page , 10";
	$result = queryMysql($query);
	$num = mysql_num_rows($result);

	for ($j = 0 ; $j < $num ; ++$j)
	{
		$row = mysql_fetch_row($result);

		if ($row[3] == 0 ||
		    $row[1] == $user ||
		    $row[2] == $user)
		{
			
?>

			<div class="maincontents1">
				<h2><?php echo "<a href='messages.php?view=$row[1]'>$row[1]'s List</a> "; ?> </h2>
				<div class="about">
					<p class="autor">작성자 : <strong><?php echo "$row[1]"; ?></strong></p>
					<p class="date"><?php echo date('y년 m월 j일 G시i분s초', $row[4]); ?></p>
				</div>
				<div class="post">
<?php 
			if($row[8]=="")
				echo "";
			else 
				echo "<img src='images/$row[8]' alt='' width='200' />";

?>
					<p class="style1"><?php if($row[5]==""){echo "제목 없음";} else {echo $row[5];} ?></p>
					<p><?php echo $row[6]; ?></p>

				</div>
			</div>

<?php
		}
	}

if (!$num) echo "<li>No messages yet</li><br />";
?>

			<div class="pagenumber">

<?php

	$pagenum = (int) ($all_message/10 + 1);
	if(!isset($_GET['page']))
		$pagenow=1;
	else
		$pagenow=$_GET['page'];

	if(isset($pagenow)&& $pagenow>1)
		$prepage = $pagenow-1; else $prepage=1;
	if($pagenow!=$pagenum)
		$nextpage = $pagenow+1; else $nextpage=$pagenum;

	echo "<a href=index.php?page=$prepage><img class='page' src='images/prepage.png'></a>";
	for($i=0; $i<5; $i++)
	{
		$linknum=$pagenow+($i-2);
		if($linknum>=1 && $linknum<=$pagenum)
		{
			if($linknum==$pagenow) $now='page'; else $now='';
			echo "<span class=$now><a href='index.php?page=$linknum'>$linknum</a></span>";
		}
	}
	echo "<a href=index.php?page=$nextpage><img class='page' src='images/nextpage.png'></a>";
	echo "<p><span>페이지 : $pagenum page</span><span>총 List의 수 : $all_message</span>";

?>

			</div>

