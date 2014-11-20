		<div id="main">

			<div class="maincontents">

<?php
include_once 'rnheader.php';

if (!isset($_SESSION['user']))
{
?>
			 <script>
				alert("먼저 가입후 로그인하시기 바랍니다");
			    location.href="index.php";
			 </script>

<?php
}
$user = $_SESSION['user'];

echo "<h3>프로필을 수정하세요!</h3>";

if (isset($_POST['text']))
{
	$text = sanitizeString($_POST['text']);
	$text = preg_replace('/\s\s+/', ' ', $text);
	
	$query = "SELECT * FROM rnprofiles WHERE user='$user'";
	if (mysql_num_rows(queryMysql($query)))
	{
		queryMysql("UPDATE rnprofiles SET text='$text' 
				    where user='$user'");
	}
	else
	{
		$query = "INSERT INTO rnprofiles VALUES('$user', '$text')";
		queryMysql($query);
	}
}
else
{
	$query  = "SELECT * FROM rnprofiles WHERE user='$user'";
	$result = queryMysql($query);
	
	if (mysql_num_rows($result))
	{
		$row  = mysql_fetch_row($result);
		$text = stripslashes($row[1]);
	}
	else $text = "";
}

$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

if (isset($_FILES['image']['name']))
{
	$saveto = "images/profile/$user.jpg";
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
	$typeok = TRUE;
	
	switch($_FILES['image']['type'])
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
		$max = 200;
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
}
showProfile($user);
?>

<form class='profilebox' method='post' action='<?php $_SERVER['PHP_SELF']; ?>' enctype='multipart/form-data'>
<p>프로필을 수정해보세요! 다른 회원들이 관심을 가지게 된답니다</p>
<textarea name='text' cols='40' rows='3'><?php echo $text; ?></textarea>
<p>Image:</p><input type='file' name='image' size='14' maxlength='32' />
<input type='submit' value='Save Profile' />
</form>


			</div>

		</div>