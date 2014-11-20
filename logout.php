<?php // rnlogout.php
include_once 'rnheader.php';
echo "<h3>Log out</h3>";

if (isset($_SESSION['user']))
{
	destroySession();
?>
			 <script>
			     location.href="index.php";
			 </script>

<?php
}
else echo "You are not logged in";
?>
