<html>
	<head>
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
		<?php
        function generate_insert($username, $email, $password) {
            return "INSERT INTO `login-information` (`id`, `username`, `email`, `password`)
                VALUES (NULL, '$username', '$email', '$password');";
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
        $sql_insert = generate_insert($username, $email, $password);
        
        if (mysqli_multi_query($conn, $sql_insert)) {
            echo "New records created successfully";
        } else {
            echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
        }
        
        mysqli_close($conn);
        ?>
	</body>
</html>