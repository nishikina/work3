<?php
session_start();

if (isset($_SESSION['cart'])) {
    $itemCount = count($_SESSION['cart']);
    echo $itemCount;
} else {
    echo 0;
}
?>
