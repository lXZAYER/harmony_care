<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = max(1, intval($qty));
        }
    }
}
header("Location: checkout.php");
exit();
?>