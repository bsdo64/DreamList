		<div id="main">
			<div class="maincontents">
<?php // rnsignup.php
include_once 'rnheader.php';

echo <<<_END
<script>
function checkUser(user)
{
	if (user.value == '')
	{
		document.getElementById('info').innerHTML = ''
		return
	}

	params  = "user=" + user.value
	request = new ajaxRequest()
	request.open("POST", "rncheckuser.php", true)
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	request.setRequestHeader("Content-length", params.length)
	request.setRequestHeader("Connection", "close")
	
	request.onreadystatechange = function()
	{
		if (this.readyState == 4)
		{
			if (this.status == 200)
			{
				if (this.responseText != null)
				{
					document.getElementById('info').innerHTML =
						this.responseText
				}
				else alert("Ajax error: No data received")
			}
			else alert( "Ajax error: " + this.statusText)
		}
	}
	request.send(params)
}

function ajaxRequest()
{
	try
	{
		var request = new XMLHttpRequest()
	}
	catch(e1)
	{
		try
		{
			request = new ActiveXObject("Msxml2.XMLHTTP")
		}
		catch(e2)
		{
			try
			{
				request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch(e3)
			{
				request = false
			}
		}
	}
	return request
}
</script>
<h3>지금 가입하세요!</h3>
_END;

$error = $user = $pass = "";
if (isset($_SESSION['user'])) destroySession();

if (isset($_POST['user']))
{
	$user = sanitizeString($_POST['user']);
	$pass = sanitizeString($_POST['pass']);
	
	if ($user == "" || $pass == "")
{

?>
			 <script>
				alert("빈칸을 모두 채워주세요");
			    location.href="index.php?";
			 </script>

<?php

}
	else
	{
		$query = "SELECT * FROM rnmembers WHERE user='$user'";

		if (mysql_num_rows(queryMysql($query)))

{
?>
			 <script>
				alert("아이디와 비번이 다릅니다");
			    location.href="index.php?";
			 </script>

<?php
}
		else
		{
			$time=time();
			$query = "INSERT INTO rnmembers VALUES('$user', '$pass', '$time')";
			queryMysql($query);
		}
?>
			 <script>
				alert("축하합니다! 가입이 완료되었습니다.");
			    location.href="index.php?view=<?php echo $user; ?>";
			 </script>

<?php
	}
}

echo <<<_END
<form method='post' action='signup.php'>$error
<ul class="polls">
	<li><p>아이디 : <span id='info'></span></p><input type='text' maxlength='16' name='user' value='$user' onBlur='checkUser(this)' /></li>
	<li><p>비밀번호 : </p><input type='text' maxlength='16' name='pass' value='$pass' /></li>
	<input type='submit' value='Signup' />
</ul>
</form>
_END;
?>
			</div>
		</div>