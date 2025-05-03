<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link rel="stylesheet" href="style.css">
</head>
         
  <script>
function setFormAction(action) {
  document.getElementById('cartForm').action = action;
}

function submitBuy() {
  setFormAction('checkout_action.php');
  document.getElementById('cartForm').submit();
}

function showLoginPopup() {
  document.getElementById('loginPopup').style.display = 'flex';
}
</script>


         
         
         
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

  <main class="cart-container">
           <?php if (!$is_logged_in): ?>
<div id="loginPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
background:rgba(0,0,0,0.7); align-items:center; justify-content:center; z-index:1000;">
  <div style="background:white; padding:30px; border-radius:10px; text-align:center; max-width:400px;">
    <h2>Login Required</h2>
    <p>You need to log in to complete your purchase.</p>
    <div style="margin-top:20px;">
      <a href="login.html" style="display:inline-block; margin:5px; padding:10px 20px; background:#4CAF50; color:white; text-decoration:none; border-radius:5px;">Login</a>
      <a href="index.php" style="display:inline-block; margin:5px; padding:10px 20px; background:#2196F3; color:white; text-decoration:none; border-radius:5px;">Return to Home</a>
    </div>
  </div>
</div>
<?php endif; ?>

    <h2>Your Shopping Cart</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
      <form id="cartForm" method="POST">
  <table class="cart-table">
    <thead>
      <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item):
          $item_total = $item['price'] * $item['quantity'];
          $total += $item_total;
      ?>
      <tr>
        <td><?php echo htmlspecialchars($item['name']); ?></td>
        <td>
          
        <input type="number" 
       name="quantities[<?php echo $id; ?>]" 
       value="<?php echo $item['quantity']; ?>" 
       min="1" 
       max="<?php echo $item['stock']; ?>">
       

        </td>
        <td>$<?php echo number_format($item['price'], 2); ?></td>
        <td>$<?php echo number_format($item_total, 2); ?></td>
        <td>
          <a class="remove-btn" href="remove_from_cart.php?id=<?php echo $id; ?>">Remove</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>

  <input type="hidden" id="formAction" name="action_type" value="update">

  <button type="submit" class="btn" onclick="setFormAction('update_cart.php')">Update Quantities</button>

  <button type="button" class="btn" onclick="<?php echo $is_logged_in ? "submitBuy()" : "showLoginPopup()"; ?>">Buy</button>


</form>

<script>
  function setFormAction(action) {
    document.getElementById('cartForm').action = action;
  }
</script>

    <?php else: ?>
      <p>Your cart is empty.</p>
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
