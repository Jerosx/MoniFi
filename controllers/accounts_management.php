<?php
$root = realpath(__DIR__ . '/..');
include_once($root . "/config.php");
include_once(DB_PATH);
include_once(DB_METADATA_PATH);


function get_logged_user_id() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['user_id'] ?? null;
}

function get_user_accounts($con) {
    $user_id = get_logged_user_id();

    $sql = "SELECT 
                c.id,
                c.nombre,
                c.presupuesto,
                c.estado_id AS estado
            FROM " . Cuenta::TBL_NAME . " c
            WHERE c.usuario_id = ?
            ORDER BY c.fecha_creacion DESC";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function create_new_money_account($con, $name, $budget) {
    $user_id = get_logged_user_id();

    $sql = "INSERT INTO " . Cuenta::TBL_NAME . " (
                usuario_id,
                nombre,
                presupuesto,
                estado_id
            ) VALUES (?, ?, ?, 1)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("isd", $user_id, $name, $budget);
    return $stmt->execute();
}

function update_account($con, $id, $name, $budget, $state) {
    $user_id = get_logged_user_id();

    $sql = "UPDATE " . Cuenta::TBL_NAME . "
            SET nombre = ?, presupuesto = ?, estado_id = ?
            WHERE id = ? AND usuario_id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sdiii", $name, $budget, $state, $id, $user_id);
    return $stmt->execute();
}

function delete_account($con, $id) {
    $user_id = get_logged_user_id();

    $sql = "DELETE FROM " . Cuenta::TBL_NAME . "
            WHERE id = ? AND usuario_id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    return $stmt->execute();
}

?>
