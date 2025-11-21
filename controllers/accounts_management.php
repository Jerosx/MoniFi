<?php

$rootPath = realpath(__DIR__ . '/..');
include_once($rootPath . '/config.php');
include_once(DB_PATH);
include_once(DB_METADATA_PATH);

/* ============================================================
   Obtener ID real del usuario logueado
============================================================ */
function get_logged_user_id($con) 
{
    if (!isset($_SESSION['usuario'])) {
        return null;
    }

    $email = $_SESSION['usuario'];

    $sql = "SELECT " . TblUsuarios::ID . " 
            FROM " . TblUsuarios::TBL_NAME . "
            WHERE " . TblUsuarios::EMAIL . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $result[TblUsuarios::ID] ?? null;
}


/* ============================================================
   Crear nueva cuenta
============================================================ */
function create_new_money_account($con, $name, $budget)
{
    $user_id = get_logged_user_id($con);
    if (!$user_id) return false;

    $sql = "INSERT INTO " . TblCuenta::TBL_NAME . " (
                " . TblCuenta::NOMBRE . ",
                " . TblCuenta::PRESUPUESTO . ",
                " . TblCuenta::USUARIO_ID . ",
                " . TblCuenta::ESTADO . "
            ) VALUES (?, ?, ?, 1)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sdi", $name, $budget, $user_id);

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}


/* ============================================================
   Obtener cuentas del usuario
============================================================ */
function get_user_accounts($con)
{
    $user_id = get_logged_user_id($con);
    if (!$user_id) return null;

    $sql = "SELECT * FROM " . TblCuenta::TBL_NAME . "
            WHERE " . TblCuenta::USUARIO_ID . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}


/* ============================================================
   Actualizar cuenta
============================================================ */
function update_account($con, $id, $name, $budget, $state)
{
    $sql = "UPDATE " . TblCuenta::TBL_NAME . " SET "
         . TblCuenta::NOMBRE . " = ?, "
         . TblCuenta::PRESUPUESTO . " = ?, "
         . TblCuenta::ESTADO . " = ? 
           WHERE " . TblCuenta::ID . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sdii", $name, $budget, $state, $id);

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}


/* ============================================================
   Eliminar
============================================================ */
function delete_account($con, $id)
{
    $sql = "DELETE FROM " . TblCuenta::TBL_NAME . "
            WHERE " . TblCuenta::ID . " = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}

?>
