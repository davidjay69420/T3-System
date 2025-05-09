<?php
session_start(); // Start session for user authentication

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "t3salon";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data exists
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id']; // Storing user ID for future use
                echo "Login successful!";
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User not found.";
        }

        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>T3 Salon - Admin Login</title>
  <style>
    body { background: pink; font-family: sans-serif; }
    .login-box { background: #fff; padding: 20px; max-width: 300px; margin: 50px auto; border-radius: 10px; }
    input, button { width: 100%; margin: 10px 0; padding: 10px; }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Admin Login</h2>
    <form action="login.php" method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
