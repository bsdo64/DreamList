<?php // login.php
require_once 'rnheader.php';


if (isset($_POST['user']))
{
	$user = sanitizeString($_POST['user']);
	$pass = sanitizeString($_POST['pass']);
	
	if ($user == "" || $pass == "")
	{
?>
			 <script>
				 alert("아이디와 비밀번호를 입력하세요");
			     location.href="index.php?view=<?php echo $user; ?>";
			 </script>

<?php
	}
	else
	{
		$query = "SELECT user,pass FROM rnmembers
				  WHERE user='$user' AND pass='$pass'";

		if (mysql_num_rows(queryMysql($query)) == 0)
		{
?>
			 <script>
				 alert("아이디와 비밀번호가 일치하지 않습니다.");
			     location.href="index.php?view=<?php echo $user; ?>";
			 </script>

<?php
		}
		else
		{
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
?>
			 <script>
			     location.href="index.php?view=<?php echo $user; ?>";
			 </script>

<?php
		}
	}
}
?>
			<div class="login">
<?php
if(isset($_SESSION['user']))
{
					$view = sanitizeString($_GET['view']);
					
					if ($view == $user) $name = "Your";
					else $name = "$view";
					
					echo "<h5>환영합니다! $user 님 </h5>";
					showProfile($user);
echo 
			'<ul id="navigation">
				 <li><a href="members.php">새로운 회원들</a></li>
				 <li><a href="friends.php">나만의 친구들</a></li>
				 <li><a href="messages.php?page=1">나의 꿈 리스트 올리기</a></li>
				 <li><a href="profile.php">프로필 수정하기</a></li>
				 <li><a href="logout.php">로그아웃</a></li>
			</ul>';
} else {

	$error = $user = $pass = "";

echo <<<_END
				<h5>Login Form</h5>
				<form method='post' action='index.php'>$error
					<span>Username</span><br />
					<input type='text' maxlength='16' name='user' value='$user' /><br />
					<span>Password</span><br />
					<input type='password' maxlength='16' name='pass' value='$pass' />
					<p class='login_button'><input type='image' src='images/login.gif' alt='' width='53' height='22'  value='Login' /></a></p>
				</form>
			<ul id="navigation">
				<li><a href="index.php">홈으로</a></li>
				<li><a href="about.php">My Dream List?</a></li>
				<li><a href="signup.php">가입하기</a></li>
			</ul>
_END;
}
?>
			</div>