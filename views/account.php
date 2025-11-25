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
    $expenses = get_account_expenses($con, $account_id);
}

$saldo = $account["presupuesto"] - $total_expenses;
$porcentaje_gastado = $account["presupuesto"] > 0 ? ($total_expenses / $account["presupuesto"]) * 100 : 0;

// Cargar subcategorías
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
    <link rel="stylesheet" href="../views/style/account_style.css">
    <link rel="icon" href="../views/character/favicon/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="container-fluid main-container">



    <!-- Header -->
    <div class="header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h1>
            <i class="fas fa-credit-card"></i> 
            <span><?= htmlspecialchars($account["nombre"]) ?></span>
        </h1>
        <a href="main.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <!-- Presupuesto -->
        <div class="stat-card budget">
            <div class="stat-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-label">Presupuesto Total</div>
            <div class="stat-value">$<?= number_format($account["presupuesto"], 2) ?></div>
        </div>

        <!-- Total Gastos -->
        <div class="stat-card expenses">
            <div class="stat-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-label">Total de Gastos</div>
            <div class="stat-value">$<?= number_format($total_expenses, 2) ?></div>
            <div class="progress-section">
                <div class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" 
                         style="width: <?= min($porcentaje_gastado, 100) ?>%"
                         aria-valuenow="<?= $porcentaje_gastado ?>" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <small class="text-muted mt-1 d-block"><?= number_format($porcentaje_gastado, 1) ?>% del presupuesto</small>
            </div>
        </div>

        <!-- Saldo Restante -->
        <div class="stat-card balance <?= $saldo < 0 ? 'negative' : '' ?>">
            <div class="stat-icon">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div class="stat-label">Saldo Restante</div>
            <div class="stat-value <?= $saldo < 0 ? 'text-danger' : 'text-success' ?>">
                $<?= number_format($saldo, 2) ?>
            </div>
        </div>
    </div>

    <!-- Agregar Gasto -->
    <div class="form-section">
        <h3>
            <i class="fas fa-plus-circle"></i> 
            <span>Agregar Nuevo Gasto</span>
        </h3>

        <form action="../controllers/process/expenses/create_expense.php" method="POST">
            <input type="hidden" name="account_id" value="<?= $account_id ?>">

            <div class="row g-3">
                <div class="col-lg-5 col-md-12">
                    <label class="form-label">
                        <i class="fas fa-comment-alt me-2"></i>Descripción del gasto
                    </label>
                    <input type="text" class="form-control" name="description" 
                           placeholder="Ej: Compra en supermercado" required>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2"></i>Monto
                    </label>
                    <input type="number" class="form-control" step="0.01" 
                           name="amount" placeholder="0.00" required min="0.01">
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="form-label">
                        <i class="fas fa-tags me-2"></i>Subcategoría
                    </label>
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

                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check-circle me-2"></i>Agregar Gasto
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Gastos -->
    <div class="table-section">
        <h3>
            <i class="fas fa-list"></i> 
            <span>Lista de Gastos</span>
        </h3>

        <?php if ($expenses && $expenses->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th><i class="fas fa-info-circle me-2"></i>Descripción</th>
                            <th><i class="fas fa-money-bill-wave me-2"></i>Monto</th>
                            <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                            <th class="text-center"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($exp = $expenses->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($exp["descripcion"]) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark fs-6">
                                        $<?= number_format($exp["monto"], 2) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($exp["fecha"])) ?></td>
                                <td class="text-center">
                                    <form action="../controllers/process/expenses/delete_expense.php" 
                                          method="POST"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este gasto?');"
                                          class="d-inline">
                                        <input type="hidden" name="id" value="<?= $exp["id"] ?>">
                                        <input type="hidden" name="account_id" value="<?= $account_id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>No hay gastos registrados para esta cuenta.</p>
                <small class="text-muted">Comienza agregando tu primer gasto usando el formulario superior.</small>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>