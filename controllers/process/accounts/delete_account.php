<?php
include('../../accounts_management.php');

$id = $_POST['id'];

$con = create_conection();

if (delete_account($con, $id)) {
    echo "<script>alert('Cuenta eliminada.'); window.location.href='../../../public/main.php';</script>";
} else {
    echo "<script>alert('Error.'); window.location.href='../../../public/main.php';</script>";
}
?>
