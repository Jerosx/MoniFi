<?php
include('../../accounts_management.php');

$name = trim($_POST['account_name'] ?? '');
$budget = trim($_POST['budget'] ?? '');

$con = create_conection();

if (create_new_money_account($con, $name, $budget)) {
    echo "<script>alert('Cuenta creada.'); window.location.href='../../../public/main.php';</script>";
} else {
    echo "<script>alert('Error.'); window.location.href='../../../public/main.php';</script>";
}
?>
