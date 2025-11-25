<?php
session_start();

include('../controllers/validate_exist_sesion.php');

$root = realpath(__DIR__ . '/..');

include_once($root . "/config.php");
include_once(DB_PATH);
include_once(DB_METADATA_PATH);
include('../controllers/accounts_management.php');
include('../controllers/user_management.php');

$con = create_conection();
$accounts = get_user_accounts($con);
$name_user = get_logged_user_name($con);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cuentas - Moni-Fi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../views/style/main.css">
    <link rel="stylesheet" href="../views/style/main_style.css">
    <link rel="icon" href="../views/character/favicon/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="container-fluid main-container">
    
    <!-- Header -->
    <div class="header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h1>
            <i class="fas fa-wallet"></i> 
            <span>Moni-Fi</span>
        </h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i> 
            <span>Bienvenido, <strong><?= htmlspecialchars($name_user) ?></strong></span>
        </div>
    </div>

    <!-- Crear nueva cuenta -->
    <div class="create-section">
        <h2>
            <i class="fas fa-plus-circle"></i> 
            <span>Crear Nueva Cuenta</span>
        </h2>

        <form action="../controllers/process/accounts/create_account.php" method="POST">
            <div class="row g-3">
                <div class="col-lg-6 col-md-12">
                    <label class="form-label">
                        <i class="fas fa-tag me-2"></i>Nombre de la cuenta
                    </label>
                    <input type="text" name="account_name" class="form-control" 
                           placeholder="Ej: Gastos del hogar" required>
                </div>

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-2"></i>Presupuesto
                    </label>
                    <input type="number" step="0.01" name="budget" class="form-control" 
                           placeholder="0.00" required min="0.01">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check-circle me-2"></i>Crear Cuenta
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de cuentas -->
    <div class="accounts-section">
        <h2>
            <i class="fas fa-list"></i> 
            <span>Mis Cuentas</span>
        </h2>

        <?php if ($accounts && $accounts->num_rows > 0): ?>
            <div class="accounts-grid">
                <?php while ($acc = $accounts->fetch_assoc()): ?>

                    <div class="account-card">
                        <div class="account-header">
                            <div class="account-title-wrapper">
                                <h3 class="account-title">
                                    <i class="fas fa-credit-card"></i>
                                    <?= htmlspecialchars($acc["nombre"]) ?>
                                </h3>
                                <span class="account-status <?= $acc["estado"] == 1 ? 'status-active' : 'status-inactive' ?>">
                                    <i class="fas fa-circle"></i> 
                                    <?= $acc["estado"] == 1 ? "Activa" : "Inactiva" ?>
                                </span>
                            </div>
                        </div>

                        <div class="account-budget">
                            <div class="budget-label">Presupuesto</div>
                            <div class="budget-amount">$<?= number_format($acc["presupuesto"], 2) ?></div>
                        </div>

                        <div class="account-actions">
                            <!-- Ver cuenta -->
                            <a href="account.php?id=<?= $acc["id"] ?>" class="btn btn-view">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>

                            <!-- Eliminar cuenta -->
                            <form action="../controllers/process/accounts/delete_account.php" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta cuenta?');" 
                                  class="delete-form">
                                <input type="hidden" name="id" value="<?= $acc["id"] ?>">
                                <button type="submit" class="btn btn-delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>

        <?php else: ?>

            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>No tienes cuentas registradas aún.</p>
                <small class="text-muted">¡Crea tu primera cuenta usando el formulario superior!</small>
            </div>

        <?php endif; ?>
    </div>

    <!-- Cerrar sesión -->
    <div class="logout-section">
        <form action="../controllers/close_session.php">
            <button type="submit" class="btn btn-logout">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>