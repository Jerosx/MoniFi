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

// Calcular presupuestos 50/30/20
$presupuesto_total = $account[Cuenta::PRESUPUESTO];
$presupuesto_necesidades = $presupuesto_total * 0.50; // 50%
$presupuesto_gustos = $presupuesto_total * 0.30;      // 30%
$presupuesto_ahorro = $presupuesto_total * 0.20;      // 20%

// Calcular gastos por categoría
$total_expenses = 0;
$gastos_necesidades = 0;
$gastos_gustos = 0;

if ($expenses && $expenses->num_rows > 0) {
    while ($row = $expenses->fetch_assoc()) {
        $total_expenses += $row[Gasto::MONTO];
        
        // Obtener el categoria_id de la subcategoría para determinar si es Necesidad (1) o Gusto (2)
        $query = "SELECT " . SubcategoriaGasto::CATEGORIA_ID . " as categoria_id 
                  FROM " . SubcategoriaGasto::TBL_NAME . " 
                  WHERE " . SubcategoriaGasto::ID . " = " . intval($row[Gasto::SUBCATEGORIA_GASTO_ID]);
        
        $tipo_result = $con->query($query);
        if ($tipo_result && $tipo_row = $tipo_result->fetch_assoc()) {
            if ($tipo_row['categoria_id'] == CategoriaGasto::NECESIDADES_ID) { // Necesidad
                $gastos_necesidades += $row[Gasto::MONTO];
            } else if ($tipo_row['categoria_id'] == CategoriaGasto::GUSTOS_ID) { // Gusto
                $gastos_gustos += $row[Gasto::MONTO];
            }
        }
    }
    $expenses = get_account_expenses($con, $account_id);
}

// Calcular restantes
$restante_necesidades = $presupuesto_necesidades - $gastos_necesidades;
$restante_gustos = $presupuesto_gustos - $gastos_gustos;
$ahorro_actual = $presupuesto_ahorro; // El ahorro es lo que no se gasta

// Calcular porcentajes
$porcentaje_necesidades = $presupuesto_necesidades > 0 ? ($gastos_necesidades / $presupuesto_necesidades) * 100 : 0;
$porcentaje_gustos = $presupuesto_gustos > 0 ? ($gastos_gustos / $presupuesto_gustos) * 100 : 0;
$porcentaje_gastado = $presupuesto_total > 0 ? ($total_expenses / $presupuesto_total) * 100 : 0;

$saldo = $presupuesto_total - $total_expenses;

// Cargar subcategorías con su tipo
$subcategories = $con->query("
    SELECT 
        sg." . SubcategoriaGasto::ID . " AS id, 
        sg." . SubcategoriaGasto::NOMBRE . " AS nombre, 
        sg." . SubcategoriaGasto::CATEGORIA_ID . " AS categoria_id,
        cg." . CategoriaGasto::NOMBRE . " AS categoria_nombre
    FROM " . SubcategoriaGasto::TBL_NAME . " sg
    JOIN " . CategoriaGasto::TBL_NAME . " cg 
    ON sg." . SubcategoriaGasto::CATEGORIA_ID . " = cg." . CategoriaGasto::ID . "
    ORDER BY sg." . SubcategoriaGasto::CATEGORIA_ID . ", sg." . SubcategoriaGasto::NOMBRE
);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($account[Cuenta::NOMBRE]) ?> - Moni-Fi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../views/style/account_style.css">
    <link rel="icon" href="../views/character/favicon/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="container-fluid main-container">

    <!-- Header -->
    <div class="header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h1>
            <i class="fas fa-credit-card"></i> 
            <span><?= htmlspecialchars($account[Cuenta::NOMBRE]) ?></span>
        </h1>
        <a href="main.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Info Card 50/30/20 -->
    <div class="info-card-50-30-20">
        <div class="rule-header">
            <h2><i class="fas fa-chart-pie"></i> Regla 50/30/20</h2>
            <p>Distribución inteligente de tu presupuesto</p>
        </div>
        <div class="rule-grid">
            <div class="rule-item">
                <div class="rule-icon needs">
                    <i class="fas fa-home"></i>
                </div>
                <div class="rule-content">
                    <h4>50% Necesidades</h4>
                    <p>Gastos esenciales e ineludibles</p>
                </div>
            </div>
            <div class="rule-item">
                <div class="rule-icon wants">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="rule-content">
                    <h4>30% Gustos</h4>
                    <p>Entretenimiento y caprichos</p>
                </div>
            </div>
            <div class="rule-item">
                <div class="rule-icon savings">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="rule-content">
                    <h4>20% Ahorro</h4>
                    <p>Metas y emergencias</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards 50/30/20 -->
    <div class="stats-container-50-30-20">
        
        <!-- Necesidades (50%) -->
        <div class="stat-card-50-30-20 needs">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-info">
                    <h3>Necesidades</h3>
                    <span class="stat-percentage">50%</span>
                </div>
            </div>
            
            <div class="stat-budget">
                <div class="budget-row">
                    <span class="label">Presupuesto:</span>
                    <span class="value">$<?= number_format($presupuesto_necesidades, 2) ?></span>
                </div>
                <div class="budget-row">
                    <span class="label">Gastado:</span>
                    <span class="value spent">$<?= number_format($gastos_necesidades, 2) ?></span>
                </div>
                <div class="budget-row remaining">
                    <span class="label">Restante:</span>
                    <span class="value <?= $restante_necesidades < 0 ? 'text-danger' : 'text-success' ?>">
                        $<?= number_format($restante_necesidades, 2) ?>
                    </span>
                </div>
            </div>

            <div class="stat-progress">
                <div class="progress">
                    <div class="progress-bar bg-needs" role="progressbar" 
                         style="width: <?= min($porcentaje_necesidades, 100) ?>%"
                         aria-valuenow="<?= $porcentaje_necesidades ?>" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <small class="progress-label"><?= number_format($porcentaje_necesidades, 1) ?>% utilizado</small>
            </div>
        </div>

        <!-- Gustos (30%) -->
        <div class="stat-card-50-30-20 wants">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-info">
                    <h3>Gustos</h3>
                    <span class="stat-percentage">30%</span>
                </div>
            </div>
            
            <div class="stat-budget">
                <div class="budget-row">
                    <span class="label">Presupuesto:</span>
                    <span class="value">$<?= number_format($presupuesto_gustos, 2) ?></span>
                </div>
                <div class="budget-row">
                    <span class="label">Gastado:</span>
                    <span class="value spent">$<?= number_format($gastos_gustos, 2) ?></span>
                </div>
                <div class="budget-row remaining">
                    <span class="label">Restante:</span>
                    <span class="value <?= $restante_gustos < 0 ? 'text-danger' : 'text-success' ?>">
                        $<?= number_format($restante_gustos, 2) ?>
                    </span>
                </div>
            </div>

            <div class="stat-progress">
                <div class="progress">
                    <div class="progress-bar bg-wants" role="progressbar" 
                         style="width: <?= min($porcentaje_gustos, 100) ?>%"
                         aria-valuenow="<?= $porcentaje_gustos ?>" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <small class="progress-label"><?= number_format($porcentaje_gustos, 1) ?>% utilizado</small>
            </div>
        </div>

        <!-- Ahorro (20%) -->
        <div class="stat-card-50-30-20 savings">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="stat-info">
                    <h3>Ahorro</h3>
                    <span class="stat-percentage">20%</span>
                </div>
            </div>
            
            <div class="stat-budget">
                <div class="budget-row">
                    <span class="label">Meta de ahorro:</span>
                    <span class="value">$<?= number_format($presupuesto_ahorro, 2) ?></span>
                </div>
                <div class="budget-row">
                    <span class="label">Total gastado:</span>
                    <span class="value spent">$<?= number_format($total_expenses, 2) ?></span>
                </div>
                <div class="budget-row remaining">
                    <span class="label">Ahorro proyectado:</span>
                    <span class="value <?= $saldo < $presupuesto_ahorro ? 'text-warning' : 'text-success' ?>">
                        $<?= number_format(max(0, $saldo), 2) ?>
                    </span>
                </div>
            </div>

            <?php 
            $porcentaje_ahorro_logrado = $presupuesto_ahorro > 0 ? (max(0, $saldo) / $presupuesto_ahorro) * 100 : 0;
            ?>
            <div class="stat-progress">
                <div class="progress">
                    <div class="progress-bar bg-savings" role="progressbar" 
                         style="width: <?= min($porcentaje_ahorro_logrado, 100) ?>%"
                         aria-valuenow="<?= $porcentaje_ahorro_logrado ?>" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <small class="progress-label"><?= number_format($porcentaje_ahorro_logrado, 1) ?>% de la meta</small>
            </div>
        </div>
    </div>

    <!-- Resumen General -->
    <div class="summary-card">
        <div class="summary-header">
            <h3><i class="fas fa-chart-line"></i> Resumen General</h3>
        </div>
        <div class="summary-grid">
            <div class="summary-item">
                <i class="fas fa-wallet"></i>
                <div>
                    <span class="summary-label">Presupuesto Total</span>
                    <span class="summary-value">$<?= number_format($presupuesto_total, 2) ?></span>
                </div>
            </div>
            <div class="summary-item">
                <i class="fas fa-receipt"></i>
                <div>
                    <span class="summary-label">Total Gastado</span>
                    <span class="summary-value spent">$<?= number_format($total_expenses, 2) ?></span>
                </div>
            </div>
            <div class="summary-item">
                <i class="fas fa-balance-scale"></i>
                <div>
                    <span class="summary-label">Saldo Disponible</span>
                    <span class="summary-value <?= $saldo < 0 ? 'text-danger' : 'text-success' ?>">
                        $<?= number_format($saldo, 2) ?>
                    </span>
                </div>
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
                    <select name="subcategory_id" class="form-select" required id="subcategory-select">
                        <option value="" disabled selected>Selecciona una subcategoría</option>
                        <?php if ($subcategories && $subcategories->num_rows > 0): ?>
                            <?php 
                            $current_categoria = null;
                            while ($sc = $subcategories->fetch_assoc()): 
                                // Agrupar por categoría (Necesidades o Gustos)
                                if ($current_categoria !== $sc['categoria_id']) {
                                    if ($current_categoria !== null) {
                                        echo '</optgroup>';
                                    }
                                    $tipo_label = $sc['categoria_id'] == CategoriaGasto::NECESIDADES_ID ? '🏠 Necesidades' : '❤️ Gustos';
                                    echo '<optgroup label="' . $tipo_label . '">';
                                    $current_categoria = $sc['categoria_id'];
                                }
                            ?>
                                <option value="<?= $sc['id'] ?>" data-categoria="<?= $sc['categoria_id'] ?>">
                                    <?= htmlspecialchars($sc['nombre']) ?>
                                </option>
                            <?php endwhile; ?>
                            <?php if ($current_categoria !== null) echo '</optgroup>'; ?>
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
                            <th><i class="fas fa-tags me-2"></i>Categoría</th>
                            <th><i class="fas fa-money-bill-wave me-2"></i>Monto</th>
                            <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                            <th class="text-center"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($exp = $expenses->fetch_assoc()): 
                            // Obtener categoria_id para determinar si es Necesidad o Gusto
                            $query = "SELECT sg." . SubcategoriaGasto::CATEGORIA_ID . " as categoria_id,
                                     sg." . SubcategoriaGasto::NOMBRE . " as subcategoria_nombre
                                     FROM " . SubcategoriaGasto::TBL_NAME . " sg
                                     WHERE sg." . SubcategoriaGasto::ID . " = " . intval($exp[Gasto::SUBCATEGORIA_GASTO_ID]);
                            $tipo_result = $con->query($query);
                            $tipo_data = $tipo_result->fetch_assoc();
                            $es_necesidad = $tipo_data['categoria_id'] == CategoriaGasto::NECESIDADES_ID;
                            $tipo_badge = $es_necesidad ? 'badge-needs' : 'badge-wants';
                            $tipo_icon = $es_necesidad ? 'fa-home' : 'fa-heart';
                            $tipo_text = $es_necesidad ? 'Necesidad' : 'Gusto';
                        ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($exp[Gasto::DESCRIPCION]) ?></strong>
                                </td>
                                <td>
                                    <span class="badge <?= $tipo_badge ?>">
                                        <i class="fas <?= $tipo_icon ?>"></i>
                                        <?= $tipo_text ?>
                                    </span>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($tipo_data['subcategoria_nombre']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark fs-6">
                                        $<?= number_format($exp[Gasto::MONTO], 2) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($exp[Gasto::FECHA])) ?></td>
                                <td class="text-center">
                                    <form action="../controllers/process/expenses/delete_expense.php" 
                                          method="POST"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este gasto?');"
                                          class="d-inline">
                                        <input type="hidden" name="id" value="<?= $exp[Gasto::ID] ?>">
                                        <input type="hidden" name="account_id" value="<?= $account_id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
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