<?php
include('../user_management.php');

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    echo "<script> alert('Todos los campos deben estar completos');
                        window.location.href='../../views/index.html';
              </script>";
    exit;
}

validate_credentials($email, $password);
?>
