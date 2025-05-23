<?php
include('../user_management.php');

$name = $_POST['name'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$username = $_POST['username'] ?? '';
$password =  $_POST['password'] ?? '';

register_user($name, $lastname, $username, $password);
?>
