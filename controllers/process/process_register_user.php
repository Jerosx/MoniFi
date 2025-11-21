<?php
include('../user_management.php');

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($name) || empty($email) || empty($password)) {
    echo "<script> alert('Todos los campos deben estar completos');
                        window.location.href='../../public/register_user.html';
              </script>";
    exit;
}

register_user($name, $email, $password);
?>
