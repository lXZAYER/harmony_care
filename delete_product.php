<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "harmony_care";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $conn->real_escape_string($_POST['product_name']);

    $checkSql = "SELECT * FROM products WHERE name = '$productName'";
    $result = $conn->query($checkSql);

    if ($result && $result->num_rows > 0) {
        $deleteSql = "DELETE FROM products WHERE name = '$productName'";

        if ($conn->query($deleteSql) === TRUE) {
            echo "<h2>🗑️ Product '$productName' deleted successfully!</h2>";
        } else {
            echo "<h2>❌ Error deleting product: " . $conn->error . "</h2>";
        }
    } else {
        echo "<h2>⚠️ Product '$productName' not found in the database.</h2>";
    }
} else {
    echo "<h2>⚠️ No form data submitted.</h2>";
}

$conn->close();

echo '<p><a href="admin_dashboard.php">🔙 Back to Admin Dashboard</a></p>';
?>
