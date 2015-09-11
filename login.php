<?php
//If the page was gotten to by clicking the submit button then run the login() function
if($_POST){
    if(isset($_POST['Submit'])){
        login();
	}
}

function generate_uid() {
  $str1 = str_pad(dechex(mt_rand()), 8, "0", STR_PAD_LEFT);
  $str2 = str_pad(dechex(mt_rand()), 8, "0", STR_PAD_LEFT);
  return $str1 . $str2;
}

function login() {
	//make sure user inputted a username
	if(isset($_POST['username']) && $_POST['username'] != "") {

		//If no cookie is found on device add cookie
		$expire=time()+60*60*24*30;//however long you want (current expires in 30 days)
    $new_uniqueID = generate_uid();
		setcookie('uniqueID', $new_uniqueID , $expire);

		$username = $_POST['username'];
		echo 'username: ' . $username . '</br>';

		//database server
		define('db_server', 'localhost');

		//user, password, and database variables
		$db_user = 'dro';
		$db_password = 'password';
		$db_dbname = 'saltybet';

		//connect to the database server
		$db = mysql_connect(db_server, $db_user, $db_password);
		if ($db) {
			//select database name
			mysql_select_db($db_dbname);

			//check to see if user exists
			$user_exists_query = 'SELECT * FROM users WHERE username=\'' . $username . '\';';
			echo $user_exists_query . '</br>';
			$user_exists = mysql_query($user_exists_query);
			$unique_id_exists_query = 'SELECT * FROM users WHERE uniqueId=\'' . $new_uniqueID . '\';';
			echo $unique_id_exists_query . '</br>';
			$unique_id_exists = mysql_query($unique_id_exists_query);

			echo 'Username found? (1=yes, 0=no): ' . mysql_num_rows($user_exists) . '</br>';
			echo 'UniqueID found? (1=yes, 0=no): ' . mysql_num_rows($unique_id_exists) . '</br>';

			if(mysql_num_rows($user_exists)==0 && mysql_num_rows($unique_id_exists) == 0) {
				//Insert user by uniqueID into users table
				$insert_query = "INSERT INTO users (uniqueId,username,saltyBucks)
              VALUES ('" . $new_uniqueID . "','" . $username . "',10);";
				echo $insert_query . '</br>';
				mysql_query($insert_query, $db) or die("Error: ".mysql_error());

				//Redirect back to test.php
				header("Location: user.php");
				die();
			} else if(mysql_num_rows($unique_id_exists)!=0) {
				echo 'Device is already registered';

				//Redirect back to test.php
				header("Location: user.php");
				die();
			} else if(mysql_num_rows($user_exists)!=0) {
				echo 'Username already exists';
			} else {
				echo 'SHOULD NOT GET HERE!';
			}
		}
		//close database connection
		mysql_close($db);
	} else {
		echo 'Please input a username </br>';
	}
}
?>

<h1>Welcome to Salty Bet Dream Casino</h1>
<form action="login.php" method="POST">
<table>
	<tr>
		<td>Pick a username: <input type="text" id="username" name="username"/></td>
	</tr>
	<tr>
		<td><input type="submit" name="Submit" value="Submit"/></td>
	</tr>
</table>
</form>
