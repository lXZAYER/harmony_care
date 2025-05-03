<?php
session_start();

$show_login_popup = false;

if (!isset($_SESSION['user_id'])) {
    $show_login_popup = true;
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "harmony_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$stmt = $conn->prepare("
  SELECT 
    o.order_id,
    o.order_date,
    o.total_amount,
    GROUP_CONCAT(CONCAT(oi.product_name, ' (', oi.quantity, ')') SEPARATOR ', ') AS products
  FROM orders o
  JOIN order_items oi ON o.order_id = oi.order_id
  WHERE o.user_id = ?
  GROUP BY o.order_id
  ORDER BY o.order_date DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Orders - Harmony Care</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
<header class="navbar">
  <h1>Harmony Care</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="checkout.php">Cart 🛒</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<main class="order-history-container">
<?php if ($show_login_popup): ?>
<div id="loginPopup" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
     background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 1000;">
  <div style="background: white; padding: 30px; border-radius: 10px; text-align: center; max-width: 400px;">
    <h2>Login Required</h2>
    <p>You need to log in to view your orders.</p>
    <div style="margin-top: 20px;">
      <a href="login.html" style="display: inline-block; margin: 5px; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Login</a>
      <a href="index.php" style="display: inline-block; margin: 5px; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;">Return to Home</a>
    </div>
  </div>
</div>
<?php endif; ?>
  <h2>My Orders</h2>
  <table class="order-history-table">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Total</th>
        <th>Products</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['order_id']) ?></td>
        <td><?= htmlspecialchars($row['order_date']) ?></td>
        <td><?= htmlspecialchars($row['total_amount']) ?> $</td>
        <td><?= htmlspecialchars($row['products']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>

<footer>
  <p>© 2025 Harmony Care. All rights reserved.</p>
</footer>
</body>
</html>

<?php $conn->close(); ?>