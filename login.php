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
		
            function generate_login_select($username, $password) {
                return "SELECT * FROM `login-information` WHERE `username` LIKE '$username' AND `password` LIKE '$password'";
            }
            
            function generate_pref_select($username) {
                return "SELECT * FROM `user-preferences` WHERE `username` LIKE '$username'";
            }
            
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            
            function handle_login() {
                session_start();
                
                if (isset($_COOKIE['prefColor'])) {
                    echo "The preferred color is " . $_COOKIE['prefColor'];
                }
                
                if (array_key_exists('username', $_SESSION)) {
                    echo "You are already logged in!<br>";
                    die();
                }
                
                $conn = connect_to_database();
                
                // Gather form data
                $username = $password = "";
                
                $username = test_input($_GET['username']);
                $password = test_input($_GET['password']);
                
                // Make sure email and username are distinct
                $sql_login_select = generate_login_select($username, $password);
                
                $login_query = mysqli_query($conn, $sql_login_select);
                
                if (mysqli_affected_rows($conn) == 0) {
                    echo "Invalid username or password!";
                    mysqli_close($conn);
                    die();
                }
                // Get the user data
                $user = mysqli_fetch_assoc($login_query);
                
                // Set session variables
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $user['email'];
                $_SESSION['password'] = $password;
                
                // Get preferences from database
                $sql_pref_select = generate_pref_select($username);
    
                $pref_query = mysqli_query($conn, $sql_select);
    
                if (mysqli_affected_rows($conn) == 0) {
                    echo "Server error!";
                    mysqli_close();
                    die();
                }
                
                $pref = mysqli_fetch_assoc($pref_query);
                
                $_SESSION['prefColor'] = $pref['prefColor'];
                $_SESSION['prefValue'] = $pref['prefValue'];
                
                setcookie('prefColor', $pref['prefColor'], time() + (86400 * 30), "/");
                setcookie('prefValue', $pref['prefValue'], time() + (86400 * 30), "/");
                setcookie('username', $username, time() + (86400 * 30), "/");
                
                mysqli_close($conn);
                
                echo "Initialized all session variables and cookies!";
            }
            
            handle_login();
        ?>
	</body>
</html>