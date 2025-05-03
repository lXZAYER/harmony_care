<?php
session_start();
include 'db_connect.php';
$conn = new mysqli("localhost", "root", "", "harmony_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
  $show_login_popup = true;
}
include("connect.php");

$products_result = $conn->query("SELECT * FROM products");
$products = $products_result ? $products_result->fetch_all(MYSQLI_ASSOC) : [];

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user_id']; 
    $orders_result = $conn->query("SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC LIMIT 5");
    $orders = $orders_result ? $orders_result->fetch_all(MYSQLI_ASSOC) : [];
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Harmony Care - Home</title>
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

  <main>

<?php ; ?>
    <h2 class="section-title">Available Products</h2>
    <div class="product-grid">
      
      <?php foreach ($products as $product): ?>
        <div class="product-card" onclick="window.location='product.php?id=<?php echo $product['id']; ?>'">
          <img src="<?php echo htmlspecialchars($product['picture']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
          <h3><?php echo htmlspecialchars($product['name']); ?></h3>
          <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
          <a href="product.php?id=<?php echo $product['id']; ?>" class="btn" onclick="event.stopPropagation();">View</a>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (isset($orders) && count($orders) > 0): ?>
      <h2 class="section-title">Your Past 5 Orders</h2>
        <?php foreach ($orders as $order) : ?>
		<?php
			$order_id = $order['order_id'];
			$orderitems_result = $conn->query("SELECT* FROM order_items WHERE order_id = $order_id");
			$orderitems = $orderitems_result->fetch_all(MYSQLI_ASSOC);
			?>
          <div class="order-item">
            <h4>Order ID: <?php echo $order['order_id']; ?></h4>
            <p class="price">Total: $<?php echo number_format($order['total_amount'], 2); ?></p>
			
			<?php foreach ($orderitems as $item) : ?>
				<p><?php echo htmlspecialchars($item['product_name']) . " (" . $item['quantity'] . ") "; ?> </p>
			<?php endforeach; ?>
				
          </div>
        <?php endforeach; ?>
      
    <?php endif; ?>
  </main>

  <footer>
    <div class="footer-links">
      <a href="contact.html" class="btn">Contact Us</a>
      <button onclick="window.open('error-help.html','Help','width=400,height=400')" class="footer-btn">Help</button>
    </div>
    <p>&copy; 2025 Harmony Care. All rights reserved.</p>
  </footer>
</body>
</html>