<?php
include('../../accounts_management.php');

$id = $_POST['id'];
$name = trim($_POST['name']);
$budget = trim($_POST['budget']);
$state = trim($_POST['state']);

$con = create_conection();

if (update_account($con, $id, $name, $budget, $state)) {
    echo "<script>alert('Cuenta actualizada.'); window.location.href='../../../public/main.php';</script>";
} else {
    echo "<script>alert('Error.'); window.location.href='../../../public/main.php';</script>";
}
?>
