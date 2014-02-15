<?php 
$title_tag = 'Log In';

require 'page_header.php';

if (isset($_POST['username']) && isset($_POST['password'])) //user is trying to log in (clicked Log In button)
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	$query = "	select user_name, first_name, last_name
				from nr_users
				where user_name = '$username' and password = sha1('$password');";

	require 'db_connect.php';

	$result = mysqli_query($connect, $query);

	if (mysqli_num_rows($result) == 0) // no match
	{
		$error = "User name or password incorrect.";
	}
	else
	{
		mysqli_close($connect); // close mysql connection

		$i=0;
		while ($row = mysqli_fetch_array($result)) // get each row as an array
		$data[$i++] = $row; // store row in 2 dim array

		foreach($data as $row)
		{
			$_SESSION['username'] = $row[0];
			$_SESSION['firstname'] = $row[1];
			$_SESSION['lastname'] = $row[2];       
		}

		header('Location: project.php');
	}

	mysqli_close($connect); // close mysql connection
}
?>
		<h4>You must log in to use this site.</h4>
		<div class="login">
			<form method="post" action="login.php">
				<fieldset>
					<legend>Please log in:</legend>
					<table class="login">
						<tr>
							<td><label>User Name: </label>
							</td><td><input type="text" name="username"></td>
						</tr>
						<tr>
							<td><label>Password: </label></td>
							<td><input type="password" name="password"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" value = "Log In"></td>
						</tr>	
						<tr>
							<td>&nbsp;</td>
							<td class="errors"><?php echo $error; ?></td>
						</tr>
					</table>
				</fieldset>
			</form>
		</div>
	</body>
</html>