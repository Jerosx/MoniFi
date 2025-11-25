<?php
// Subir 3 niveles hasta el root del proyecto
$root = realpath(__DIR__ . '/../../../');

// Archivos base
require_once($root . "/config.php");
require_once(DB_PATH);
require_once(DB_METADATA_PATH);

// Controladores
require_once($root ."/controllers/utils/get_user_id.php");
require_once($root . "/controllers/expenses_management.php");

// Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit;
}

$con = create_conection();

// Recibir datos
$expense_id  = intval($_POST['id'] ?? 0);
$account_id  = intval($_POST['account_id'] ?? 0);

// Validar parámetros
if ($expense_id <= 0 || $account_id <= 0) {
    $msg = urlencode("Parámetros inválidos");
    header("Location: ../../../views/account.php?id={$account_id}&error={$msg}");
    exit;
}

// Borrar gasto
$ok = delete_expense($con, $expense_id, $account_id);

if ($ok) {
    header("Location: ../../../views/account.php?id={$account_id}&success=Gasto eliminado");
} else {
    header("Location: ../../../views/account.php?id={$account_id}&error=" . urlencode("No se pudo eliminar el gasto."));
}

exit;
