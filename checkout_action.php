<?php 
session_start();
include 'db_connect.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];

    try {
        $conn->beginTransaction();

        $total_amount = 0;

        // Step 1: Check and lock stock
        foreach ($cart as $id => $item) {
            $stmt = $conn->prepare("SELECT stock FROM products WHERE id = :id FOR UPDATE");
            $stmt->execute(['id' => $id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                throw new Exception("Product not found.");
            }

            if ($product['stock'] < $item['quantity']) {
                throw new Exception("Insufficient stock for product: " . htmlspecialchars($item['name']));
            }

            $total_amount += $item['price'] * $item['quantity'];
        }

        // Step 2: Create order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount) VALUES (:user_id, NOW(), :total_amount)");
        $stmt->execute([
            'user_id' => $user_id,
            'total_amount' => $total_amount
        ]);
        $order_id = $conn->lastInsertId();

        // Step 3: Insert items + update stock
        foreach ($cart as $id => $item) {
            // Insert into order_items
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (:order_id, :product_name, :price, :quantity)");
            $stmt_item->execute([
                'order_id' => $order_id,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ]);

            // Update stock
            $stmt_update = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
            $stmt_update->execute([
                'quantity' => $item['quantity'],
                'id' => $id
            ]);
        }

        $conn->commit();
        unset($_SESSION['cart']);
        header("Location: success.php");
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        echo "<p>Error: " . $e->getMessage() . "</p>";
        echo "<a href='checkout.php'>Back to Cart</a>";
        exit();
    }
} else {
    echo "Cart is empty or user not logged in.";
}
?>
