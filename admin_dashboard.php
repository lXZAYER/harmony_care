<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "harmony_care";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - Harmony Care</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<header class="navbar">
  <div class="logo">Harmony Care Admin</div>
  <nav>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<main class="admin-container">
  <h2>Admin Dashboard 🛠️</h2>

  <section class="admin-add">
    <h3>Add New Product</h3>
    <form action="add_product.php" method="POST" onsubmit="return validateForm();">
  <input name="name" placeholder="Product Name" type="text" />
  <input name="picture" placeholder="Image File Name (e.g. product.jpg)" type="text" />
  <input name="stock" placeholder="Stock" type="number" />
  <input name="price" placeholder="Price" step="0.01" type="number" />
  <label><input name="prescription_required" type="checkbox" /> Requires Prescription</label>
  <button class="btn" type="submit">Add Product</button>
</form>

<script>
function validateForm() {
  var name = document.getElementsByName('name')[0];
  var picture = document.getElementsByName('picture')[0];
  var stock = document.getElementsByName('stock')[0];
  var price = document.getElementsByName('price')[0];

  if (name.value.trim() === '') {
    alert('Please enter Name.');
    name.focus();
    return false;
  }
  if (picture.value.trim() === '') {
    alert('Please enter Picture.');
    picture.focus();
    return false;
  }
  if (stock.value.trim() === '' || isNaN(stock.value) || parseInt(stock.value) < 0) {
    alert('Please enter valid Stock.');
    stock.focus();
    return false;
  }
  if (price.value.trim() === '' || isNaN(price.value) || parseFloat(price.value) < 0) {
    alert('Please enter valid Price.');
    price.focus();
    return false;
  }

  return true;
}
</script>


  </section>

  <section class="admin-list">
    <h3>Current Products</h3>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Stock</th>
          <th>Price</th>
          <th>Prescription</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['stock']) ?></td>
            <td>$<?= htmlspecialchars($product['price']) ?></td>
            <td><?= $product['prescription_required'] ? 'Yes' : 'No' ?></td>
            <td>
              
              <button class="btn" onclick="openModal(
                '<?= htmlspecialchars($product['name']) ?>',
                <?= (int)$product['stock'] ?>,
                <?= (float)$product['price'] ?>,
                <?= (int)$product['prescription_required'] ?>
              )">Edit</button>

              
              <form action="delete_product.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                <button type="submit" class="btn remove-btn">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>

<footer>
  <p>&copy; 2025 Harmony Care. Admin Panel</p>
</footer>


<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h3>Edit Product</h3>
    <form id="editForm" action="edit_product.php" method="POST">
      <input type="hidden" id="originalName" name="original_name">

      <label for="editName">Product Name</label>
      <input type="text" id="editName" name="product_name" required>

      <label for="editStock">Stock</label>
      <input type="number" id="editStock" name="stock" required>

      <label for="editPrice">Price</label>
      <input type="number" id="editPrice" name="price" step="0.01" required>

      <label><input type="checkbox" id="editPrescription" name="requires_prescription"> Requires Prescription</label>

      <button type="submit" class="btn">Update</button>
    </form>
  </div>
</div>

<script>
function openModal(name, stock, price, prescriptionRequired) {
  document.getElementById('editModal').style.display = 'block';
  document.getElementById('originalName').value = name;
  document.getElementById('editName').value = name;
  document.getElementById('editStock').value = stock;
  document.getElementById('editPrice').value = price;
  document.getElementById('editPrescription').checked = prescriptionRequired === 1;
}

function closeModal() {
  document.getElementById('editModal').style.display = 'none';
}
</script>

</body>
</html>

<?php $conn->close(); ?>
