<?php
include('../user_management.php');

$name = trim($_POST['name'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($name) || empty($lastname) || empty($username) || empty($password)) {
    echo "<script> alert('Todos los campos deben estar completos');
                        window.location.href='../../views/register_user.html';
              </script>";
    exit;
}

register_user($name, $lastname, $username, $password);
?>
