<?php

	include "../pass.php";

//------------------------------------------------
	$servername = $GLOBALS['servername'];
	$username 	= $GLOBALS['username'];
	$password 	= $GLOBALS['password'];
	$dbname 		= $GLOBALS['dbname'];

	$user 		= $_POST["username"];
	$pass 		= $_POST["password"];
	$flag 		= $_POST["flag"];

	$md5_user = md5($user);
	$md5_pass = md5($pass);

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn,"utf8");

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	//SQL qwestion..
	if ($flag) {
		$sql = 	"CREATE TABLE `$dbname`.`users` ( `id` INT NULL DEFAULT NULL AUTO_INCREMENT ,
																					 `User` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
																					 `Password` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
																					 `Token` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
																					 INDEX (`id`)) ENGINE = InnoDB";
		$conn->query($sql);
		echo "Table users was create".'<br>';
		
		$sql = 	"CREATE TABLE `$dbname`.`rubles` ( `id` INT NULL DEFAULT NULL AUTO_INCREMENT ,
																						`User` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
																						`MESSAGE` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
																						`flag_read` BOOLEAN NULL ,
																						INDEX (`id`)) ENGINE = InnoDB";
		$conn->query($sql);
		echo "Table rubles was create".'<br>';
	} else {
		$sql = 	"INSERT INTO users (User, Password) VALUES ('$md5_user', '$md5_pass')";
		$conn->query($sql);
		echo "User was ADD";
	}

	$conn->Close();

?>