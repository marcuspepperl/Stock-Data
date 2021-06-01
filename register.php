<html>
	<head>
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
    		<?php
    		function connect_to_database() {
    		    // Get basic database information
    		    $serverName = "localhost";
    		    $serverUsername = "root";
    		    $serverPassword = "root";
    		    $databaseName = "stock-data";
    		    
    		    $conn = mysqli_connect($serverName, $serverUsername, $serverPassword, $databaseName);
    		    
    		    if (!$conn) {
    		        die("Connection failed: " . mysqli_connect_error());
    		    }
    		    echo "Connected Successfully<br>";
    		    
    		    return $conn;
    		}
    		
            function generate_login_insert($username, $email, $password) {
                return "INSERT INTO `login-information` (`id`, `username`, `email`, `password`)
                    VALUES (NULL, '$username', '$email', '$password');";
            }
            
            function generate_preferences_insert($username) {
                return "INSERT INTO `user-preferences` (`username`, `prefColor`, `prefValue`)
                    VALUES ('$username', NULL, NULL)";
            }
            
            function generate_email_select($email) {
                return "SELECT * FROM `login-information` WHERE `email` LIKE '$email'";
            }
        
            function generate_username_select($username) {
                return "SELECT * FROM `login-information` WHERE `username` LIKE '$username'";
            }
            
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            
            session_start();
            
            if (array_key_exists('username', $_SESSION)) {
                echo "You are already logged in!<br>";
                die();
            }
            
            $conn = connect_to_database();
            
            // Gather form data
            $username = $email = $password = $password2 = "";
            
            $username = test_input($_GET['username']);
            $email = test_input($_GET['email']);
            $password = test_input($_GET['password']);
            $password2 = test_input($_GET['password2']);
        
            $usernameLength = strlen($username);
            $passwordLength = strlen($password);
            
            // Validate form data
            if ($usernameLength > 200) {
                echo "Your username is too long!";
                mysqli_close($conn);
                die();
            }
            if ($passwordLength < 7 || $passwordLength > 200) {
                echo "Your password is an improper length!";
                mysqli_close($conn);
                die();
            }
            if ($password != $password2) {
                echo "Your passwords do not match!";
                mysqli_close($conn);
                die();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Your email is not formatted correctly!";
                mysqli_close($conn);
                die();
            }
            
            // Make sure email and username are distinct
            $sql_email_select = generate_email_select($email);
            
            $email_query = mysqli_query($conn, $sql_email_select);
            
            if (mysqli_affected_rows($conn) > 0) {
                echo "Your email is already being used!";
                mysqli_close($conn);
                die();
            }
            
            $sql_username_select = generate_username_select($username);
            
            $username_query = mysqli_query($conn, $sql_username_select);
            
            if (mysqli_affected_rows($conn) > 0) {
                echo "Your username is already being used!";
                mysqli_close($conn);
                die();
            }
            
            // Add the user to the database
            $sql_login_insert = generate_login_insert($username, $email, $password);
            
            if (mysqli_query($conn, $sql_login_insert)) {
                echo "Login information created sucessfully!";
            } else {
                echo "Error: " . $sql_login_insert . "<br>" . mysqli_error($conn);
                mysqli_close($conn);
                die();
            }
            
            $sql_preferences_insert = generate_preferences_insert($username);
            
            if (mysqli_query($conn, $sql_preferences_insert)) {
                echo "Preferences information created sucessfully!";
            } else {
                echo "Error: " . $sql_preferences_insert . "<br>" . mysqli_error($conn);
                mysqli_close($conn);
                die();
            }
            
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            
            $_SESSION['prefColor'] = null;
            $_SESSION['prefValue'] = null;
            
            // Set cookies to default values
            setcookie('prefColor', null, time() + (86400 * 30), "/");
            setcookie('prefValue', null, time() + (86400 * 30), "/");
            
            
            mysqli_close($conn);
        ?>
	</body>
</html>