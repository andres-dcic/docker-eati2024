<?php
// Pasarle el nombre del contenedor que corre mysql
$host = 'db';

// Database use name
$user = 'mysql_user';

//database user password
$pass = 'mysql_password';

// check the MySQL connection status
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  echo "<h1> Connected to MySQL server successfully! </h1> \n";
}
?>

