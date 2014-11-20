		<div id="main">

<?php
require_once 'rnheader.php';


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

if (!empty($_FILES['uploadimages']['name']))
{

	$times=time();
	$filename= $user.'_'.$times.'.jpg';
	$saveto = 'images/'.$filename;

	move_uploaded_file($_FILES['uploadimages']['tmp_name'], $saveto);
	$typeok = TRUE;
	
	switch($_FILES['uploadimages']['type'])
	{
		case "image/gif":   $src = imagecreatefromgif($saveto); break;

		case "image/jpeg":  // Both regular and progressive jpegs
		case "image/pjpeg":	$src = imagecreatefromjpeg($saveto); break;

		case "image/png":   $src = imagecreatefrompng($saveto); break;

		default:			$typeok = FALSE; break;
	}
	
	if ($typeok)
	{
		list($w, $h) = getimagesize($saveto);
		$max = 100;
		$tw  = $w;
		$th  = $h;
		
		if ($w > $h && $max < $w)
		{
			$th = $max / $w * $h;
			$tw = $max;
		}
		elseif ($h > $w && $max < $h)
		{
			$tw = $max / $h * $w;
			$th = $max;
		}
		elseif ($max < $w)
		{
			$tw = $th = $max;
		}
		
		$tmp = imagecreatetruecolor($tw, $th);
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
		imageconvolution($tmp, array( // Sharpen image
							    array(-1, -1, -1),
							    array(-1, 16, -1),
							    array(-1, -1, -1)
						       ), 8, 0);
		imagejpeg($tmp, $saveto);
		imagedestroy($tmp);
		imagedestroy($src);
	}



	if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
	else $view = $user;

	if (isset($_POST['text']))
	{
		$title = sanitizeString($_POST['title']);
		$text = sanitizeString($_POST['text']);

		if ($text != "")
		{
			$pm = substr(sanitizeString($_POST['pm']),0,1);
			$time = time();
			queryMysql("INSERT INTO rnmessages VALUES(NULL,'$user', '$view', '$pm', $time, '$title', '$text', NULL ,'$filename')");
		}
	}
} else {
	$filename= NULL;
	if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
	else $view = $user;

	if (isset($_POST['text']))
	{
		$title = sanitizeString($_POST['title']);
		$text = sanitizeString($_POST['text']);

		if ($text != "")
		{
			$pm = substr(sanitizeString($_POST['pm']),0,1);
			$time = time();
			queryMysql("INSERT INTO rnmessages VALUES(NULL,'$user', '$view', '$pm', $time, '$title', '$text', NULL , NULL)");
		}

	}
	$times=$filename=$saveto=NULL;
}


if ($view != "")
{
	if ($view == $user)
	{
		$name1 = "Your";
		$name2 = "Your";
	}
	else
	{
		$name1 = "<a href='members.php?view=$view'>$view</a>'s";
		$name2 = "$view's";
	}



	if (isset($_GET['erase']))
	{
		$erase = sanitizeString($_GET['erase']);
		queryMysql("DELETE FROM rnmessages WHERE id=$erase AND recip='$user'");
	} else if (isset($_GET['accept']))
	{
		$accept = sanitizeString($_GET['accept']);
		queryMysql("UPDATE  rnmessages SET  `check` =  1 WHERE  id=$accept LIMIT 1");

	}
?>

			<div class="maincontents">
				<h3><?php echo "$view 의 Dream List"; ?> </h3>
<?php
	

	if($view==$user){


echo <<<_END
<form class='dreambox' method='post' action='messages.php?page=1' enctype='multipart/form-data'>
	<h2>꿈을 적으세요 이루어 집니다!</h2>
	<p>자! 이제 바라는 꿈을 적어보세요! 사진도 함께 올릴수 있답니다</p>
	<textarea class='boxtitle' name='title'>$title</textarea><br />
	<textarea class='boxtext' name='text'>$text</textarea><br />
	<span>공개</span><input type='radio' name='pm' value='0' checked='checked' />
	<span>비공개</span><input type='radio' name='pm' value='1' />
	<span>Image:</span><input type='file' name='uploadimages' size='14' maxlength='32' />
	<input type='submit' value='꿈나무의 저장' />
</form>
_END;

	}

?>


<?php

	
	$page=10*($_GET['page']-1);
	if(!isset($_GET['page']))
		$page=1;
?>


<?php

	if($user==$view)
		$query = "SELECT * FROM rnmessages WHERE auth='$view' ORDER BY time DESC LIMIT $page , 10";
	else if($user!=$view)
		$query = "SELECT * FROM rnmessages WHERE auth='$view' AND pm=0 ORDER BY time DESC LIMIT $page , 10";
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
				<div class="<?php $secret=""; if($row[3]==1) $secret=" secret"; else $secret=""; 
									  if($row[7]==1) 
										 echo 'messageboxtrue'. $secret;
									  else if(!$row[7])
										 echo 'messagebox'.$secret; ?>">
					<div class="about">
						<p class="autor">작성자 : <strong><?php echo "$row[1]"; ?></strong></p>
						<p class="date"><?php echo date('y년 m월 j일 G시i분s초', $row[4]); ?></p>
					</div>
					<div class="post">
<?php 
			if($user==$row[1])
			{
				echo '<a href="messages.php?view='.$view.'&accept='.$row[0].'&page=1"><img src="images/accept.gif" /></a>';
				echo '<a href="messages.php?view='.$view.'&erase='.$row[0].'&page=1"><img src="images/remove.png" /></a>';
			}
			if($row[8]=="")
				echo "";
			else 
				echo "<img src='images/$row[8]' alt='' width='200' />";
?>					

						<p class="style1"><?php if($row[5]==""){echo "제목 없음";} else {echo $row[5];} ?></p>
						<p class="style2"><?php if($row[6]==""){echo "제목 없음";} else {echo $row[6];} ?></p>
					</div>
				</div>

<?php
		}
	}
}

if (!$num) echo "<li>당신의 꿈을 입력해주세요!</li><br />";


?>

			</div>
			<div class="pagenumber">

<?php

	$query = "SELECT * FROM rnmessages WHERE auth='$view'";
	$result = queryMysql($query);
	$all_message = mysql_num_rows($result);


	$pagenum = (int) ($all_message/10 + 1);
	if(!isset($_GET['page']))
		$pagenow=1;
	else
		$pagenow=$_GET['page'];

	if(isset($pagenow)&& $pagenow>1)
		$prepage = $pagenow-1; else $prepage=1;
	if($pagenow!=$pagenum)
		$nextpage = $pagenow+1; else $nextpage=$pagenum;

	echo "<a href=messages.php?view=$view&page=$prepage><img class='page' src='images/prepage.png'></a>";
	for($i=0; $i<5; $i++)
	{
		$linknum=$pagenow+($i-2);
		if($linknum>=1 && $linknum<=$pagenum)
		{
			if($linknum==$pagenow) $now='page'; else $now='';
			echo "<span class=$now><a href='messages.php?view=$view&page=$linknum'>$linknum</a></span>";
		}
	}
	echo "<a href=messages.php?view=$view&page=$nextpage><img class='page' src='images/nextpage.png'></a>";
	echo "<p><span>페이지 : $pagenum page</span><span>총 List의 수 : $num</span>";

?>

			</div>
		</div>