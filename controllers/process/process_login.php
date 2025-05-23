<?php
include('../user_management.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

validate_credentials($username, $password);
?>
