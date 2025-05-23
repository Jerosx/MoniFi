<?php
include('../user_management.php');

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    echo "<script> alert('Todos los campos deben estar completos');
                        window.location.href='../../views/index.html';
              </script>";
    exit;
}

validate_credentials($username, $password);
?>
