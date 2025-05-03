<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = strip_tags(trim($_POST["email"]));
    $message = strip_tags(trim($_POST["message"]));

    $entry = "Name: $name\nEmail: $email\nMessage: $message\n----------------------\n";

    $file = fopen("messages.txt", "a");
    fwrite($file, $entry);
    fclose($file);

    echo "<script>alert('Message sent successfully!'); window.location.href='contact.html';</script>";
} else {
    header("Location: contact.php");
    exit();
}
