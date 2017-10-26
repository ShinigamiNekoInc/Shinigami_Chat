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
	$comand		= $_POST["ajax_command"];
	$message 	= $_POST["ajax_message"];

	$md5_user 	= md5($user);
	$md5_pass 	= md5($pass);
	$md5_token 	= md5($token);

	$base64_message 	= base64_encode($message);

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn,"utf8");

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	//SQL qwestion..
	$sql = "SELECT User FROM users WHERE User = '$md5_user' AND Password = '$md5_pass' AND Token = '$md5_token'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$autorization = TRUE;
	} else {
		$autorization = FALSE;
	}

	if ($autorization) {

		switch ($comand) {
			case 'w':
				# code...
					$sql = 	"INSERT INTO rubles (User, Message, flag_read) VALUES ('$md5_user', '$base64_message', FALSE)";
					$conn->query($sql);
				break;
			
			case 'r':
				# code...
					$flag = TRUE;
					$sql = 	"SELECT id, MESSAGE FROM rubles WHERE flag_read='false' AND (NOT User='$md5_user') ORDER BY id ASC";
					
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {//foreach element in associative array
							try {
								$data[] = base64_decode($row["MESSAGE"]);
							} catch (Exception $e) {
							  $data[] = $row["MESSAGE"];
							}
							$id = $row["id"];
							//create sql query for confirm messages
							if ($flag) {
								$confirm_message = "UPDATE rubles SET flag_read=TRUE WHERE id='$id'";
								$flag = FALSE;
							} else {
								$confirm_message .= "OR id='$id'";
							}
						}
						$conn->query($confirm_message); //confirm as read all messages
						print json_encode($data);
					} else {
						$data = FALSE;
						print json_encode($data);
					}
				break;
			
			case 'h':
				# code...
					$sql = "SELECT User, MESSAGE FROM rubles WHERE flag_read = true OR User = '$md5_user' ORDER BY id DESC limit 10";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {//foreach element in associative array
							
							//decode message
							try {
								$message = base64_decode($row["MESSAGE"]);
							} catch (Exception $e) {
							  $message = $row["MESSAGE"];
							}
							
							//add to stack
							if ($md5_user == $row["User"]) {
								$data[] = ["user" => TRUE, "message" => $message];
							} else {
								$data[] = ["user" => FALSE, "message" => $message];
							}

						}
						print json_encode($data);
					} else {
						$data = FALSE;
						print json_encode($data);
					}
				break;

			default:
				# code...
				break;
		}
	} else {
		$data = ["authentication" => TRUE];
		print json_encode($data);
	}

	$conn->Close();

		//$data = base64_encode($message);
		//$data = base64_decode($data);

?>