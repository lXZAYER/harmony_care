

<?php
session_start();
$conn = new mysqli("localhost", "root", "", "harmony_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Successful</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="navbar">
    <h1>Harmony Care</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="checkout.php">Cart 🛒</a>
      <?php if (isset($_SESSION['user'])): ?>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</span>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.html">Login</a>
      <?php endif; ?>
      <a class="nav-link" href="orders.php">My Order</a>
    </nav>
  </header>

<main class="success-container" style="text-align:center; padding:50px;">
    <h2>✅ Thank you for your purchase!</h2>
    <p>Your order has been placed successfully.</p>
    <a href="index.php" class="btn" style="margin-top:20px;">Continue Shopping</a>
</main>

<footer>
    <p>&copy; 2025 Harmony Care. All rights reserved.</p>
</footer>
</body>
</html>
