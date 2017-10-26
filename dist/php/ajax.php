<?php

	include "pass.php";

//------------------------------------------------
	$servername = $GLOBALS['servername'];
	$username 	= $GLOBALS['username'];
	$password 	= $GLOBALS['password'];
	$dbname 		= $GLOBALS['dbname'];

	$user 		= $_POST["ajax_username"];
	$pass 		= $_POST["ajax_password"];
	$token		= $_POST["ajax_tokenkey"];

	$md5_user 	= md5($user);
	$md5_pass 	= md5($pass);
	$md5_token 	= md5($token);

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn,"utf8");

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// SQL qwestion..
	$sql = "SELECT ID, User FROM users WHERE User = '$md5_user' AND Password = '$md5_pass'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // push new token
	  $sql = 	"UPDATE users SET Token = '$md5_token' WHERE User = '$md5_user' AND Password = '$md5_pass'";
		$conn->query($sql);

	  // status
		$data = TRUE;
	} else {
		$data = FALSE;
	}

	$conn->Close();

	print json_encode($data);

?>