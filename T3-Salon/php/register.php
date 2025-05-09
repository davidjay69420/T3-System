<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "t3salon";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify table existence
$tableCheck = $conn->query("SHOW TABLES LIKE 'customer'");
if ($tableCheck->num_rows == 0) {
    die("Error: Table 'customer' doesn't exist. Please create the table first.");
}

// Check if POST data is set
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : "";
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $contact_number = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : "";
    $address = isset($_POST['address']) ? trim($_POST['address']) : "";
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $password = isset($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_DEFAULT) : "";

    // Validate required fields
    if (empty($name) || empty($username) || empty($password) || empty($email) || empty($contact_number) || empty($address)) {
        die("Error: All fields are required!");
    }

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO customer (name, email, contact_number, address, username, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $contact_number, $address, $username, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #6e6563;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: #d0cece;
            width: 100%;
            max-width: 500px;
            padding: 40px 20px;
            border-radius: 2px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #222;
        }

        .form-group {
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .form-input::placeholder {
            font-style: italic;
        }

        .submit-button {
            background-color: white;
            color: #222;
            border: none;
            border-radius: 5px;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .submit-button:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">Registration Form</h1>

        <form action="register.php" method="POST">
            <div class="form-group">
                <input type="text" name="name" placeholder="Name" class="form-input" required>
                <input type="text" name="username" placeholder="Username" class="form-input" required>
                <input type="password" name="password" placeholder="Password" class="form-input" required>
                <input type="email" name="email" placeholder="Email" class="form-input" required>
                <input type="tel" name="contact_number" placeholder="Phone number" class="form-input" required>
                <input type="text" name="address" placeholder="Address" class="form-input" required>
                <button type="submit" class="submit-button">Create account</button>
            </div>
        </form>
    </div>
</body>
</html>