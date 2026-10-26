<?php require_once 'config.php';
unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['cart'], $_SESSION['checkout']);
flash('success', 'You have been logged out.'); redirect('index.php');
