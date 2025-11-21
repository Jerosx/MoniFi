<?php

$rootPath = realpath(__DIR__ . '/..');
include_once($rootPath . '/config.php');
include_once(DB_PATH);
include_once(DB_METADATA_PATH);


function create_new_money_account($conection, $name, $salary) {

    $user_id = $_SESSION['usuario'];

    $sql = "INSERT INTO " . TblCuenta::TBL_NAME . " (" .
           TblCuenta::NOMBRE . ", " .
           TblCuenta::SALARIO . ", " .
           TblCuenta::PROPIETARIO .
           ") VALUES (?, ?, ?)";

    $stmt = $conection->prepare($sql);
    $stmt->bind_param("sdi", $name, $salary, $user_id);

    $success = $stmt->execute();

    $stmt->close();
    return $success;
}


function add_debt($money_account_id, $desciption, $mount, $debt_type){

    

}







?>