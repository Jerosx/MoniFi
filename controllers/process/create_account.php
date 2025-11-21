<?php
include('../accounts_management.php');

$account_name = trim($_POST['account_name'] ?? '');
$salary = trim($_POST['salary'] ?? '');

if (empty($account_name) || empty($salary)) {
    echo "<script> alert('Todos los campos deben estar completos');
                        window.location.href='../../views/register_user.html';
              </script>";
    exit;
}

create_new_money_account($account_name, $salary);
?>
