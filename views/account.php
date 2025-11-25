<?php
session_start();
include('../controllers/validate_exist_sesion.php');

$root = realpath(__DIR__ . '/..');

require_once($root . "/config.php");
require_once(DB_PATH);
require_once(DB_METADATA_PATH);

require_once($root . "/controllers/accounts_management.php");
require_once($root . "/controllers/expenses_management.php");

// Validar ID
if (!isset($_GET['id'])) {
    header("Location: accounts.php");
    exit;
}

$account_id = intval($_GET['id']);

$con = create_conection();
$account = get_account_by_id($con, $account_id);
$expenses = get_account_expenses($con, $account_id);

// Calcular total de gastos
$total_expenses = 0;
if ($expenses && $expenses->num_rows > 0) {
    while ($row = $expenses->fetch_assoc()) {
        $total_expenses += $row["monto"];
    }
    // Volvemos a obtener los gastos porque ya iteramos el cursor
    $expenses = get_account_expenses($con, $account_id);
}
// Cargar subcategorías (antes del HTML, justo después de obtener $con)
$subcategories = $con->query("SELECT " . SubcategoriaGasto::ID . " AS id, " . SubcategoriaGasto::NOMBRE . " AS nombre, " . SubcategoriaGasto::CATEGORIA_ID . " AS categoria_id FROM " . SubcategoriaGasto::TBL_NAME . " ORDER BY nombre");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($account["nombre"]) ?> - Moni-Fi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../views/style/main.css">
</head>
<body>

<div class="container main-container">

    <!-- Header -->
    <div class="header-section d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-credit-card"></i> <?= htmlspecialchars($account["nombre"]) ?></h1>

        <a href="main.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Información general -->
    <div class="card p-3 mb-4">
        <h4><i class="fas fa-wallet"></i> Presupuesto: 
            $<?= number_format($account["presupuesto"], 2) ?>
        </h4>

        <h5 class="mt-2"><i class="fas fa-receipt"></i> Total de gastos: 
            $<?= number_format($total_expenses, 2) ?>
        </h5>

        <h5 class="mt-2 <?= ($account["presupuesto"] - $total_expenses) < 0 ? 'text-danger fw-bold' : 'text-success fw-bold' ?>">
            <i class="fas fa-balance-scale"></i> Saldo restante: 
            $<?= number_format($account["presupuesto"] - $total_expenses, 2) ?>
        </h5>
    </div>

    <!-- Agregar gasto -->
    <div class="card p-3 mb-4">
        <h3><i class="fas fa-plus-circle"></i> Agregar Gasto</h3>

        <form action="../controllers/process/expenses/create_expense.php" method="POST">
            <input type="hidden" name="account_id" value="<?= $account_id ?>">

            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Descripción del gasto</label>
                    <input type="text" class="form-control" name="description" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Monto</label>
                    <input type="number" class="form-control" step="0.01" name="amount" required min="0.01">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Subcategoría</label>
                    <select name="subcategory_id" class="form-select" required>
                        <option value="" disabled selected>Selecciona una subcategoría</option>
                        <?php if ($subcategories && $subcategories->num_rows > 0): ?>
                            <?php while ($sc = $subcategories->fetch_assoc()): ?>
                                <option value="<?= $sc['id'] ?>">
                                    <?= htmlspecialchars($sc['nombre']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay subcategorías</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary w-100">
                <i class="fas fa-check-circle"></i> Agregar Gasto
            </button>
        </form>
    </div>


    <!-- Lista de gastos -->
    <div class="card p-3 mb-4">
        <h3><i class="fas fa-list"></i> Lista de Gastos</h3>

        <?php if ($expenses && $expenses->num_rows > 0): ?>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($exp = $expenses->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($exp["descripcion"]) ?></td>
                            <td>$<?= number_format($exp["monto"], 2) ?></td>
                            
                            <!-- CORRECCIÓN: usar `fecha` -->
                            <td><?= $exp["fecha"] ?></td>

                            <td>
                                <form action="../controllers/process/expenses/delete_expense.php" 
                                      method="POST"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este gasto?');">

                                    <input type="hidden" name="id" value="<?= $exp["id"] ?>">
                                    <input type="hidden" name="account_id" value="<?= $account_id ?>">

                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>

            <div class="text-center p-4">
                <i class="fas fa-folder-open fa-2x"></i>
                <p class="mt-3">No hay gastos registrados para esta cuenta.</p>
            </div>

        <?php endif; ?>
    </div>

</div>

</body>
</html>
