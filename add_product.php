<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.html');
    exit();
}

$conn = new mysqli("localhost", "root", "", "harmony_care");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $picture = $_POST["picture"];
    $stock = $_POST["stock"];
    $price = $_POST["price"];
    $prescription_required = isset($_POST["prescription_required"]) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO products (name, picture, stock, price, prescription_required) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidi", $name, $picture, $stock, $price, $prescription_required);

    if ($stmt->execute()) {
        echo "Product added successfully. <a href='admin_dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>