<?php
session_start();

$root = realpath(__DIR__ . '/..');

include_once($root . "/config.php");
include_once(DB_PATH);
include_once(DB_METADATA_PATH);
include_once($root . "/controllers/accounts_management.php");

$con = create_conection();
$accounts = get_user_accounts($con);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cuentas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    
    <h2 class="mb-4">Crear nueva cuenta</h2>

    <form action="../controllers/process/accounts/create_account.php" method="POST" class="card p-4 shadow-sm mb-5">
        <div class="mb-3">
            <label class="form-label">Nombre de la cuenta</label>
            <input type="text" name="account_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Presupuesto</label>
            <input type="number" step="0.01" name="budget" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Crear cuenta</button>
    </form>

    <h2 class="mb-3">Mis cuentas</h2>

    <?php if ($accounts && $accounts->num_rows > 0): ?>
        <?php while ($acc = $accounts->fetch_assoc()): ?>

            <div class="card mb-3 shadow-sm">
                <div class="card-body">

                    <h5 class="card-title"><?= $acc["nombre"] ?></h5>
                    <p><strong>Presupuesto:</strong> $<?= number_format($acc["presupuesto"], 2) ?></p>
                    <p><strong>Estado:</strong> <?= $acc["estado"] == 1 ? "Activa" : "Inactiva" ?></p>

                    <hr>

                    <!-- Formulario Editar -->
                    <form action="../controllers/process/accounts/update_account.php" method="POST" class="row g-2">
                        <input type="hidden" name="id" value="<?= $acc["id"] ?>">

                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" value="<?= $acc["nombre"] ?>" required>
                        </div>

                        <div class="col-md-4">
                            <input type="number" step="0.01" name="budget" class="form-control" value="<?= $acc["presupuesto"] ?>" required>
                        </div>

                        <div class="col-md-2">
                            <select name="state" class="form-select">
                                <option value="1" <?= $acc["estado"] == 1 ? "selected" : "" ?>>Activa</option>
                                <option value="0" <?= $acc["estado"] == 0 ? "selected" : "" ?>>Inactiva</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <button class="btn btn-warning">Actualizar</button>
                        </div>
                    </form>

                    <!-- Formulario Eliminar -->
                    <form action="../controllers/process/accounts/delete_account.php"
                          method="POST" 
                          class="mt-2"
                          onsubmit="return confirm('¿Seguro de eliminar esta cuenta?');">

                        <input type="hidden" name="id" value="<?= $acc["id"] ?>">
                        <button class="btn btn-danger w-100">Eliminar</button>
                    </form>

                </div>
            </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-secondary">No tienes cuentas registradas.</p>
    <?php endif; ?>

    <form action="../controllers/close_session.php">
        <button class="btn btn-danger w-100">Cerrar sesion</button>
    </form>

</div>

</body>
</html>
