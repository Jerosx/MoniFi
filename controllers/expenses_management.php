<?php
$root = realpath(__DIR__ . '/..');

require_once($root . "/config.php");
require_once(DB_PATH);
require_once(DB_METADATA_PATH);
require_once("utils/get_user_id.php");
require_once($root . "/controllers/accounts_management.php");

/**
 * Obtener gastos de una cuenta
 */
function get_account_expenses($con, $account_id) {
    $user_id = get_logged_user_id();
    $account_id = intval($account_id);

    // Validar que la cuenta pertenece al usuario
    $sql_check = "SELECT 1 FROM " . Cuenta::TBL_NAME . "
                  WHERE id = ? AND usuario_id = ?";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("ii", $account_id, $user_id);
    $stmt_check->execute();

    if ($stmt_check->get_result()->num_rows === 0) {
        return null;
    }

    // Obtener gastos
    $sql = "SELECT 
                g.id,
                g.descripcion,
                g.monto,
                g.fecha,
                g.subcategoria_gasto_id
            FROM " . Gasto::TBL_NAME . " g
            WHERE g.cuenta_id = ?
            ORDER BY g.fecha DESC";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();

    return $stmt->get_result();
}

/**
 * Crear gasto
 */
function create_expense($con, $account_id, $description, $amount, $subcategory_id = null) {
    $user_id = get_logged_user_id();
    $account_id = intval($account_id);
    $amount = floatval($amount);

    // Validar cuenta pertenece al usuario
    $sql_check = "SELECT 1 FROM " . Cuenta::TBL_NAME . "
                  WHERE id = ? AND usuario_id = ?";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("ii", $account_id, $user_id);
    $stmt_check->execute();

    if ($stmt_check->get_result()->num_rows === 0) {
        return false;
    }

    if ($subcategory_id !== null) {
        $subcategory_id = intval($subcategory_id);
    }

    // Insertar gasto
    $sql = "INSERT INTO " . Gasto::TBL_NAME . " (
                cuenta_id, descripcion, monto, fecha, subcategoria_gasto_id
            ) VALUES (?, ?, ?, CURDATE(), ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("isdi", $account_id, $description, $amount, $subcategory_id);

    return $stmt->execute();
}

/**
 * Obtener gasto por ID
 */
function get_expense_by_id($con, $expense_id) {
    $user_id = get_logged_user_id();
    $expense_id = intval($expense_id);

    $sql = "SELECT 
                g.id,
                g.cuenta_id,
                g.descripcion,
                g.monto,
                g.fecha,
                g.subcategoria_gasto_id
            FROM gasto g
            INNER JOIN cuenta c ON g.cuenta_id = c.id
            WHERE g.id = ? AND c.usuario_id = ?
            LIMIT 1";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $expense_id, $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

/**
 * Eliminar gasto
 */
function delete_expense($con, $expense_id) {
    $user_id = get_logged_user_id();
    $expense_id = intval($expense_id);

    $sql_check = "SELECT g.id
                  FROM gasto g
                  INNER JOIN cuenta c ON g.cuenta_id = c.id
                  WHERE g.id = ? AND c.usuario_id = ?";

    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("ii", $expense_id, $user_id);
    $stmt_check->execute();

    if ($stmt_check->get_result()->num_rows === 0) {
        return false;
    }

    $sql = "DELETE FROM gasto WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $expense_id);

    return $stmt->execute();
}
