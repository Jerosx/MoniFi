<?php
session_start();

include('../controllers/validate_exist_sesion.php');

$root = realpath(__DIR__ . '/..');

include_once($root . "/config.php");
include_once(DB_PATH);
include_once(DB_METADATA_PATH);
include('../controllers/accounts_management.php');

$con = create_conection();
$accounts = get_user_accounts($con);
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
    <link rel="icon" href="../views/character/favicon/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="container main-container">
    
    <!-- Header -->
    <div class="header-section d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-wallet"></i> Moni-Fi</h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i> Bienvenido
        </div>
    </div>

    <!-- Crear nueva cuenta -->
    <div class="create-card mb-4">
        <h2><i class="fas fa-plus-circle"></i> Crear Nueva Cuenta</h2>

        <form action="../controllers/process/accounts/create_account.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-tag"></i> Nombre de la cuenta</label>
                    <input type="text" name="account_name" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-dollar-sign"></i> Presupuesto</label>
                    <input type="number" step="0.01" name="budget" class="form-control" required>
                </div>
            </div>

            <button class="btn btn-primary w-100">
                <i class="fas fa-check-circle"></i> Crear Cuenta
            </button>
        </form>
    </div>

    <!-- Lista de cuentas -->
    <div class="accounts-section">
        <h2><i class="fas fa-list"></i> Mis Cuentas</h2>

        <?php if ($accounts && $accounts->num_rows > 0): ?>
            <?php while ($acc = $accounts->fetch_assoc()): ?>

                <div class="account-card mb-4">

                    <div class="account-header d-flex justify-content-between align-items-center">
                        <h3 class="account-title">
                            <i class="fas fa-credit-card"></i>
                            <?= htmlspecialchars($acc["nombre"]) ?>
                        </h3>

                        <span class="account-status <?= $acc["estado"] == 1 ? 'status-active' : 'status-inactive' ?>">
                            <i class="fas fa-circle"></i> <?= $acc["estado"] == 1 ? "Activa" : "Inactiva" ?>
                        </span>
                    </div>

                    <div class="account-budget">
                        $<?= number_format($acc["presupuesto"], 2) ?>
                    </div>

                    <div class="d-flex gap-2">

                        <!-- Ver cuenta -->
                        <a href="account.php?id=<?= $acc["id"] ?>" class="btn btn-secondary w-100">
                            <i class="fas fa-eye"></i> Ver Cuenta
                        </a>

                        <!-- Eliminar cuenta -->
                        <form action="../controllers/process/accounts/delete_account.php" method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar esta cuenta?');" class="w-100">
                                
                            <input type="hidden" name="id" value="<?= $acc["id"] ?>">

                            <button class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty-state text-center">
                <i class="fas fa-folder-open fa-3x mb-3"></i>
                <p>No tienes cuentas registradas aún.<br>¡Crea tu primera cuenta!</p>
            </div>

        <?php endif; ?>
    </div>

    <!-- Cerrar sesión -->
    <div class="logout-section mt-4">
        <form action="../controllers/close_session.php">
            <button class="btn btn-logout w-100">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
