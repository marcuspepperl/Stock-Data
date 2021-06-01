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
            
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            
            function handle_peferences() {
                
                session_start();
                
                // Gather form data
                $color = null;
                $value = null;
                $color = test_input($_GET['color']);
                $value = test_input($_GET['value']);
                
                if (isset($_SESSION['username'])) {
                    $_SESSION['prefColor'] = $color;
                    $_SESSION['prefValue'] = $value;
                }
               
                $_COOKIE['prefColor'] = $color;
                $_COOKIE['prefValue'] = $value;
            }
            
            handle_preferences();
        ?>
	</body>
</html>