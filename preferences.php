<html>
	<head>
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
		<?php 
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
               
                setcookie('prefColor', $color, time() + (86400 * 30), "/");
                setcookie('prefValue', $value, time() + (86400 * 30), "/");
                
                echo "Cookies have been set";
            }
            
            handle_preferences();
        ?>
	</body>
</html>