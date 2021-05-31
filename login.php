<html>
	<head>
		<link rel="stylesheet" href="index.css">
	</head>
	<body>
		<?php
        function generate_select($username, $password) {
            return "SELECT * FROM `login-information` WHERE `username` LIKE '$username' AND `password` LIKE '$password'";
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
        $username = $password = "";
        
        $username = test_input($_GET['username']);
        $password = test_input($_GET['password']);
        
        // Make sure email and username are distinct
        $sql_select = generate_select($username, $password);
        
        $login_query = mysqli_query($conn, $sql_select);
        
        if (mysqli_affected_rows($conn) == 0) {
            echo "Invalid username or password!";
            mysqli_close($conn);
            die();
        }
        
        // Print email back to user
        $user = mysqli_fetch_assoc($login_query);
        echo "Your email is " . $user['email'];
        
        // Set preferences
        $_SESSION['background-color'] = "red";
        
        mysqli_close($conn);
        ?>
	</body>
</html>