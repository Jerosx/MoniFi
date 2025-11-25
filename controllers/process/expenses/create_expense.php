<?php
// controllers/process/expenses/create_expense.php
$root = realpath(__DIR__ . '/../../../');
require_once($root . "/config.php");
require_once(DB_PATH);
require_once(DB_METADATA_PATH);

// helpers / controladores
require_once($root ."/controllers/utils/get_user_id.php");
require_once($root . "/controllers/expenses_management.php");

// Asegurar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER'] ?? '/');
    exit;
}

// Conexión
$con = create_conection();

// Recoger y sanitizar
$account_id = isset($_POST['account_id']) ? intval($_POST['account_id']) : 0;
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$subcategory_id = isset($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null;

// Validaciones básicas
$errors = [];

if ($account_id <= 0) $errors[] = "Cuenta inválida.";
if ($description === '') $errors[] = "La descripción es requerida.";
if ($amount <= 0) $errors[] = "El monto debe ser mayor que cero.";
if ($subcategory_id === null || $subcategory_id <= 0) $errors[] = "Selecciona una subcategoría.";

// Si hay errores, volver con query string (puedes mejorar mostrando alert en la vista)
if (!empty($errors)) {
    $msg = urlencode(implode(' | ', $errors));
    header("Location: ../../../views/account.php?id={$account_id}&error={$msg}");
    exit;
}

// Intentar crear el gasto usando la función del controlador
$ok = create_expense($con, $account_id, $description, $amount, $subcategory_id);

if ($ok) {
    header("Location: ../../../views/account.php?id={$account_id}&success=1");
    exit;
} else {
    header("Location: ../../../views/account.php?id={$account_id}&error=" . urlencode("No se pudo crear el gasto."));
    exit;
}
