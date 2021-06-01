<html>
	<head>
		<link rel="stylesheet" href="index.css">
	</head>
	<body>
		<?php
        function generate_insert($username, $email, $password) {
            return "INSERT INTO `login-information` (`id`, `username`, `email`, `password`)
                VALUES (NULL, '$username', '$email', '$password');";
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
        $color = "";
        $color = test_input($_GET['color']);
        
        echo "The username is " . $_SESSION['username'];
        echo "The color is " . $color;
        
        mysqli_close($conn);
        ?>
	</body>
</html>