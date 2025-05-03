<?php
session_start();

$conn = new mysqli("localhost", "root", "", "harmony_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}
$conn->close();

if (!$product) {
    echo "<p>Product not found.</p><a href='index.php'>Back to Home</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['name']); ?> - Details</title>
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

  <main class="product-detail-container">
    <div class="product-detail">
    <img src="<?php echo htmlspecialchars($product['picture']); ?>" alt="Product Image" class="product-image" style="max-width: 250px; max-height: 250px;">

    <div class="product-info">
      <h2><?php echo htmlspecialchars($product['name']); ?></h2>
      <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
      <p class="stock">Stock Available: <?php echo htmlspecialchars($product['stock']); ?></p>
      <form method="POST" action="add_to_cart.php">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $product['stock']; ?>" value="1">        <button type="submit" class="btn">Add to Cart</button>
      </form>
      <button onclick="window.open('error-help.html','Help','width=400,height=400')" class="btn help-btn">Help</button>
    </div>
    </div>
  </main>

  <footer>
  <div class="footer-links">
      <a href="contact.html" class="btn">Contact Us</a>
      <a href="error-help.html" class="footer-btn">Help</a>
    </div>
    <p>&copy; 2025 Harmony Care. All rights reserved.</p>
  </footer>
</body>
</html>
