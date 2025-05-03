<?php
session_start();
$conn = new mysqli("localhost", "root", "", "harmony_care");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION["user"] = $row["username"];
        $_SESSION["role"] = $row["role"];
        $_SESSION['user_id'] = $row['id'];

        if ($row["role"] === "admin") {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        echo "Invalid login credentials.";
    }

    $stmt->close();
}
$conn->close();
?>