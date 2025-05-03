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
    $originalName = $conn->real_escape_string($_POST['original_name']);
    $productName = $conn->real_escape_string($_POST['product_name']);
    $stock = (int) $_POST['stock'];
    $price = (float) $_POST['price'];
    $requiresPrescription = isset($_POST['requires_prescription']) ? 1 : 0;

    $checkSql = "SELECT * FROM products WHERE name = '$originalName'";
    $result = $conn->query($checkSql);

    if ($result && $result->num_rows > 0) {
        $updateSql = "UPDATE products 
                      SET name = '$productName',
                          stock = $stock,
                          price = $price,
                          prescription_required = $requiresPrescription
                      WHERE name = '$originalName'";

        if ($conn->query($updateSql) === TRUE) {
            echo "<h2>✅ Product '$productName' updated successfully!</h2>";
        } else {
            echo "<h2>❌ Error updating product: " . $conn->error . "</h2>";
        }
    } else {
        echo "<h2>⚠️ Product not found in the database.</h2>";
    }
} else {
    echo "<h2>⚠️ No form data submitted.</h2>";
}

$conn->close();

echo '<p><a href="admin_dashboard.php">🔙 Back to Admin Dashboard</a></p>';
?>
