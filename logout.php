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
		    
    		function generate_pref_update($username, $prefColor, $prefValue) {
    		    $sql_command = "UPDATE `user-preferences` SET ";
    		    $exists = false;
    		    if ($prefColor != null) {
    		        $sql_command .= "`prefColor` = '$prefColor'";
    		        $exists = true;
    		    }
    		    if ($prefValue != null) {
    		        if ($exists) {
    		            $sql_command .= ", ";
    		        }
    		        $sql_command .= "`prefValue` = $prefValue";
    		        $exists = true;
    		    }
    		    $sql_command .= " WHERE `username` LIKE '$username'";
    		    
    		    return $sql_command;
    		}
    		
    		function handle_logout() {
    		    
    		    session_start();
    		    
    		    if (!isset($_SESSION['username'])) {
    		        echo "You are not logged in!<br>";
    		        die();
    		    }
    		    
    		    // Connect to the database
    		    $conn = connect_to_database();
    		    
    		    $sql_update = generate_pref_update($_SESSION['username'], $_SESSION['prefColor'], $_SESSION['prefValue']);
    		    
    		    echo $sql_update . "<br>";
    		    
    		    mysqli_query($conn, $sql_update);
    		    
    		    mysqli_close($conn);
    		    
    		    echo "Ending current session!<br>";
    		    session_unset();
    		    session_destroy();
    		    setcookie("username", "", time() - 3600, "/");
    		    setcookie("prefColor", null, time() - 3800, "/");
    		    setcookie("prefValue", null, time() - 3600, "/");
    		}
    		handle_logout();
        ?>
	</body>
</html>